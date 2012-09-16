<div class="content">
	<h3>Nightly settings</h3>
	<form action="<?php echo Request::full_uri(); ?>" method="post">
		<div class="tabular box">
			<div class="group">
				<label>Build directory</label>
				<?php echo Form::text('settings[builds_dir]', array('value' => ($settings->builds_dir != '') ? $settings->builds_dir : '')); ?> <abbr title="No trailing slash. If the directory is not writeable, you're gonna have a bad time.">?</abbr>
			</div>
		</div>
		<div class="actions">
			<input type="submit" value="<?php echo l('save'); ?>" />
		</div>
	</form>
</div>