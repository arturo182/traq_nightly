<?php
namespace nightly\controllers;

include APPPATH . '/plugins/nightly/helpers/filesize.php';

use traq\controllers\AppController;
use traq\models\Project;
use avalon\core\Controller;
use avalon\output\View;
use avalon\http\Router;
use avalon\Database;
use nightly\models\Build;

class Nightly extends AppController
{
	public function action_global_builds()
	{
		$projects = Project::select('*')->where('build_enabled', '1')->exec()->fetch_all();
		View::set('projects', $projects);
	}

	public function action_builds($project_slug)
	{
		$project = Project::find('slug', $project_slug);
		$builds = Build::select()->where('project_id', $project->id)->order_by('build_id', 'DESC')->exec()->fetch_all();

		$last_artifacts = Build::select()->where(array(array('project_id', $project->id), array('artifacts', '', '!='), array('status', '1')))->order_by('build_id', 'DESC')->limit(1)->exec()->fetch();

		$success = count(Build::select()->where(array(array('project_id', $project->id), array('status', '1')))->exec()->fetch_all());

		$duration = 0;
		foreach($builds as $build) {
			$duration += $build->duration;
		}
		
		if(count($builds))
			$duration /= count($builds);

		$stats = array(
			'count' => count($builds),
			'successful' => count($builds) ? ($success * 100.0 / count($builds)) : 0,
			'duration' => $duration
		);

		View::set('last_artifacts', $last_artifacts);
		View::set('stats', $stats);
		View::set('builds', $builds);
	}

	public function action_view($project_slug, $build_id)
	{
		$project = Project::find('slug', $project_slug);
		$build = Build::select()->where(array(array('project_id', $project->id), array('build_id', $build_id)))->exec()->fetch();
		if($build) {
			$prev_build = Build::select()->where(array(array('project_id', $project->id), array('build_id', $build_id-1)))->exec()->fetch();
			$next_build = Build::select()->where(array(array('project_id', $project->id), array('build_id', $build_id+1)))->exec()->fetch();

			View::set('build', $build);
			View::set('prev_build', $prev_build);
			View::set('next_build', $next_build);
		} else {
			$this->show_404();
		}
	}

	public function action_output($project_slug, $build_id)
	{		
		$project = Project::find('slug', $project_slug);
		$build = Build::select()->where(array(array('project_id', $project->id), array('build_id', $build_id)))->exec()->fetch();

		$this->_render['layout'] = 'plain';

		View::set('console', $build->console);
	}

	public function action_artifact($project_slug, $build_id, $artifact)
	{
		$project = Project::find('slug', $project_slug);
		$build = Build::select()->where(array(array('project_id', $project->id), array('build_id', $build_id)))->exec()->fetch();
		
		$artifacts = explode(',', $build->artifacts);
		if(in_array($artifact, $artifacts)) {
			$settings = settings('nightly');
			$settings = json_decode($settings);
			$builds_dir = ($settings->builds_dir != '') ? $settings->builds_dir : dirname(__FILE__) . '/../builds';
			$build_dir = $builds_dir . '/' . $project->slug . '/' . $build->build_id;

			if(file_exists($build_dir . '/' . $artifact)) {
				header('Content-Length: ' . filesize($build_dir . '/' . $artifact));
				header('Content-type: application/octet-stream');
 				readfile($build_dir . '/' . $artifact);
				exit;
			}
		}

		$this->show_404();
	}
}

?>