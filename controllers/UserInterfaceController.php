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
	
	public function query($method) {
		$this->setupCoachingConfigurator();
		$this->setupApi();
		
		$CoachingKey = $this->getRequest()->getData('CoachingKey');
		$ObjectId = $this->getRequest()->getData('ObjectId');
		$data = $this->getRequest()->getData('data');
		
		switch (strtolower($method)) {
			case 'query':
				return $this->getApi()->query($CoachingKey);
			case 'extendcoachinghistory':
				$this->getApi()->run(sprintf('Coaching/extendCoachingHistory/%s/%d', $CoachingKey, $ObjectId));
				return $this->output(array(
					'object' => array(
						'id' => $ObjectId
					)
				));
			case 'getinteractionresults':
				$results = array();
				foreach ($this->getCoachingConfigurator()->getValues() as $key => $result) {
					$results[$key] = array(
						'data' => Json::decode($result['data']),
						'value' => Json::decode($result['value'])
					);
				}
				return $this->output($results);
			case 'saveinteractionresults':
				$data = (array)Json::decode($data);
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
}
