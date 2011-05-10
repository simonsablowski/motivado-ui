<?php

class CoachingConfigurator {
	public $UserId = 1;
	
	public function __construct() {
		
	}
	
	public function getValues() {
		$values = array();
		$q = mysql_query('SELECT * FROM `motivado_ui`.`userinteraction` WHERE `UserId` = ' . $this->UserId . ' ORDER BY `created` ASC');
		while ($r = mysql_fetch_array($q)) {
			$values[$r['key']] = array(
				'data' => $r['data'],
				'value' => $r['value']
			);	
		}
		return $values;
	}
	
	public function getValue($field = NULL) {
		if (is_null($field)) return $this->getValues();
		
		$q = mysql_query('SELECT * FROM `motivado_ui`.`userinteraction` WHERE `UserId` = ' . $this->UserId . ' AND `key` = \'' . $field . '\' ORDER BY `created` ASC LIMIT 1');
		$r = mysql_fetch_array($q);
		return $r ? $r['value'] : $r;
	}
	
	public function setValues($values) {
		foreach ((array)$values as $field => $value) {
			$this->setValue($field, (array)$value);
		}
		
		return $this->getValues();
	}
	
	public function setValue($field, $value = NULL) {
		if (is_null($value)) return $this->setValues($field);
		
		return mysql_query('INSERT INTO `motivado_ui`.`userinteraction` SET `UserId` = ' . $this->UserId . ', `key` = \'' . $field . '\', `data` = \'' . $value['data'] . '\', `value` = \'' . $value['value'] . '\'');
	}
}
