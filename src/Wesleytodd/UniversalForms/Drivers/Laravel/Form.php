<?php

namespace Wesleytodd\UniversalForms\Drivers\Laravel;

use \Config;
use \Validator;
use Exception;
use Wesleytodd\UniversalForms\Core\Form as CoreForm;

class Form extends CoreForm {

	public $file;

	/**
	 * Constructor
	 */
	public function __construct($form = null, $input = null) {

		if ($form != null) {
			if (is_string($form)) {
				if (json_decode($form) == null) {
					$this->setFilePath($form);
					if ($this->file !== null && file_exists($this->file)) {
						$form = file_get_contents($this->file);
					} else {
						$form = null;
					}
				}
			} else if (is_object($form) || is_array($form)) {
				$form = json_encode($form);
			}
		}

		parent::__construct($form, $input);

	}

	/**
	 * Add a field to the form
	 *
	 * @param string|Field $name the field name or an instance of a field
	 * @param string $type the field type
	 * @param string|array $field the field declaration
	 * @param arrat $opts an array of options
	 */
	public function addField($name, $type = '', $field = array()) {
		if ($name instanceof Field) {
			$this->fields[$name->name] = $name;
			return $this;
		}

		$this->fields[$name] = new Field($name, $type, $field);
		return $this;
	}

	public function setFilePath($file, $ext = null) {
		if ($ext === null) {
			 $ext = Config::get('UniversalForms::ext');
		}
		$this->file = realpath(App('path') . '/' . Config::get('UniversalForms::path')) . '/' . $file . '.' . $ext;
		return $this->file;
	}

	public function valid($input = array(), $format = ':message') {
		if (!empty($input)) {
			$this->populate($input);
		}
		$this->validator = Validator::make($this->getValues(), $this->getRules());

		$valid = $this->validator->passes();

		if (!$valid) {
			$messages = $this->validator->messages();
			foreach ($this as $field) {
				if ($messages->has($field->name)) {
					$field->setErrors($messages->get($field->name, $format));
				}
			}
		}

		return $valid;
	}

	public function getErrors() {
		$errors = array();
		foreach ($this as $field) {
			if ($field->hasErrors()) {
				$errors[$field->name] = $field->getErrors();
			}
		}
		return $errors;
	}

}
