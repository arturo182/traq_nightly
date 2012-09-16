<?php

include APPPATH . '/controllers/admin/app_controller.php';

use avalon\Database;

class NightlyAdminController extends AdminAppController
{
	public function action_index()
	{
		if(Request::$method == 'post') {
			$settings = Request::$post['settings'];
			$settings = json_encode($settings);

			Database::connection()->update('settings')->set(array('value' => $settings))->where('setting', 'nightly')->exec();
			Request::redirect('/admin/plugins');
		}

		$settings = settings('nightly');
		$settings = json_decode($settings);

		View::set('settings', $settings);
	}
}
?>