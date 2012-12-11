<?php
namespace nightly\models;

use avalon\database\Model;
use \traq\models\Project;

class Build extends Model
{
	protected static $_name = 'builds';
	protected static $_properties = array(
		'id',
		'build_id',
		'duration',
		'project_id',
		'built_at',
		'status',
		'console',
		'artifacts'
	);
	
	protected static $_belongs_to = array('project' => array('model' => 'traq\models\project'));

	public function is_valid() { return true; }

	public function href($uri = false)
	{
		return '/' . $this->project->slug . '/nightly/' . $this->build_id . ($uri ? $uri : '');
	}
	
	public function bullet()
	{
		$image = $this->status ? 'bullet_green.png' : 'bullet_red.png';
		$title = $this->status ? l('nightly.success') : l('nightly.failure');
		return '<img src="/assets/images/' . $image . '" style="vertical-align: middle;" alt="' . $title . '" title="' . $title . '">';
	}

	public function build_dir()
	{
		$settings = settings('nightly');
		$settings = json_decode($settings);
		$builds_dir = ($settings->builds_dir != '') ? $settings->builds_dir : dirname(__FILE__) . '/../builds';
		return $builds_dir . '/' . $this->project->slug . '/' . $this->build_id;
	}
}

?>