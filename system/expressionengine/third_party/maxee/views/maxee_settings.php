<?=form_open($post_url, array('id' => 'gunneer_settings')) ?>

<table cellpadding="0" cellspacing="0" class="mainTable">
	<thead>
		<tr>
			<th scope="col" width="200">Setting</th>
			<th scope="col">Value</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<label for="alias">Alias</label>
			</td>
			<td>
				<?=form_input('alias', $settings->alias, 'id="alias"') ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="consumer_key">Consumer Key</label>
			</td>
			<td>
				<?=form_input('consumer_key', $settings->consumer_key, 'id="consumer_key"') ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="consumer_secret">Consumer Secret</label>
			</td>
			<td>
				<?=form_input('consumer_secret', $settings->consumer_secret, 'id="consumer_secret"') ?>
			</td>
		</tr>
	</tbody>
</table>
<input type="submit" class="submit" value="Submit">

<?=form_close() ?>