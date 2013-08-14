# KPN Interactieve Televisie Online API

De base-URI van de API is:
http://www.itvonline.nl/AVS/besc

Alle requests worden beantwoord met een JSON response. De structuur van het response zal mogelijk later in dit document beschreven worden. Doordat de response in leesbaar Engels wordt opgestuurd is het echter niet heel lastig om dit zelf uit te zoeken. 

Bij alle requests kan met de channel parameter mee worden gegeven of het verzoek van itvonline.nl of van de mobiele app af komt ("PCTV" / "IPAD"). Het is nog onduidelijk wat hier de verdere gevolgen van zijn.

## API requests

### Login
Doel: Inloggen op itvonline.nl

Voorbeeld: *https://www.itvonline.nl/AVS/besc?action=Login&callback=jQuery1523961375789383374&channel=PCTV&username=XXX&password=0000&remember=N&_=1375789493*

**Parameters**

  * action: "Login"
  * callback: Zal mee worden gegeven in de response. Is optioneel
  * channel: "PCTV"
  * username: Is het abonnementsnummer
  * password: Is de pincode
  * remember: "N", vermoedelijk of een cookie gezet moet worden
  * _: Timestamp op het moment van het verzoek

### IpAuthentication
Doel: Automatisch inloggen op itvonline.nl (enkel vanaf een KPN IP adres).

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=IpAuthentication&channel=PCTV*

**Parameters**

  * action: "IpAuthentication"
  * channel: "PCTV"

### GetProfile
Doel: Geeft informatie over de ingelogde gebruiker, zoals de actieve TV pakketten, kinderslot, account type

Voorbeeld: *https://www.itvonline.nl/AVS/besc?action=GetProfile&callback=jQuery15339520517&channel=PCTV&_=1375868186435*

**Parameters**

  * action: GetProfile
  * callback: Zal mee worden gegeven in de response. Is optioneel
  * channel: "PCTV"
  * _: Timestamp op het moment van het verzoek

### KeepAlive
Doel: Een request om aan te geven dat de client nog steeds actief is

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=KeepAlive&channel=PCTV*

**Parameters**

  * action: "KeepAlive"
  * channel: "PCTV"

### Logout
Doel: Uitloggen

Voorbeeld: *https://www.itvonline.nl/AVS/besc?action=Logout&callback=jQuery1523961375789383374&channel=PCTV&_=1375789493*

**Parameters**

  * action: "Logout"
  * callback: Zal mee worden gegeven in de response. Is optioneel
  * channel: "PCTV"
  * _: Timestamp op het moment van het verzoek

### GetLiveChannels
Doel: Ophalen van alle kanalen inclusief gedetaileerde informatie

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetLiveChannels&channel=PCTV&startTimeStamp=1375740000&endTimeStamp=1375826399*

**Parameters**

  * action: "GetLiveChannels"
  * channel: "PCTV" of "IPAD"
  * startTimeStamp: Timestamp voor begin van periode
  * endTimeStamp: Timestamp voor einde van periode

### GetEpg
Doel: Ophalen van de EPG

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetEpg&channel=PCTV&startTimeStamp=1375826400&endTimeStamp=1375912799&channelId=18%3B19%3B20%3B21%3B22%3B23%3B24%3B25%3B26%3B27%3B30%3B31%3B29%3B28%3B32*

**Parameters**

  * action: "GetEpg"
  * channel: "PCTV" of "IPAD"
  * startTimeStamp: Timestamp voor begin van periode
  * endTimeStamp: Timestamp voor einde van periode
  * channelId: ID('s) van de kanalen die gewenst zijn. Deze ID's zijn met GetLiveChannels op te vragen. ID's zijn gescheiden met '%3B'. De parameter is optioneel, zonder deze parameter worden alle kanalen getoond

### GetEpgMobile
Doel: Ophalen van de EPG, zonder programma omschrijvingen en pakket informatie (ter besparing van bandbreedte)

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetEpgMobile&channel=IPAD&endTimeStamp=1375826400&startTimeStamp=1375912799&channelId=18%3B19%3B20%3B21%3B22%3B23%3B24%3B25%3B26%3B27%3B30%3B31%3B29%3B28%3B32*

**Parameters**

  * action: "GetEpgMobile"
  * channel: "IPAD"
  * startTimeStamp: Timestamp voor begin van periode
  * endTimeStamp: Timestamp voor einde van periode
  * channelId: ID('s) van de kanalen die gewenst zijn. Deze ID's zijn met GetLiveChannels op te vragen. ID's zijn gescheiden met '%3B'. De parameter is optioneel, zonder deze parameter worden alle kanalen getoond

### GetLiveInfo
Doel: Toon informatie over een programma

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetLiveInfo&channel=PCTV&contentId=314520018*

**Parameters**

  * action: "GetLiveInfo"
  * channel: "PCTV"
  * contentId: ID van het programma

### GetRecordingList
Doel: Ophalen van de lijst met ingeprogrameerde opnames

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetRecordingList&channel=PCTV&typeOfRecording=individual&stateOfRecording=ALL*

**Parameters**

  * action: "GetRecordingList"
  * channel: "PCTV" of "IPAD"
  * typeOfRecording: "individual", onbekende functionaliteit
  * stateOfRecording: "ALL", onbekende functionaliteit

### SetRecording
Doel: Inprogrammeren van een opname

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=SetRecording&channel=PCTV&externalChannelId=dtvchannels_15&programRefNr=0003604642&programStartTime=1375794000000&enableAutoDelete=N*

**Parameters**

  * action: "SetRecording"
  * channel: "PCTV"
  * externalChannelId: ID van het kanaal. Kan gevonden worden in het EPG (externalChannelId).
  * programRefNr: ID van het op te nemen programma. Kan gevonden worden in het EPG (externalContentId).
  * programStartTime: Timestamp van het op te nemen programma in milliseconde. Kan gevonden worden in het EPG (startTime)
  * enableAutoDelete: "N", vermoedelijk of de opname verwijderd moet worden als er ruimtegebrek is

### DeleteRecordings
Doel: Opname verwijderen

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=DeleteRecordings&channel=PCTV&recordIDList=2736100&userStartTimeMarkList=0*

**Parameters**

  * action: "DeleteRecordings"
  * channel: "PCTV"
  * recordIDList: ID van te verwijderen programma. Kan gevonden worden in de recording list (recordID). 
  * userStartTimeMarkList: Onbekende functionaliteit. Kan gevonden worden in de recording list (userStartTimeMarker).

De namen van de laatste twee parameters doen vermoeden dat het mogelijk is om meerdere items gelijktijdig te verwijderen. Dit moet nog uitgezocht worden.

### SearchContents
Doel: Zoeken naar content op itvonline.nl

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=SearchContents&channel=PCTV&query=filmnaam*

**Parameters**

  * action: "SearchContents"
  * channel: "PCTV"
  * query: Zoekterm

## Nog uit te zoeken API requests

### GetBaseInfo
Doel: Vermoedelijk versiecontrole voor mobiele applicaties

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetBaseInfo&channel=IPAD&beVersion=BE1000*

**Parameters**

  * action: "GetBaseInfo"
  * channel: "IPAD"
  * beVersion: "BE1000"

### GetCatalogueTree
Doel: Vermoedelijk voor Video On Demand

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetCatalogueTree&channel=PCTV&isHorizontal=Y&maxCategoryResults=30*

**Parameters**

  * action: "GetCatalogueTree"
  * channel: "PCTV"
  * isHorizontal: "Y"
  * maxCategoryResult: Maximaal aantal resultaten

### GetSpecialContents
Doel: Vermoedelijk de lijst met programma's van Video on Demand

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetSpecialContents&channel=PCTV&isHorizontal=Y&maxCategoryResults=30*

**Parameters**

  * action: "GetSpecialContents"
  * channel: "PCTV"
  * isHorizontal: "Y"
  * maxCategoryResults:

### GetCatchUpTV
Doel: Vermoedelijk de lijst met programma's van Uitzending Gemist

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetCatchUpTV&channel=PCTV&isHorizontal=Y&maxResults=30&startDate=1375048800&endDate=1375912799*

**Parameters**

  * action: "GetCatchUpTV"
  * channel: "PCTV"
  * isHorizontal: "Y"
  * maxResults:
  * startDate: 
  * endDate: 

### GetCatchUpTVChannelsList
Doel: Vermoedelijk de lijst met kanalen van Uitzending Gemist

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetCatchUpTVChannelsList&channel=PCTV&endTimeStamp=1375912799&startTimeStamp=1375048800*

**Parameters**
 
  * action: "GetCatchUpTVChannelsList"
  * channel: "PCTV"
  * endTimeStamp:
  * startTimeStamp: 

### GetCDN
Doel: Vermoedelijk een link naar het content delivery network manifest

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetCDN&channel=PCTV&id=18&type=LIVE_PROMO*

**Parameters**

  * action: "GetCDN"
  * channel: "PCTV"
  * id: Vermoedelijk het kanaal ID
  * type: "LIVE_PROMO", onbekende functionaliteit ("LIVE" en "TRAILER" zijn allicht ook mogelijk)

### GetRecommendedContents
Doel: Vermoedelijk voor het tabblad "Videotheek tips"

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetRecommendedContents&channel=PCTV&isAnonymous=N*

**Parameters**

  * action: "GetRecommendedContents"
  * channel: "PCTV"
  * isAnonymouse: "Y" / "N"

### GetContentList

### GetContentDetail

### CheckContentRights

### ContentPurchase

### Checkpin

### StopContent

### GetRentedMovies