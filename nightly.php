<?php
/*!
 * Traq
 * Copyright (C) 2009-2012 Traq.io
 * 
 * This file is part of Traq.
 * 
 * Traq is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 only.
 * 
 * Traq is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Traq. If not, see <http://www.gnu.org/licenses/>.
 */
namespace traq\plugins;

include APPPATH . '/plugins/nightly/models/build.php';

use Avalon\Database;
use \FishHook;
use \Project;
use \Router;
use \Build;

/**
 * Nightly snapshot builder.
 *
 * @package Traq
 * @subpackage Plugins
 * @author arturo182
 * @copyright (c) arturo182
 */
class Nightly extends \traq\libraries\Plugin
{
	public static function info()
	{
		return array(
			'name' => HTML::link('Nightly', '/admin/plugins/nightly'),
			'version' => '0.1',
			'author' => 'arturo182'
		);
	}

	public static function __install()
	{
		global $db;

		$value = json_encode(array('builds_dir' => ''));
		Database::connection()->insert(array('setting' => 'nightly', 'value' => $value))->into('settings')->exec();

		Database::connection()->query("CREATE TABLE IF NOT EXISTS `{$db['prefix']}builds` ( ".
						  "	`id` bigint(20) NOT NULL AUTO_INCREMENT, ".
						  "	`build_id` bigint(20) NOT NULL, ".
						  "	`duration` float NOT NULL, ".
						  "	`project_id` bigint(20) NOT NULL, ".
						  "	`built_at` datetime NOT NULL, ".
						  "	`status` tinyint(1) NOT NULL, ".
						  "	`console` text COLLATE utf8_unicode_ci NOT NULL, ".
						  "	`artifacts` text COLLATE utf8_unicode_ci NOT NULL, ".
						  "	PRIMARY KEY (`id`) ".
						  ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		Database::connection()->query("ALTER TABLE `{$db['prefix']}projects` ".
						  "	ADD `build_enabled` TINYINT(1) NOT NULL DEFAULT '0', ".
						  "	ADD `build_cmds` TEXT COLLATE utf8_unicode_ci NOT NULL, ".
						  "	ADD `build_artifacts` TEXT COLLATE utf8_unicode_ci NOT NULL, ".
						  "	ADD `build_interval` INT NOT NULL, ".
						  "	ADD `build_at` DATETIME NOT NULL;");

		copy(APPPATH . '/plugins/nightly/images/bullet_green.png', APPPATH . '/../assets/images/bullet_green.png');
		copy(APPPATH . '/plugins/nightly/images/bullet_red.png', APPPATH . '/../assets/images/bullet_red.png');
	}

	public static function __uninstall()
	{		
		global $db;

		Database::connection()->delete()->from('settings')->where('setting', 'nightly')->exec();
		Database::connection()->query("DROP TABLE `{$db['prefix']}builds`;");
		Database::connection()->query("ALTER TABLE `{$db['prefix']}projects` ".
						  "	DROP `build_enabled`, ".
						  "	DROP `build_cmds`, ".
						  "	DROP `build_artifacts`, ".
						  "	DROP `build_interval`, ".
						  "	DROP `build_at`;");

		unlink(APPPATH . '/../assets/images/bullet_red.png');
		unlink(APPPATH . '/../assets/images/bullet_green.png');
	}

	public static function init()
	{
		FishHook::add('template:layouts/default/main_nav', function($project)
		{
			if($project) {
				echo '<li'. iif(active_nav('/:slug/nightly(.*)'), ' class="active"') .'>'. HTML::link('Builds', $project->href("nightly")) .'</li>'.PHP_EOL;
			} else {
				echo '<li'. iif(active_nav('/nightly'), ' class="active"') .'>'. HTML::link('Builds', '/nightly') .'</li>'.PHP_EOL;
			}
		});
		
		FishHook::add('template:projectsettings/_nav', function($project)
		{
			echo '<li' . iif(active_nav('/:slug/settings/nightly'), ' class="active"') . '>' . HTML::link('Builds', "{$project->slug}/settings/nightly") . '</li>';
		});
		
		FishHook::add('model::__construct', function($name, $obj, &$properties)
		{
			if($name != 'Project')
				return;

			$properties = array_merge($properties, array('build_interval', 'build_artifacts', 'build_cmds', 'build_at', 'build_enabled'));
		});
		
		FishHook::add('model::__get', function($name, $var, $data, $val)
		{
			if($name != 'Project')
				return;

			$builds = Build::select('*')->where('project_id', $data['id']);
			if($var == 'build_recent') {
				$val = $builds->order_by('build_id', 'DESC')->limit(1)->exec()->fetch();	
			} else if($var == 'build_success') {
				$val = $builds->where('status', '1')->order_by('build_id', 'DESC')->limit(1)->exec()->fetch();	
			} else if($var == 'build_failure') {
				$val = $builds->where('status', '0')->order_by('build_id', 'DESC')->limit(1)->exec()->fetch();	
			} 
		}); 

		Router::add('/nightly', 'Nightly::global_builds');
		Router::add('/admin/plugins/nightly', 'NightlyAdmin::index');
		Router::add('/' . RTR_PROJSLUG . '/nightly', 'Nightly::builds/$1');
		Router::add('/' . RTR_PROJSLUG . '/settings/nightly', 'NightlySettings::index');
		Router::add('/' . RTR_PROJSLUG . '/nightly/(?P<build_id>[0-9]+)', 'Nightly::view/$1,$2');
		Router::add('/' . RTR_PROJSLUG . '/nightly/(?P<build_id>[0-9]+)/output.txt', 'Nightly::output/$1,$2');
		Router::add('/' . RTR_PROJSLUG . '/nightly/(?P<build_id>[0-9]+)/artifact/(?P<artifact>[a-zA-Z0-9\-\_\.]+)', 'Nightly::artifact/$1,$2,$3');

		//this we are sure that the Project constructor is called in time
		$p = new Project;
		unset($p);
	}
}

?>
