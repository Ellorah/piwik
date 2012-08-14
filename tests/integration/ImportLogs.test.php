<?php
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once dirname(__FILE__)."/../../tests/config_test.php";
}

require_once PIWIK_INCLUDE_PATH . '/tests/integration/Integration.php';

/**
 * Tests the log importer.
 */
class Test_Piwik_Integration_ImportLogs extends Test_Integration_Facade
{
	protected $dateTime = '2010-03-06 11:22:33';
	protected $idSite = null;
	protected $idGoal = null;
	
	public function getApiToTest()
	{
		return array(
			array('all', array('idSite' => $this->idSite, 'date' => '2012-08-09', 'periods' => 'month')),
		);
	}

	public function getControllerActionsToTest()
	{
		return array();
	}
	
	public function getOutputPrefix()
	{
		return 'ImportLogs';
	}
	
	public function setUp()
	{
		parent::setUp();
		$this->idSite = $this->createWebsite($this->dateTime);
		
		// for conversion testing
		$this->idGoal = Piwik_Goals_API::getInstance()->addGoal($this->idSite, 'all', 'url', 'http', 'contains', false, 5);
	}
	
	/**
	 * Logs a couple visits for Aug 9, Aug 10, Aug 11 of 2012.
	 */
	protected function trackVisits()
	{
		$cmd = "python "
			 . PIWIK_INCLUDE_PATH.'/misc/log-analytics/import_logs.py ' # script loc
			 . '--url="'.$this->getRootUrl().'" '
			 . '--tracker-url="'.$this->getTrackerUrl().'" '
			 . '--idsite='.$this->idSite.' '
			 . '--recorders=4 '
			 . '--enable-http-errors '
			 . '--enable-http-redirects '
			 . '--enable-static '
			 . '--enable-bots '
			 . PIWIK_INCLUDE_PATH.'/tests/resources/fake_logs.log ' # log file
			 . '2>&1'
			 ;
		
		exec($cmd, $output, $result);
		if ($result !== 0)
		{
			echo "<pre>command: $cmd\nresult: $result\noutput: ".implode("\n", $output)."</pre>";
			throw new Exception("log importer failed");
		}
	}
}