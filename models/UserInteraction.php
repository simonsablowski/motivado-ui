<?php

class UserInteraction extends Model {
	protected $fields = array(
		'id',
		'UserId',
		'key',
		'data',
		'value',
		'created',
		'modified'
	);
	protected $requiredFields = array(
		'UserId',
		'key',
		'data',
		'value',
	);
	protected $hiddenFields = array(
		'id',
		'UserId',
	);
	protected static $defaultCondition = array();
}