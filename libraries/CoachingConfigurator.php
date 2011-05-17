<?php

class CoachingConfigurator {
	public $UserId;
	
	public function getValues() {
		if ($sessionValues = $this->retrieveValuesFromSession()) {
			return $sessionValues;
		} else {
			return $this->saveValuesToSession($this->retrieveValuesFromDatabase());
		}
	}
	
	protected function retrieveValuesFromSession() {
		if (isset($_SESSION['CoachingConfiguratorValues'])) {
			return $_SESSION['CoachingConfiguratorValues'];
		}
	}
	
	protected function retrieveValuesFromDatabase() {
		$values = array();
		$query = sprintf('SELECT * FROM `motivado_ui`.`userinteraction` WHERE `UserId` = %d ORDER BY `created` ASC', mysql_real_escape_string($this->UserId));
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result)) {
			$values[$row['key']] = array(
				'data' => $row['data'],
				'value' => $row['value']
			);	
		}
		return $values;
	}
	
	public function getValue($field = NULL) {
		if (is_null($field)) {
			return $this->getValues();
		}
		
		if ($sessionValue = $this->retrieveValueFromSession($field)) {
			return $sessionValue;
		} else {
			return $this->saveValueToSession($field, $this->retrieveValueFromDatabase($field));
		}
	}
	
	protected function retrieveValueFromSession($field) {
		if (isset($_SESSION['CoachingConfiguratorValues'][$field])) {
			return $_SESSION['CoachingConfiguratorValues'][$field];
		}
	}
	
	protected function retrieveValueFromDatabase($field) {
		$query = sprintf('SELECT * FROM `motivado_ui`.`userinteraction` WHERE `UserId` = %d AND `key` = \'%s\' ORDER BY `created` ASC LIMIT 1', mysql_real_escape_string($this->UserId), mysql_real_escape_string($field));
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		if (isset($row['value'])) {
			return $row['value'];
		}
	}
	
	protected function mustSaveToDatabase() {
		return !is_null($this->UserId);
	}
	
	public function setValues($values = NULL) {
		if (is_null($values)) {
			$values = $this->getValues();
		}
		
		if (($sessionValues = $this->saveValuesToSession($values)) && $this->mustSaveToDatabase()) {
			$this->saveValuesToDatabase($values);
		}
		
		return $this->getValues();
	}
	
	protected function saveValuesToSession($values) {
		if (!isset($_SESSION['CoachingConfiguratorValues'])) {
			return $_SESSION['CoachingConfiguratorValues'] = $values;
		} else {
			return $_SESSION['CoachingConfiguratorValues'] = array_merge($_SESSION['CoachingConfiguratorValues'], $values);
		}
	}
	
	protected function saveValuesToDatabase($values) {
		$query = 'INSERT INTO `motivado_ui`.`userinteraction` (`UserId`, `key`, `data`, `value`, `created`) VALUES ';
		$comma = FALSE;
		foreach ($values as $field => $value) {
			$query .= $comma ? ', ' : '';
			$query .= sprintf('(%d, \'%s\', \'%s\', \'%s\', NOW())',
				mysql_real_escape_string($this->UserId),
				mysql_real_escape_string($field),
				mysql_real_escape_string($value['data']),
				mysql_real_escape_string($value['value'])
			);
			$comma = TRUE;
		}
		if ($comma) {
			return mysql_query($query);
		}
	}
	
	public function setValue($field, $value = NULL) {
		if (is_null($value)) {
			return $this->setValues($field);
		}
		
		if (($sessionValue = $this->saveValueToSession($field)) && !$this->mustSaveToDatabase()) {
			return $sessionValue;
		} else {
			return $this->saveValueToDatabase($field);
		}
	}
	
	protected function saveValueToSession($field, $value) {
		if (!isset($_SESSION['CoachingConfiguratorValues'])) {
			$_SESSION['CoachingConfiguratorValues'] = array();
		}
		
		return $_SESSION['CoachingConfiguratorValues'][$field] = $value;
	}
	
	protected function saveValueToDatabase($field) {
		$query = sprintf('INSERT INTO `motivado_ui`.`userinteraction` (`UserId`, `key`, `data`, `value`, `created`) VALUES (%d, \'%s\', \'%s\', \'%s\', NOW())',
			mysql_real_escape_string($this->UserId),
			mysql_real_escape_string($field),
			mysql_real_escape_string($value['data']),
			mysql_real_escape_string($value['value'])
		);
		return mysql_query($query);
	}
}