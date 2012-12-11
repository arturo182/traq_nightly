<?php
	include dirname(__FILE__) . '/../../../bootstrap.php';
	include dirname(__FILE__) . '/../../helpers/uri.php';

	use nightly\models\Build;
	use traq\models\Project;
	use avalon\Database;

	//10 hours should be enough
	set_time_limit(36000);

	//get build directory
	$settings = settings('nightly');
	$settings = json_decode($settings);
	$builds_dir = ($settings->builds_dir != '') ? $settings->builds_dir : dirname(__FILE__) . '/builds';

	$projects = Project::fetch_all();
	foreach($projects as $project) {
		if($project->build_enabled && (Time::to_unix($project->build_at) <= time())) {
			$success = 1;
			$console = '';
		
			if(is_writeable($builds_dir)) {
				//grab the build_id
				$last_build = Database::connection()->select('build_id')->from('builds')->where('project_id', $project->id)->order_by('build_id', 'DESC')->limit(1)->exec()->fetch();		
				$build_id = is_array($last_build) ? ((int)$last_build['build_id'] + 1) : 1;
				$build_dir = $builds_dir . '/' . $project->slug . '/' . $build_id;

				mkdir($build_dir, 0755, true);
				chdir($build_dir);

				$console .= 'Building ' . $project->slug .' in ' . $build_dir . PHP_EOL;

				$cmds = preg_split('/\r\n|\r|\n/', htmlspecialchars_decode($project->build_cmds));
				$cmds = array_filter($cmds, 'strlen');

				$start_time = microtime(true);
				foreach($cmds as $cmd) {
					$console .= '$ ' . $cmd . PHP_EOL;
					$output = array();
					$exit_code = 0;

					exec($cmd . ' 2>&1', $output, $exit_code);

					$console .= implode(PHP_EOL, $output) . (count($output) ? PHP_EOL : '');
					$console .= 'Exit code: ' . $exit_code . PHP_EOL;
				
					if($exit_code != 0) {
						$console .= 'Command failed, will not continue' . PHP_EOL;
						$success = 0;
						break;
					}
				}
				$end_time = microtime(true);
			} else {
				$console .= 'Builds dir ' . $builds_dir . ' is not writeable!' . PHP_EOL;
				$success = 0;
			}

			$console .= 'Finished: ' . ($success ? 'SUCCESS' : 'FAILURE') . PHP_EOL;

			$artifact_dir = md5(rand());
			mkdir($artifact_dir);

			$artifacts = array();
			foreach(explode(',', $project->build_artifacts) as $artifact) {
				$artifact = trim($artifact);
				if(file_exists($artifact)) {
					$artifacts[] = $artifact;

				}
			}

			//leave only artifact files
			$items = array_merge(glob('*'), glob('.*'));
			foreach($items as $item) {
				if(($item == '.') || ($item == '..'))
					break;
		
				if(is_dir($item)) {
					echo 'rm -rf ' . $item . PHP_EOL;
					//exec('rm -rf ' . $item);
				} else if(!in_array($item, $artifacts)) {
				echo 'unlink ' . $item . PHP_EOL;
					//unlink($item);
				}
			}

			//update next build time
			$proj = Project::find($project->id);
			$proj->set('build_at', date("Y-m-d H:i:s", time() + $proj->build_interval));
			$proj->save();

			//save build info
			$build = new Build(array(
				'build_id' => $build_id,
				'duration' => $end_time - $start_time,
				'project_id' => $project->id,
				'built_at' => date('Y-m-d H:i:s', $start_time),
				'status' => $success,
				'console' => $console,
				'artifacts' => implode(',', $artifacts)
			));
			$build->save();	
		}
	}
?>