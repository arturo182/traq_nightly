<div class="content">
	<h2 id="page_title"><?php echo l('nightly.build_settings'); ?></h2>
</div>
<?php View::render('projectsettings/_nav'); ?>
<div class="content">
	<form action="<?php echo Request::full_uri(); ?>" method="post">
		<?php show_errors($project->errors); ?>
		<div class="tabular box">
			<?php View::render('nightlysettings/_form', array('project' => $project)); ?>
		</div>
		<div class="actions">
			<input type="submit" value="<?php echo l('save'); ?>" />
		</div>
	</form>
</div>