<?php

include APPPATH . '/controllers/projectsettings/app_controller.php';

class NightlySettingsController extends ProjectSettingsAppController 
{
	public function action_index()
	{
		$project = clone $this->project;

		if(Request::$method == 'post') {

			$project->set(array(
				'build_enabled' => Request::$post['build_enabled'],
				'build_cmds' => Request::$post['build_cmds'],
				'build_artifacts' => Request::$post['build_artifacts'],
				'build_interval' => Request::$post['build_interval'] * Request::$post['build_interval_unit'],
				'build_at' => (Request::$post['build_at'] != '') ? Request::$post['build_at'] : date("Y-m-d H:i:s", time())
			));

			if($project->is_valid()){

				$project->save();
				Request::redirect(Request::base($project->href('settings/nightly')));
			}
		}
		
		View::set('project', $project);
	}
}
?>