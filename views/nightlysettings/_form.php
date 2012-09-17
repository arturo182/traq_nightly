<div class="group">
	<label><?php echo l('plugins.nightly.enable_builds'); ?></label>
	<?php echo Form::checkbox('build_enabled', 1, array('checked' => $project->build_enabled == 1 ? true : false)); ?> <?php echo Form::label(l('yes'), 'build_enabled'); ?>
</div>
<div class="group">
	<label><?php echo l('plugins.nightly.commands'); ?></label>
	<?php echo Form::textarea('build_cmds', array('value' => $project->build_cmds)); ?><br><abbr title="<?php echo l('plugins.nightly.commands_help'); ?>">?</abbr>
</div>
<div class="group">
	<label><?php echo l('plugins.nightly.artifacts'); ?></label>
	<?php echo Form::text('build_artifacts', array('value' => $project->build_artifacts)); ?> <abbr title="<?php echo l('plugins.nightly.artifacts_help'); ?>">?</abbr>
</div>
<div class="group">
	<label><?php echo l('plugins.nightly.build_interval'); ?></label>
	<?php echo Form::text('build_interval', array('value' => $project->build_interval)); ?> <?php echo Form::select('build_interval_unit', array(array('value' => '1', 'label' => 'seconds'), array('value' => '60', 'label' => 'minutes'), array('value' => '3600', 'label' => 'hours'), array('value' => '86400', 'label' => 'days'))); ?>
</div>
<div class="group">
	<label><?php echo l('plugins.nightly.starting_from'); ?></label>
	<?php echo Form::text('build_at', array('value' => ($project->build_at > 0 ? $project->build_at : ''), 'placeholder' => 'YYYY-MM-DD HH:MM:SS')); ?> <abbr title="<?php echo l('plugins.nightly.starting_from_help'); ?>">?</abbr>
</div>