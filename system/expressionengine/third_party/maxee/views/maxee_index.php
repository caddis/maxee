<?php if (isset($view_stats)) { ?>
<table cellpadding="0" cellspacing="0" class="mainTable">
	<thead>
		<tr>
			<th width="20%">Zone</th>
			<th width="20%">Cache hits</th>
			<th width="20%">Hits</th>
			<th width="20%">Non-cache hits</th>
			<th width="20%">Size</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($view_stats) === 0) { ?>
		<tr>
			<td colspan="5">No stats available.</td>
		</tr>
		<?php } else { ?>
		<?php foreach ($view_stats as $key => $val) { ?>
		<tr>
			<td><strong><?=$key ?></strong></td>
			<td><?=$val['cache_hits'] ?></td>
			<td><?=$val['hits'] ?></td>
			<td><?=$val['noncache_hits'] ?></td>
			<td><?=$val['size'] ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</tbody>
</table>

<?php } else { ?>

<p>You have missing or inavlid API credentials.</p>

<?php } ?>