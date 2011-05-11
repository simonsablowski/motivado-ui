<?php

class CoachingConfigurator {
	public $UserId;
	
	public function getValues() {
		$values = array();
		$q = mysql_query('SELECT * FROM `motivado_ui`.`userinteraction` WHERE `UserId` = ' . mysql_real_escape_string($this->UserId) . ' ORDER BY `created` ASC');
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
		
		$q = mysql_query('SELECT * FROM `motivado_ui`.`userinteraction` WHERE `UserId` = ' . mysql_real_escape_string($this->UserId) . ' AND `key` = \'' . mysql_real_escape_string($field) . '\' ORDER BY `created` ASC LIMIT 1');
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
		
		return mysql_query('INSERT INTO `motivado_ui`.`userinteraction` SET `UserId` = ' . mysql_real_escape_string($this->UserId) . ', `key` = \'' . mysql_real_escape_string($field) . '\', `data` = \'' . mysql_real_escape_string($value['data']) . '\', `value` = \'' . mysql_real_escape_string($value['value']) . '\'');
	}
}
