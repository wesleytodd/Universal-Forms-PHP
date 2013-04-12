<?php namespace Wesleytodd\UniversalForms\Drivers\Laravel;

use \View;
use Wesleytodd\UniversalForms\Core\Field as CoreField;

class Field extends CoreField {

	/**
	 * An array of the field errors
	 *
	 * @var array
	 */
	public $errors = array();

	/**
	 * Get the array of rules in Laravel format
	 *
	 * @return array The array of rules for the field
	 */
	public function getRules() {
		$rules = array();
		$base_rules = parent::getRules();

		foreach ($base_rules as $rule) {
			preg_match('/([a-z]+)(?:\[(.*)\])?/', $rule, $matches);
			$ruleName = $matches[1];
			$ruleArgs = isset($matches[2]) ? explode(',', $matches[2]) : array();
			switch ($matches[1]) {
				case 'length':
					if ($ruleArgs[0] !== '' && $ruleArgs[1] !== '') {
						$rules[] = 'between:' + $ruleArgs[0] . ',' . $ruleArgs[1];
					} else if ($ruleArgs[0] !== '' && $ruleArgs[1] === '') {
						$rules[] = 'min:' . $ruleArgs[0];
					} else if ($ruleArgs[0] === '' && $ruleArgs[1] !== '') {
						$rules[] = 'max:' . $ruleArgs[1];
					}
					break;
				default:
					$rules[] = $rule;
			}
		}

		return $rules;
	}

	/**
	 * Validate the field
	 *
	 * @param mixed $input The form input to validate
	 * @return bool True if it is valid
	 */
	public function valid($input = null) {
		if ($input !== null) {
			$this->value = $input;
		}
		$this->validator = Validator::make(array('value'=>$this->value), array('value'=>$this->getRules()));
		$this->setErrors($this->validator->messages()->all());
		return $this->validator->passes();
	}

	/**
	 * Set error messages
	 *
	 * @param mixed $error An array of errors or a single error message
	 * @return Field $this
	 */
	public function setErrors($error, $message = null) {
		if (is_array($error)) {
			foreach ($error as $key => $msg) {
				$this->setErrors($key, $msg);
			}
		} else {
			$this->errors[] = $message;
		}
		return $this;
	}

	/**
	 * Does the field have errors
	 *
	 * @return bool True if errors exist
	 */
	public function hasErrors() {
		return !empty($this->errors);
	}

	/**
	 * Get the errors
	 *
	 * @return array The array of errors
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Render the field
	 *
	 * @param View $fieldView A Laravel view to render the field with
	 * @return View The view to render
	 */
	public function render(View $fieldView = null) {
		if ($fieldView !== null) {
			return $fieldView->with('field', $this);
		}

		return View::make('UniversalForms::fields.' . $this->type, array(
			'field' => $this
		));
	}

}
