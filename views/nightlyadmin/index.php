<div class="content">
	<h3><?php echo l('nightly.settings'); ?></h3>
	<form action="<?php echo Request::full_uri(); ?>" method="post">
		<div class="tabular box">
			<div class="group">
				<label><?php echo l('nightly.build_directory'); ?></label>
				<?php echo Form::text('settings[builds_dir]', array('value' => ($settings->builds_dir != '') ? $settings->builds_dir : '')); ?> <abbr title="<?php echo l('nightly.build_directory_help'); ?>">?</abbr>
			</div>
		</div>
		<div class="actions">
			<input type="submit" value="<?php echo l('save'); ?>" />
		</div>
	</form>
</div>