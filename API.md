# KPN Interactieve Televisie Online API

## API requests
De base-URI van de API is:
http://www.itvonline.nl/AVS/besc

De tot nu toe uitgezochte functionaliteit is hieronder te vinden. Parameters gemarkeerd met een (?) zijn parameters waarvan de functionaliteit niet bekend is.

Alle requests worden beantwoord met een JSON response. De structuur van het response zal mogelijk later in dit document beschreven worden. Doordat de reponse is leesbaar Engels wordt opgestuurd is het echter niet heel lastig om dit zelf uit te zoeken. 

### Login
Doel: Inloggen op itvonline.nl

Voorbeeld: _https://www.itvonline.nl/AVS/besc?action=Login&callback=jQuery1523961375789383374&channel=PCTV&username=XXX&password=0000&remember=N&_=1375789493_

**Parameters**

  * action: "Login"
  * callback: Zal mee worden gegeven in de response
  * channel: "PCTV" (?)
  * username: Is het abonnementsnummer
  * password: Is de pincode
  * remember: "N" (?) vermoedelijk of een cookie gezet moet worden
  * _: Timestamp op het moment van het verzoek

### GetLiveChannels
Doel: Ophalen van alle kanalen inclusief gedetaileerde informatie

Voorbeeld: _https://www.itvonline.nl/AVS/besc?action=GetLiveChannels&channel=PCTV&startTimeStamp=1375740000&endTimeStamp=1375826399_

**Parameters**

  * action: "GetLiveChannels"
  * channel: "PCTV" (?)
  * startTimeStamp: Timestamp voor begin van zoekperiode
  * endTimeStamp: Timestamp voor einde van zoekperiode

### GetEpg
Doel: Ophalen van de EPG

Voorbeeld: _http://www.itvonline.nl/AVS/besc?action=GetEpg&channel=PCTV&startTimeStamp=1375826400&endTimeStamp=1375912799&channelId=18%3B19%3B20%3B21%3B22%3B23%3B24%3B25%3B26%3B27%3B30%3B31%3B29%3B28%3B32_

**Parameters**

  * action: "GetEpg"
  * channel: "PCTV" (?)
  * startTimeStamp: Timestamp voor begin van zoekperiode
  * endTimeStamp: Timestamp voor einde van zoekperiode
  * channelId: ID('s) van de kanalen die gewenst zijn. Deze ID's zijn met GetLiveChannels op te vragen. ID's zijn gescheiden met '%3B'.

### GetRecordingList
Doel: Ophalen van de lijst met ingeprogrameerde opnames

Voorbeeld: _http://www.itvonline.nl/AVS/besc?action=GetRecordingList&channel=PCTV&typeOfRecording=individual&stateOfRecording=ALL_

**Parameters**

  * action: "GetRecordingList"
  * channel: "PCTV" (?)
  * typeOfRecording: "individual" (?)
  * stateOfRecording: "ALL" (?)

### SetRecording
Doel: Inprogrammeren van een opname

Voorbeeld: _http://www.itvonline.nl/AVS/besc?action=SetRecording&channel=PCTV&externalChannelId=dtvchannels_15&programRefNr=0003604642&programStartTime=1375794000000&enableAutoDelete=N_

**Parameters**

  * action: "SetRecording"
  * channel: "PCTV" (?)
  * externalChannelId: ID van het kanaal. Let op: Is anders dan het ID gebruikt in de request van GetEpg. Dit ID is terug te vinden in het individueele programma in het EPG. Zie de proof of concept voor een voorbeeld.
  * programRefNr: ID van het op te nemen programma. Kan gevonden worden in het EPG.
  * programStartTime: Timestamp van het op te nemen programma in milliseconde
  * enableAutoDelete: "N" (?)

### Logout
Doel: Uitloggen (niet verplicht, wel zo netjes)

Voorbeeld: _http://www.itvonline.nl/AVS/besc?action=Logout&callback=jQuery1523961375789383374&channel=PCTV&_=1375789493_

**Parameters**

  * action: "Logout"
  * callback: Zal mee worden gegeven in de response
  * channel: "PCTV" (?)
  * _: Timestamp op het moment van het verzoek