# KPN Herhaald Opnemen

## Licentie
Copyright (c) 2013, Bobbie Smulders

Contact: <mail@bsmulders.com>

License: GPLv3

## Project doel
Het is met KPN Interactieve Televisie in combinatie met het Opnemen pakket niet mogelijk om TV programma's herhaald op te nemen. In combinatie met het feit dat de EPG (TV-gids) slechts 3 dagen in de toekomst kan kijken zorgt dit voor ongemak met televisie series.

Het doel van dit project is het maken van een applicatie of script die met een bepaalde interval de EPG van itvonline.nl download, de gewenste TV-programma's opzoekt en een opname verzoek naar KPN stuurt.

## Project onderdelen
### API.md

Dit document bevat de API die itvonline.nl hanteert.

### database

Database (MySQL) voor het opslaan van opname verzoeken, kanaal namen en programma namen.

### server

PHP scripts voor het uitvoeren van opname verzoeken en het opzoeken en opslaan van kanaal namen en programma namen.

### client

HTML applicatie voor het benaderen van de database; om opname verzoeken uit te lezen en toe te voegen.

### proofofconcept.php

Een simpel PHP script dat inlogt op itvonline.nl, de EPG van de komende 24 uur download, op zoek gaat naar programma's genaamd "Sesamstraat" of "Lingo" en de resultaten vervolgens inprogrammeerd. Dit script is in het begin van het project gemaakt als basisimplementatie om aan te tonen dat geautomatiseerd opnemen mogelijk is.

## Gebruik
### database

Importeer het database.sql bestand naar een MySQL database.

### server

Plaats de bestanden op een (web)server. Pas config.php aan met de juiste configuratie parameters (database, inloggegevens van itvonline.nl, persoonlijke voorkeuren). Maak op de server een crontab aan die cron.php elke minuut aanroept (*/1 * * * *). Het script zorgt vervolgens zelf dat alles op het juiste moment wordt uitgevoerd. Mocht dit te veel load opleveren, is een crontab om het uur (0 * * * *) ook mogelijk. 

### client

Zet de HTML bestanden op een webserver. Installeer een REST interface ([Arrest MySQL](https://github.com/gilbitron/Arrest-MySQL)). Eventuele authenticatie kan met een .htaccess bestand worden gerealiseerd.

### proofofconcept.php
Pas de variabelen onder "//Gebruikersinstellingen" aan. Voer vervolgens het script uit. Dit kan met de command-line of op een webserver. De server of computer die het script uitvoert hoeft niet met het internet verbonden te zijn via een KPN internetverbinding.