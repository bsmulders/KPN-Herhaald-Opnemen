<?php
/*

Dit bestand is onderdeel van KPN Herhaald Opnemen

Copyright (c) 2013, Bobbie Smulders

Contact: mail@bsmulders.com

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file. Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

*/

// Gebruikersinstellingen (abonnementsnummer, pincode en op te nemen programma's)
$username = "XXXX";
$password = "0000";
$showNames = array("Sesamstraat", "Lingo");

// Systeeminstellingen
$apiRoot = "http://www.itvonline.nl/AVS/besc";
$searchStart = time();
$searchEnd = $searchStart + (24*60*60);
setlocale(LC_TIME, 'nl_NL');

// Begin output
if (php_sapi_name() != "cli") {
	echo "<pre>";
}
printf("Zoeken naar de volgende programma's: %s\n\n", implode(", ", $showNames));

// CURL initialiseren
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, "doesntexist.cookie");

// Inloggen op ITV
$loginURI = sprintf("?action=Login&callback=root&channel=PCTV&username=%s&password=%s&remember=N&_=%s",
	$username,
	$password,
	time());
curl_setopt($ch, CURLOPT_URL, $apiRoot . $loginURI);
curl_exec($ch);

// Lijst met zenders ophalen
$channelsURI = sprintf("?action=GetLiveChannels&channel=PCTV&startTimeStamp=%s&endTimeStamp=%s",
	$searchStart,
	$searchEnd);
curl_setopt($ch, CURLOPT_URL, $apiRoot . $channelsURI);
$data = curl_exec($ch);

$channelsResponse = json_decode($data);
$channelList = $channelsResponse->resultObj->channelList;
$channelIdentifiers = array();

foreach ($channelList as $channel) {
	if ($channel->channelType == "Extended") {
		$channelIdentifiers[] = $channel->channelId;
	}
}

// EPG ophalen
$epgURI = sprintf("?action=GetEpg&channel=PCTV&startTimeStamp=%d&endTimeStamp=%d&channelId=%s",
	$searchStart,
	$searchEnd,
	implode("%3B", $channelIdentifiers));
curl_setopt($ch, CURLOPT_URL, $apiRoot . $epgURI);
$data = curl_exec($ch);

$epgResponse = json_decode($data);
$channelList = $epgResponse->resultObj->channelList;

// Alle kanalen in het EPG afgaan
foreach ($channelList as $channel) {
	printf("Zoeken op %s\n\n", $channel->channelName);

	$programs = $channel->programList;
	
	// Alle programma's van het kanaal afgaan
	foreach($programs as $program) {
		if (in_array($program->title, $showNames)) {
			// De naam van het programma komt overeen, stuur een opname verzoek naar KPN
			// TODO: programStartTime conversie naar milliseconde beter oplossen (int > long problematiek)
			printf("Gevonden: %s (%s tot %s)\n%s\n",
				$program->title,
				strftime('%A %e %B %H:%M', $program->startTime),
				strftime('%A %e %B %H:%M', $program->endTime),
				$program->contentDescription);
			
			$recordURI = sprintf("?action=SetRecording&channel=PCTV&externalChannelId=%s&programRefNr=%s&programStartTime=%s000&enableAutoDelete=N",
				$program->externalChannelId,
				$program->externalContentId,
				$program->startTime);
			curl_setopt($ch, CURLOPT_URL, $apiRoot . $recordURI);
			curl_exec($ch);
			
			printf("Opname is naar ITV gestuurd\n\n");
		}
	}
}

// Uitloggen
$logoffURI = sprintf("?action=Logout&callback=root&channel=PCTV&_=%d", time());
curl_setopt($ch, CURLOPT_URL, $apiRoot . $logoffURI );
curl_exec($ch);

// CURL afsluiten
curl_close($ch);

// Einde output
printf("Alle kanalen doorzocht");

if (php_sapi_name() != "cli") {
	echo "</pre>";
}
?>