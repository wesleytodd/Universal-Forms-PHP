<?php

namespace Wesleytodd\UniversalForms\Drivers\Laravel;

use Wesleytodd\UniversalForms\Core\Field as CoreField;

class Field extends CoreField {

	public $errors = array();

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

	public function valid($input = null) {
		if ($input !== null) {
			$this->value = $input;
		}
		$this->validator = Validator::make(array('value'=>$this->value), array('value'=>$this->getRules()));
		$this->setErrors($this->validator->messages()->all());
		return $this->validator->passes();
	}

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

	public function hasErrors() {
		return !empty($this->errors);
	}

	public function getErrors() {
		return $this->errors;
	}

}
