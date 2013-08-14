<?php
/*

Dit bestand is onderdeel van KPN Herhaald Opnemen

Copyright (c) 2013, Bobbie Smulders

Contact: mail@bsmulders.com

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file. Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

*/

require_once('automator.php');
require_once('itvapi.php');
require_once('config.php');

$pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', 
	$databaseConfig['server'], 
	$databaseConfig['database']),
	$databaseConfig['username'], 
	$databaseConfig['password'], 
	array( PDO::ATTR_PERSISTENT => false));
$itvApi = new ItvApi($itvConfig['username'], $itvConfig['password']);
$automator = new Automator($pdo, $itvApi, 
	time(), strtotime($itvConfig['epgDays']));
$automator->run();
$automator->close();
?>