<?php
namespace nightly\controllers;

use traq\controllers\Admin\AppController;
use avalon\http\Request;
use avalon\output\View;
use avalon\Database;

class Admin extends AppController
{
	public function __construct() 
	{
		parent::__construct();
		
		$this->_render['view'] = 'nightly/' . $this->_render['view'];
	}
	
	public function action_index()
	{
		if(Request::method() == 'post') {
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