	<div class="content">
		<h2 id="page_title">Build #<?php echo $build->build_id; ?> (<?php echo Time::date("d-m-Y H:i:s", $build->built_at); ?>)</h2>
	<?php if($prev_build || $next_build) { ?>
		<?php if($prev_build) { ?>
			<?php echo HTML::link('< Previous build', $prev_build->href()); ?>
		<?php } ?>
		<?php if($prev_build && $next_build) echo ' | '; ?>
		<?php if($next_build) { ?>
			<?php echo HTML::link('Next build >', $next_build->href()); ?>
		<?php } ?>
		<br>
	<?php } ?>	
		<br>	
		<b>Completed</b> <?php echo time_ago($build->built_at); ?><br>
		<b>Duration</b> <?php echo time_difference_in_words(time() - $build->duration, false); ?><br>
	<?php if(strlen($build->artifacts) > 0) { ?>
		<br>
		<h3>Artifacts</h3>
		<?php foreach(explode(',', $build->artifacts) as $artifact) { ?>
			<?php if(file_exists($build->build_dir() .'/'. $artifact)) { ?>
				<li><?php echo HTML::link($artifact, $build->href('/artifact/' . $artifact)); ?> (<?php echo FileSize::format(filesize($build->build_dir() .'/'. $artifact)); ?>)</li>
			<?php } ?>
		<?php } ?>
	<?php } ?>
		<br>
		<h3>Console output</h3>
		<?php
			$lines = preg_split('/\r\n|\r|\n/', $build->console);
			if(count($lines) > 50) {
				echo 'Showing last 50 lines, ' . HTML::link('full output', $build->href('/output.txt')) . ' (' . FileSize::format(strlen($build->console)) . ').';
			}
			echo '<div class="box"><code>' . (count($lines) > 50 ? '(...)<br>' : '') . nl2br(implode(PHP_EOL, array_slice($lines, -50, 50))) . '</code></div>';
		?>
	</div>	
