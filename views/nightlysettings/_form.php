<div class="group">
	<label>Enable builds</label>
	<?php echo Form::checkbox('build_enabled', 1, array('checked' => $project->build_enabled == 1 ? true : false)); ?> <?php echo Form::label(l('yes'), 'build_enabled'); ?>
</div>
<div class="group">
	<label>Commands</label>
	<?php echo Form::textarea('build_cmds', array('value' => $project->build_cmds)); ?><br><abbr title="The list of commands to be performed at the build time. Use new line for every command.">?</abbr>
</div>
<div class="group">
	<label>Artifacts</label>
	<?php echo Form::text('build_artifacts', array('value' => $project->build_artifacts)); ?> <abbr title="A comma-separated list of files to be left after the build. Don't use spaces next to the commas.">?</abbr>
</div>
<div class="group">
	<label>Build interval</label>
	<?php echo Form::text('build_interval', array('value' => $project->build_interval)); ?> <?php echo Form::select('build_interval_unit', array(array('value' => '1', 'label' => 'seconds'), array('value' => '60', 'label' => 'minutes'), array('value' => '3600', 'label' => 'hours'), array('value' => '86400', 'label' => 'days'))); ?>
</div>
<div class="group">
	<label>Starting from</label>
	<?php echo Form::text('build_at', array('value' => ($project->build_at > 0 ? $project->build_at : ''), 'placeholder' => 'YYYY-MM-DD HH:MM:SS')); ?> <abbr title="Time of the next build. Leave empty to use current time.">?</abbr>
</div>