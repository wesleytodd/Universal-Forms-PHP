<div class="form-field">
	<label for="<?= $field->name ?>"><?= $field->label ?></label>
	<textarea <?php foreach ($field->attributes as $key => $val) echo $key . '="' . $val . '" ' ?>><?= $field->value ?></textarea>
</div>
