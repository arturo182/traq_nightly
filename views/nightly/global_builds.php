	<div class="content">
		<h2 id="page_title"><?php echo l('nightly.all_builds'); ?></h2>
	</div>

	<table class="list">
		<thead>
			<th class="s">S</th>
			<th class="fixed_name"><?php echo l('name'); ?></th>
			<th class="last_success"><?php echo l('nightly.last_success'); ?></th>
			<th class="last_failure"><?php echo l('nightly.last_failure'); ?></th>
			<th class="last_duration"><?php echo l('nightly.last_duration'); ?></th>
		</thead>
		<tbody>
		<?php foreach ($projects as $project) { ?>
			<?php if($project->build_recent) { ?>
			<tr>
				<td><?php echo $project->build_recent->bullet(); ?></td>
				<td><?php echo HTML::link($project->name, $project->href('nightly')); ?></td>
				<td><?php echo $project->build_success ? time_ago($project->build_success->built_at, true, true) . ' ('.HTML::link('#'.$project->build_success->build_id, $project->build_success->href()).')' : ''; ?></td>
				<td><?php echo $project->build_failure ? time_ago($project->build_failure->built_at, true, true) . ' ('.HTML::link('#'.$project->build_failure->build_id, $project->build_failure->href()).')': ''; ?></td>
				<td><?php echo time_difference_in_words(time() - $project->build_recent->duration, false); ?></td>
			</tr>
			<?php } ?>
		<?php } ?>
		</tbody>
	</table>