<div class="form-field">
	<label for="<?= $field->name ?>"><?= $field->label ?></label>
	<input <?php foreach ($field->attributes as $key => $val) echo $key . '="' . $val . '" ' ?>/>
</div>
