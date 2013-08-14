<?php
/*

Dit bestand is onderdeel van KPN Herhaald Opnemen

Copyright (c) 2013, Bobbie Smulders

Contact: mail@bsmulders.com

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file. Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

*/

class ItvApi {

	private $username;
	private $password;
	private $curlHandle;
	private $loggedIn;
	
	private static $apiRoot = "http://www.itvonline.nl/AVS/besc";
	
	public function __construct($username = '', $password = '') {			
		if ($username && $password) {
			$this->setUsername($username);
			$this->setPassword($password);
		}
		
		$this->initCurlHandle();
		$this->setLoggedIn(false);
	}
	
	private function getUsername() {
		return $this->username;
	}
	
	public function setUsername($username) {
		$this->username = $username;
	}
	
	private function getPassword() {
		return $this->password;
	}
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	private function getCurlHandle() {
		return $this->curlHandle;
	}
	
	private function isLoggedIn() {		
		return $this->loggedIn;
	}
	
	private function setLoggedIn($state) {
		$this->loggedIn = $state;
	}
	
	public function login() {		
		if (!$this->isLoggedIn()) {			
			$response = $this->performGET(array(
				"action" => "Login",
				"channel" => "PCTV",
				"username" => $this->getUsername(),
				"password" => $this->getPassword(),
				"remember" => "N",
				"_" => time()));
		
			if (ItvApi::isResponseOK($response)) {
				$this->setLoggedIn(true);
			}
		} 
		
		return $this->isLoggedIn();
	}
	
	public function getProfile() {		
		if (!$this->login()) {
			return false;
		}
		
		$response = $this->performGET(array(
			"action" => "GetProfile",
			"channel" => "PCTV",
			"_" => time()));
							
		return ItvApi::isResponseOK($response) ? 
			$response->resultObj : false;
	}
	
	public function logout() {			
		if ($this->isLoggedIn()) {
			$response = $this->performGET(array(
				"action" => "Logout",
				"channel" => "PCTV",
				"_" => time()));
						
			if ($response != null && $response->resultCode == 'OK') {
				$this->setLoggedIn(false);
			} 
		} 
		
		return !$this->isLoggedIn();
	}
		
	public function getChannelList($startTimeStamp, $endTimeStamp) {		
		$response = $this->performGET(array(
			"action" => "GetLiveChannels",
			"channel" => "PCTV",
			"startTimeStamp" => $startTimeStamp,
			"endTimeStamp" => $endTimeStamp));
			
		return ItvApi::isResponseOK($response) ? 
			$response->resultObj->channelList : false;
	}
	
	public function getEPG($startTimeStamp, $endTimeStamp, $channelId) {
		$response = $this->performGET(array(
			"action" => "GetEpg",
			"channel" => "PCTV",
			"startTimeStamp" => $startTimeStamp,
			"endTimeStamp" => $endTimeStamp,
			"channelId" => is_array($channelId) ? 
				implode(";", $channelId) : $channelId));
				
		return ItvApi::isResponseOK($response) ? 
			$response->resultObj->channelList : false;
	}
	
	public function getLiveInfo($contentId) {
		$response = $this->performGET(array(
			"action" => "GetLiveInfo",
			"channel" => "PCTV",
			"contentId" => $contentId));
				
		return ItvApi::isResponseOK($response) ? 
			$response->resultObj : false;		
	}
	
	public function getRecordingList() {		
		if (!$this->login()) {
			return false;
		}

		$response = $this->performGET(array(
			"action" => "GetRecordingList",
			"channel" => "PCTV",
			"typeOfRecording" => "individual",
			"stateOfRecording" => "ALL"));
						
		return ItvApi::isResponseOK($response) ? 
			$response->resultObj : false;		
	}
	
	public function setRecording($externalChannelId, $programRefNr, $programStartTime, $enableAutoDelete = false) {			
		if (!$this->login()) {
			return false;
		}
	
		$response = $this->performGET(array(
			"action" => "SetRecording",
			"channel" => "PCTV",
			"externalChannelId" => $externalChannelId,
			"programRefNr" => $programRefNr,
			"programStartTime" => $programStartTime . "000",
			"enableAutoDelete" => $enableAutoDelete ? "Y" : "N"));
		
		return ItvApi::isResponseOK($response);
	}
	
	public function deleteRecordings($recordIDList, $userStartTimeMarker) {			
		if (!$this->login()) {
			return false;
		}
	
		$response = $this->performGET(array(
			"action" => "DeleteRecordings",
			"channel" => "PCTV",
			"recordIDList" => $recordIDList,
			"userStartTimeMarkList" => $userStartTimeMarker));
		
		return ItvApi::isResponseOK($response);		
	}
	
	public function searchContents($query) {
		$response = $this->performGET(array(
			"action" => "SearchContents",
			"channel" => "PCTV",
			"query" => $query));
						
		return ItvApi::isResponseOK($response) ? 
			$response->resultObj : false;		
	}
	
	private function initCurlHandle() {	
		$this->curlHandle = curl_init();
		curl_setopt($this->curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, "doesntexist.cookie");
	}
	
	private function performGET($parameters) {		
		curl_setopt($this->getCurlHandle(), 
			CURLOPT_URL, 
			ItvApi::$apiRoot . '?' . http_build_query($parameters));
		$data = curl_exec($this->getCurlHandle());	

		return json_decode($data);
	}
	
	public function close() {
		$this->logout();
		curl_close($this->getCurlHandle());
	}
	
	private static function isResponseOK($response) {
		return ($response != null && $response->resultCode == 'OK');
	}
}
?>