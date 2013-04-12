<div class="form-field">
	<label for="<?= $field->name ?>"><?= $field->label ?></label>
	<select <?php foreach ($field->attributes as $key => $val) echo $key . '="' . $val . '" ' ?>>
	<?php foreach ($field->options as $value => $text): ?>
		<option value="<?= $value ?>"<?= ($field->value == $value) ? 'selected' : ''; ?>><?= $text ?></option>
	<?php endforeach; ?>
	</select>
</div>
