<?php
/*

Dit bestand is onderdeel van KPN Herhaald Opnemen

Copyright (c) 2013, Bobbie Smulders

Contact: mail@bsmulders.com

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file. Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

*/

require_once('itvapi.php');

class Automator {

	private $pdo;
	private $itvApi;
	private $startTimeStamp;
	private $endTimeStamp;
	
	private $epgCache;
	private $channelCache;
	
	public function __construct($pdo, $itvApi, $startTimeStamp, $endTimeStamp) {		
		$this->setPDO($pdo);
		$this->setItvApi($itvApi);
		$this->setStartTimeStamp($startTimeStamp);
		$this->setEndTimeStamp($endTimeStamp);
	}
	
	public function getItvApi() {
		return $this->itvApi;
	}
	
	public function setItvApi($itvApi) {
		$this->itvApi = $itvApi;
	}
	
	public function getPDO() {
		return $this->pdo;
	}
	
	public function setPDO($pdo) {
		$this->pdo = $pdo;
	}
	
	public function getStartTimeStamp() {
		return $this->startTimeStamp;
	}
	
	public function setStartTimeStamp($startTimeStamp) {
		$this->startTimeStamp = $startTimeStamp;
	}
	
	public function getEndTimeStamp() {
		return $this->endTimeStamp;
	}
	
	public function setEndTimeStamp($endTimeStamp) {
		$this->endTimeStamp = $endTimeStamp;
	}
	
	/**
	 * Voer de voor het programma benodigde taken uit.
	 */
	public function run() {	
		// Opname verzoeken die nooit uitgevoerd zijn direct uitvoeren
		$this->honorRequests($this->getNewRequests());
		
		// Opname verzoeken die meer dan een uur niet uitgevoerd zijn elk heel 
		// uur uitvoeren
		if (date("i") == "00" || true) {
			$this->honorRequests($this->getOverdueRequests());
		}
		
		// Kanaal namen en programma namen t.b.v. autocomplete worden  
		// dagelijks om 07:00 weggeschreven naar de database
		if (date("H:i") == "07:00") {
			$this->saveChannels();
			$this->savePrograms();
		}
	}
	
	/**
	 * Zoek in de TV-gids naar programma's die overeenkomen met de opname 
	 * verzoeken, en programmeer deze vervolgens in.
	 */
	private function honorRequests($requests) {	
		if (sizeof($requests) == 0 || sizeof($this->getEpg()) == 0) {
			return false;
		}
		
		foreach($requests as $request) {
			foreach ($this->getEpg() as $channel) {
				foreach ($channel->programList as $program) {
					if (Automator::requestMatch($program, $channel, $request)) {
						Automator::debugProgram($program);
						$this->getItvApi()->setRecording(
							$program->externalChannelId, 
							$program->externalContentId, 
							$program->startTime); 			
					}
				}
			}
		}
		
		$this->updateRequestLastScan($requests);
		
		return true;
	}
	
	/**
	 * Sla kanaal namen op in de database t.b.v. clientside autocomplete.
	 *
	 * Oude kanalen direct verwijderen.
	 */
	private function saveChannels() {	
		if (sizeof($this->getEpg()) == 0) {
			return false;
		}
	
		$sql = "TRUNCATE TABLE channels";
		$this->pdo->prepare($sql)->execute();
		
		foreach ($this->getEpg() as $channel) {
			$sql = "INSERT INTO channels (name) VALUES (:name)";
			$q = $this->getPDO()->prepare($sql);
			$q->execute(array(':name'=>$channel->channelName));		
		}
		
		return true;
	}
	
	/**
	 * Sla programma namen op in de database t.b.v. clientside autocomplete.
	 * 
	 * Oude programma's na 60 dagen verwijderen.
	 */
	private function savePrograms() {		
		if (sizeof($this->getEpg()) == 0) {
			return false;
		}
		
		foreach ($this->getEpg() as $channel) {
			foreach ($channel->programList as $program) {
				$sql = "INSERT INTO programs (name) VALUES (:name)";
				$q = $this->getPDO()->prepare($sql);
				$q->execute(array(':name'=>$program->title));

				$sql = "UPDATE programs SET lastupdate = NOW() WHERE name=:name";
				$q = $this->getPDO()->prepare($sql);
				$q->execute(array(':name'=>$program->title));
			}
		}
		
		$sql = "DELETE FROM programs 
			WHERE lastupdate - DATE_SUB(NOW(), INTERVAL 60 DAY) <= 0";
		$q = $this->getPDO()->prepare($sql)->execute();
		
		return true;
	}
	
	/**
	 * Geef het EPG van iTV. Resultaten worden gecached om de API te ontlasten.
	 */
	private function getEpg() {	
		if ($this->epgCache == null) {
			$channelList = $this->getChannelList();
			$channelIds = Automator::getChannelIds($channelList, "Extended");
			
			$this->epgCache = $this->getItvApi()->getEpg(
				$this->getStartTimeStamp(), 
				$this->getEndTimeStamp(), 
				$channelIds);
		}

		return $this->epgCache;
	}
	
	/**
	 * Geef de kanalenlijst van iTV. Resultaten worden gecached om de API te 
	 * ontlasten. 
	 */
	private function getChannelList() {	
		if ($this->channelCache == null) {
			$this->channelCache = $this->getItvApi()->getChannelList(
				$this->getStartTimeStamp(), 
				$this->getEndTimeStamp());
		}
		
		return $this->channelCache;
	}
	
	/**
	 * Geeft de opname verzoeken (requests) uit de database.
	 *
	 * Geeft enkel verzoeken die al meer dan een uur niet zijn opgevolgd.
	 */
	private function getOverdueRequests() {			
		$sql = "SELECT id, name, day, timeslot, exact, channel FROM requests 
		WHERE lastscan IS NOT NULL
		AND lastscan - DATE_SUB(NOW(), INTERVAL 55 MINUTE) <= 0";
		$q = $this->getPDO()->prepare($sql);
		$q->execute();
		$requests = $q->fetchAll();
		
		return $requests;
	}
	
	/**
	 * Geeft de opname verzoeken (requests) uit de database.
	 *
	 * Geeft enkel verzoeken die nog nooit zijn opgevolgd,
	 */
	private function getNewRequests() {			
		$sql = "SELECT id, name, day, timeslot, exact, channel FROM requests 
		WHERE lastscan IS NULL";
		$q = $this->getPDO()->prepare($sql);
		$q->execute();
		$requests = $q->fetchAll();
		
		return $requests;
	}
	
	/**
	 * Update het 'lastscan' veld voor meegegeven opname verzoeken.
	 */
	private function updateRequestLastScan($request) {		
		if (sizeof($request) == 0) {
			return false;
		}
		
		$requests = is_array($request) ? $request : array($request);

		foreach($requests as $request) {
			$sql = "UPDATE requests 
				SET lastscan = NOW() 
				WHERE id = :id";
			$q = $this->getPDO()->prepare($sql);
			$q->execute(array(':id'=>$request['id']));
		}
		
		return true;
	}

	public function close() {	
		$this->itvApi->close();
		$this->pdo = null;
	}
	
	/**
	 * Kijk of een opname verzoek overeenkomt met het kanaal en programma
	 */
	private static function requestMatch($program, $channel, $request) {	
		$programDay = strtolower(date('l', $program->startTime));
		$programHour = date('G', $program->startTime);
		$programTitle = strtolower($program->title);
		$requestTitle = strtolower($request['name']);

		$nameMatch = ($request['exact'] == '1' 
				&&  $requestTitle == $programTitle)
			|| ($request['exact'] == '0' 
				&& strpos($programTitle, $requestTitle) !== false);
		$dayMatch = $request['day'] == 'every' || $request['day'] == $programDay;
		$timeslotMatch = $request['timeslot'] == 'entire' 
			|| ($request['timeslot'] == 'morning' 
				&& $programHour >= 6 && $programHour <= 11)
			|| ($request['timeslot'] == 'afternoon' 
				&& $programHour >= 12 && $programHour <= 17)
			|| ($request['timeslot'] == 'evening' 
				&& $programHour >= 18 && $programHour <= 23)
			|| ($request['timeslot'] == 'night' 
				&& $programHour >= 0 && $programHour <= 5);
		$channelMatch = $request['channel'] == '' 
			|| $request['channel'] == $channel->channelName;

		return $nameMatch && $dayMatch && $timeslotMatch && $channelMatch;
	}
	
	private static function getChannelIds($channelList, $channelType = '') {
		if (sizeof($channelList) == 0) {
			return false;
		}
	
		$channelIds = array();		
		
		foreach ($channelList as $channel) {
			if ($channelType == '' || $channel->channelType == $channelType) {
				$channelIds[] = $channel->channelId;
			}
		}
		
		return $channelIds;
	}

	private static function debugProgram($program) {
		printf("Programma: %s (%s tot %s)\n%s\n",
			$program->title,
			strftime('%A %e %B %H:%M', $program->startTime),
			strftime('%A %e %B %H:%M', $program->endTime),
			$program->contentDescription);
	}
}

?>