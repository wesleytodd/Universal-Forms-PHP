<form <?php foreach ($form->attributes as $key => $val) echo $key . '="' . $val . '" ' ?> data-form-definition='<?= $form ?>' >
	<?php foreach ($form as $field): ?>
		<?= $field->render() ?>
	<?php endforeach; ?>
	<button type="submit" class="btn">Submit</button>
</form>
