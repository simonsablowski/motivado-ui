<?php

class CoachingConfigurator extends Application {
	protected $UserId;
	
	public function __construct() {
		
	}
	
	public function getValues() {
		return UserInteraction::findAll(array(
			'UserId' => $this->getUserId()
		));
	}
	
	public function getValue($field = NULL) {
		if (is_null($field)) return $this->getValues();
		
		$UserInteraction = UserInteraction::findFirst(array(
			'UserId' => $this->getUserId(),
			'key' => $field
		));
		return $UserInteraction->getValue();
	}
	
	public function setValues($values) {
		foreach ((array)$values as $field => $value) {
			$this->setValue($field, (array)$value);
		}
		
		return $this->getValues();
	}
	
	public function setValue($field, $value = NULL) {
		if (is_null($value)) return $this->setValues($field);
		
		$UserInteraction = new UserInteraction(array(
			'UserId' => $this->getUserId(),
			'key' => $field,
			'data' => (string)$value['data'],
			'value' => (string)$value['value']
		));
		return $UserInteraction->create();
	}
}