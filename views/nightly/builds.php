	<div class="content">
		<h2 id="page_title"><?php echo l('plugins.nightly.builds'); ?></h2>

	<?php if($last_artifacts) { ?>
		<br>
		<h3><?php echo l('plugins.nightly.last_success_artifacts'); ?></h3>
		<ul>
		<?php foreach(explode(',', $last_artifacts->artifacts) as $artifact) { ?>
			<li><?php echo HTML::link($artifact, $last_artifacts->href('/artifact/' . $artifact)); ?> (<?php echo FileSize::format(filesize($last_artifacts->build_dir() .'/'. $artifact)); ?>)</li>
		<?php } ?>
		</ul>
	<?php } ?>
		<h3>Statistics</h3>
		<ul>
			<li><b><?php echo l('plugins.nightly.x_builds', $stats['count']); ?></b></li>
			<li><b><?php echo round($stats['successful']); ?>%</b> <?php echo l('plugins.nightly.success_rate'); ?></li>
			<li><b><?php echo time_difference_in_words(time() - $stats['duration'], false); ?></b> <?php echo l('plugins.nightly.average_duration'); ?></li>
		</ul>
	</div>

	<table class="list">
		<thead>
			<th class="build_id" style="width: 50px;"></th>
			<th class="date" style="width: 65%"><?php echo l('plugins.nightly.build_date'); ?></th>
			<th class="duration" style="width: 15%"><?php echo l('plugins.nightly.build_duration'); ?></th>
			<th class="artifacts style="width: 10%""><?php echo l('plugins.nightly.artifacts'); ?></th>
		</thead>
		<tbody>
		<?php foreach ($builds as $build) { ?>
			<tr>
				<td><?php echo $build->bullet().' <span style="color: ' . ($build->status ? 'green' : 'red') . ';">#' . $build->build_id . '</span>'; ?></td>
				<td><?php echo HTML::link(Time::date("d-m-Y H:i:s", $build->built_at), $build->href()); ?></td>
				<td><?php echo time_difference_in_words(time() - $build->duration, false); ?></td>
				<td><?php echo count(explode(',', $build->artifacts)); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table> 