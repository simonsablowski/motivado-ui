<?php

class UserInterfaceController extends Controller {
	protected $Api = NULL;
	protected $CoachingConfigurator = NULL;
	
	protected function getApiConfiguration() {
		foreach ($this->getConfiguration('includeDirectories') as $includeDirectory) {
			if (stripos($includeDirectory, 'api') !== FALSE) {
				require_once $includeDirectory . 'configuration.php';
				return $configuration;
			}
		}
	}
	
	protected function setupApi() {
		$this->setApi(new Api($this->getApiConfiguration()));
		$this->getApi()->setConfiguration('ignoreSession', TRUE);
		$this->getApi()->setSession($this->getSession());
	}
	
	protected function setupCoachingConfigurator() {
		$this->setCoachingConfigurator(new CoachingConfigurator);
		$this->getCoachingConfigurator()->setUserId(1);
	}
	
	protected function output($data) {
		print Json::format(Json::encode($data));
	}
	
	public function query($method) {
		$this->setupApi();
		$this->setupCoachingConfigurator();
		
		switch (strtolower($method)) {
			case 'query':
				return $this->getApi()->query($this->getRequest()->getData('CoachingKey'));
			case 'extendcoachinghistory':
				$this->getApi()->run(sprintf('Coaching/extendCoachingHistory/%s/%d', $this->getRequest()->getData('CoachingKey'), $this->getRequest()->getData('ObjectId')));
				return $this->output(array(
					'object' => array(
						'id' => $this->getRequest()->getData('ObjectId')
					)
				));
			case 'getinteractionresults':
				$results = array();
				foreach ($this->getCoachingConfigurator()->getValues() as $UserInteraction) {
					$results[$UserInteraction->getKey()] = array(
						'data' => $UserInteraction->getData('data'),
						'value' => $UserInteraction->getValue()
					);
				}
				return $this->output($results);
			case 'saveinteractionresults':
				$this->getCoachingConfigurator()->setValues(Json::decode($this->getRequest()->getData('data')));
				return $this->output(array(
					'object' => array(
						'id' => $this->getRequest()->getData('ObjectId')
					)
				));
		}
	}
}