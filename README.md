# KPN Herhaald Opnemen

## Licentie
Copyright (c) 2013, Bobbie Smulders

Contact: <mail@bsmulders.com>

License: GPLv3

## Project doel
Het is met KPN Interactieve Televisie in combinatie met het Opnemen pakket niet mogelijk om TV programma's herhaald op te nemen. In combinatie met het feit dat de EPG (TV-gids) slechts 3 dagen in de toekomst kan kijken zorgt dit voor ongemak met televisie series.

Het doel van dit project is het maken van een applicatie of script die met een bepaalde interval de EPG van itvonline.nl download, de gewenste TV-programma's opzoekt en een opname verzoek naar KPN stuurt.

Om het gebruiksgemak te verhogen is een gebruiksvriendelijke GUI gewenst. 

## Project onderdelen
### API.md

Dit document bevat de API die itvonline.nl hanteert.

### database

Database (MySQL) voor het opslaan van opname verzoeken, kanaal namen en programma namen.

### client

HTML applicatie voor het benaderen van de database; om opname verzoeken uit te lezen en toe te voegen.

### proofofconcept.php

Een simpel PHP script dat inlogt op itvonline.nl, de EPG van de komende 24 uur download, op zoek gaat naar programma's genaamd "Sesamstraat" of "Lingo" en de resultaten vervolgens inprogrammeerd. Dit script is enkel gemaakt als basisimplementatie om aan te tonen dat geautomatiseerd opnemen mogelijk is. Het is mogelijk dit script in productie te gebruiken of er een applicatie op verder te bouwen, het wordt echter niet aangeraden.

## Gebruik
### proofofconcept.php
Pas de variabelen onder "//Gebruikersinstellingen" aan. Voer vervolgens het script uit. Dit kan met de command-line of op een webserver. De server of computer die het script uitvoert hoeft niet met het internet verbonden te zijn via een KPN internetverbinding.

Door het script via een cronjob met enige regelmaat uit te voeren (minimaal eens per 24 uur), kan een hele simpele automatische inprogrameerhulp worden gerealiseerd. Het herhaaldelijk inprogrammeren van hetzelfde programma lijkt geen problemen te geven. Evenals het inprogrammeren van programma's op zenders die niet toegankelijk zijn.

Tip: Een NAS, Raspberry PI of webhostingpakket zijn hier uitstekend voor geschikt, aangezien de taak geen rekenkracht kost maar het wel noodzakelijk is dat de server permanent aan staat.