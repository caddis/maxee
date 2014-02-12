<?php if (isset($zones)) { ?>

<?php if (count($zones) === 0) { ?>

<p>No zones available.</p>

<?php } else { ?>

<?=form_open($post_url, array('id' => 'maxee_settings')) ?>

<table cellpadding="0" cellspacing="0" class="mainTable">
	<tbody>
		<tr>
			<td width="250">
				<label for="zone">MaxCDN Zone</label>
				<small style="display: block">Select the zone you want to work with.</small>
			</td>
			<td>
				<select name="zone" id="zone">
					<?php foreach ($zones as $zone) { ?>
					<option value="<?=$zone->id ?>"><?=$zone->name ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="files">Files</label>
				<small style="display: block">Enter specific files to clear (optional).</small>
				<small style="display: block">Separate multiple files with a "|".</small>
			</td>
			<td>
				<?=form_input('files', null, 'id="files" style="max-width: 350px"') ?>
			</td>
		</tr>
	</tbody>
</table>
<input type="submit" class="submit" value="Purge">

<?php } ?>

<?=form_close() ?>

<?php } else { ?>

<p>You have missing or inavlid API credentials.</p>

<?php } ?>