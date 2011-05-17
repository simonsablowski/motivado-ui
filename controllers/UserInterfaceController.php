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
		$this->setApi(new Api($this->getApiConfiguration()));
		$this->getApi()->setSession($this->getSession());
	}
	
	protected function setupCoachingConfigurator() {
		$this->setCoachingConfigurator(new CoachingConfigurator);
		$this->getCoachingConfigurator()->UserId = 1;
	}
	
	protected function output($data) {
		print Json::format(Json::encode($data));
	}
	
	public function query($CoachingKey) {
		$this->setupApi();
		
		return $this->getApi()->query($CoachingKey);
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
				'data' => Json::decode($result['data']),
				'value' => Json::decode($result['value'])
			);
		}
		
		return $this->output($results);
	}
	
	public function saveInteractionResults($ObjectId) {
		$this->setupCoachingConfigurator();
		
		$data = (array)Json::decode($this->getRequest()->getData('data'));
		foreach ($data as $key => $result) {
			$result = (array)$result;
			$data[$key] = array(
				'data' => Json::encode($result['data']),
				'value' => Json::encode($result['value'])
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