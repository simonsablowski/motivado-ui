<?php

class UserInterfaceController extends Controller {
	protected $Api = NULL;
	protected $CoachingConfigurator = NULL;
	
	protected function getApiConfiguration() {
		foreach ($this->getConfiguration('includeDirectories') as $includeDirectory) {
			if (stripos($includeDirectory, 'api') !== FALSE) {
				require_once $includeDirectory . 'configuration.php';
				return array_merge($configuration, array(
					'ignoreSession' => TRUE
				));
			}
		}
	}
	
	protected function setupApi() {
		$this->setApi(new \Motivado\Api\Api($this->getApiConfiguration()));
		$this->getApi()->setSession($this->getSession());
	}
	
	protected function setupCoachingConfigurator() {
		$this->setCoachingConfigurator(new CoachingConfigurator);
		$this->getCoachingConfigurator()->UserId = 1;
	}
	
	protected function output($data) {
		print \Motivado\Api\Json::format(Json::encode($data));
	}
	
	public function query($CoachingKey, $initial = TRUE) {
		$this->setupApi();
		
		return $this->getApi()->query($CoachingKey, (bool)$initial);
	}
	
	public function extendCoachingHistory($CoachingKey, $ObjectId) {
		$this->setupApi();
		
		$this->getApi()->extendCoachingHistory($CoachingKey, $ObjectId);
		
		return $this->output(array(
			'object' => array(
				'id' => $ObjectId
			)
		));
	}
	
	public function getInteractionResults() {
		$this->setupCoachingConfigurator();
		
		$results = array();
		foreach ($this->getCoachingConfigurator()->getValues() as $key => $result) {
			$results[$key] = array(
				'data' => \Motivado\Api\Json::decode($result['data']),
				'value' => \Motivado\Api\Json::decode($result['value'])
			);
		}
		
		return $this->output($results);
	}
	
	public function saveInteractionResults($ObjectId) {
		$this->setupCoachingConfigurator();
		
		$data = (array)\Motivado\Api\Json::decode($this->getRequest()->getData('data'));
		foreach ($data as $key => $result) {
			$result = (array)$result;
			$data[$key] = array(
				'data' => \Motivado\Api\Json::encode($result['data']),
				'value' => \Motivado\Api\Json::encode($result['value'])
			);
		}
		$this->getCoachingConfigurator()->setValues($data);
		
		return $this->output(array(
			'object' => array(
				'id' => $ObjectId
			)
		));
	}
}