<?php
/*

Dit bestand is onderdeel van KPN Herhaald Opnemen

Copyright (c) 2013, Bobbie Smulders

Contact: mail@bsmulders.com

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file. Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

*/

$databaseConfig = array(
	'server'   => 'localhost',
	'database' => 'itv',
	'username' => 'xxxxxxxx',	
	'password' => 'xxxxxxxx');

$itvConfig = array(
	'username' => 'xxxxxxxxxxx',	// Abonnementsnummer
	'password' => '0000',			// Pincode
	'epgDays' => '+3 days');		// Aantal dagen dat de EPG (meestal) gevuld 
									// is, in formaat van de "strtotime" functie
?>