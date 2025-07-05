<?php
require_once("actionControllerBase.php");
class TestController extends actionControllerBase {
	public function savecacheAction() {
$start = microtime(true);
		if(!($testData = $this->cache->load("testdata"))) {
			echo "Saving to cache with timeout 5 secs";
			$this->cache->save(array("testdata"=>"Wow!!!"), "testdata", array("testdata"), 3600);
echo microtime(true)-$start;
			exit();
		}
		echo "Data loaded from cache: <pre>".print_r($testData, true);
	}

	public function clearcacheAction() {
		$this->cache->remove("testdata");
		echo "Data Clear";
	}
}
