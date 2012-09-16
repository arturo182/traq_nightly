	<div class="content">
		<h2 id="page_title">Builds</h2>

	<?php if($last_artifacts) { ?>
		<br>
		<h3>Last successful artifacts</h3>
		<ul>
		<?php foreach(explode(',', $last_artifacts->artifacts) as $artifact) { ?>
			<li><?php echo HTML::link($artifact, $last_artifacts->href('/artifact/' . $artifact)); ?> (<?php echo FileSize::format(filesize($last_artifacts->build_dir() .'/'. $artifact)); ?>)</li>
		<?php } ?>
		</ul>
	<?php } ?>
		<h3>Statistics</h3>
		<ul>
			<li><b><?php echo $stats['count']; ?></b> builds</li>
			<li><b><?php echo round($stats['successful']); ?>%</b> success rate</li>
			<li><b><?php echo time_difference_in_words(time() - $stats['duration'], false); ?></b> average duration</li>
		</ul>
	</div>

	<table class="list">
		<thead>
			<th class="build_id" style="width: 50px;"></th>
			<th class="date" style="width: 65%">Build date</th>
			<th class="duration" style="width: 15%">Build duration</th>
			<th class="artifacts style="width: 10%"">Artifacts</th>
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