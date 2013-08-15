# KPN Interactieve Televisie Online API

De base-URI van de API is:
http://www.itvonline.nl/AVS/besc

Alle requests worden beantwoord met een JSON response. De structuur van het response zal mogelijk later in dit document beschreven worden. Doordat de response in leesbaar Engels wordt opgestuurd is het echter niet heel lastig om dit zelf uit te zoeken. 

Bij alle requests kan met de channel parameter mee worden gegeven of het verzoek van itvonline.nl of van de mobiele app af komt ("PCTV" / "IPAD"). Dit heeft ondermeer gevolg op programma logo's, kleurgebruik en de verwijzingen naar mediastreams.

## API requests

### Login
Doel: Inloggen op itvonline.nl

Voorbeeld: *https://www.itvonline.nl/AVS/besc?action=Login&callback=jQuery1523961375789383374&channel=PCTV&username=XXX&password=0000&remember=N&_=1375789493*

**Parameters**

  * action: "Login"
  * channel: "PCTV"
  * username: Is het abonnementsnummer
  * password: Is de pincode
  * (optioneel) callback: Zal mee worden gegeven in de response
  * (optioneel) remember: "N", vermoedelijk of een cookie gezet moet worden
  * (optioneel) _: Timestamp op het moment van het verzoek

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
  * channel: "PCTV"
  * (optioneel) callback: Zal mee worden gegeven in de response
  * (optioneel) _: Timestamp op het moment van het verzoek
  * (optioneel) crmAccountId: Gebruikers ID

### CheckPin
Doel: Controleren van pincode

Voorbeeld: *https://www.itvonline.nl/AVS/besc?action=CheckPin&channel=PCTV&userPin=0000*

**Parameters**

  * channel: "PCTV"
  * userPin: Pincode van gebruiker

### KeepAlive
Doel: Aangeven dat de client nog steeds actief is

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=KeepAlive&channel=PCTV*

**Parameters**

  * action: "KeepAlive"
  * channel: "PCTV"

### Logout
Doel: Uitloggen

Voorbeeld: *https://www.itvonline.nl/AVS/besc?action=Logout&callback=jQuery1523961375789383374&channel=PCTV&_=1375789493*

**Parameters**

  * action: "Logout"
  * channel: "PCTV"
  * (optioneel) callback: Zal mee worden gegeven in de response
  * (optioneel) _: Timestamp op het moment van het verzoek

### GetLiveChannels
Doel: Ophalen van alle kanalen inclusief gedetaileerde informatie

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetLiveChannels&channel=PCTV&startTimeStamp=1375740000&endTimeStamp=1375826399*

**Parameters**

  * action: "GetLiveChannels"
  * channel: "PCTV" of "IPAD"
  * (optioneel) startTimeStamp: Timestamp voor begin van periode
  * (optioneel) endTimeStamp: Timestamp voor einde van periode

### GetEpg
Doel: Ophalen van de EPG

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetEpg&channel=PCTV&startTimeStamp=1375826400&endTimeStamp=1375912799&channelId=18%3B19%3B20%3B21%3B22%3B23%3B24%3B25%3B26%3B27%3B30%3B31%3B29%3B28%3B32*

**Parameters**

  * action: "GetEpg"
  * channel: "PCTV" of "IPAD"
  * startTimeStamp: Timestamp voor begin van periode
  * (optioneel) endTimeStamp: Timestamp voor einde van periode
  * (optioneel) channelId: ID('s) van de kanalen die gewenst zijn. Deze ID's zijn met GetLiveChannels op te vragen. ID's zijn gescheiden met ';'
  * (optioneel) maxResultsPerChannel: Maximaal aantal resultaten per kanaal
  * (optioneel) startChannel: Begin van reeks kanalen 
  * (optioneel) endChannel: Eind van reeks kanalen

### GetEpgMobile
Doel: Ophalen van de EPG, zonder programma omschrijvingen en pakket informatie (ter besparing van bandbreedte)

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetEpgMobile&channel=IPAD&endTimeStamp=1375826400&startTimeStamp=1375912799&channelId=18%3B19%3B20%3B21%3B22%3B23%3B24%3B25%3B26%3B27%3B30%3B31%3B29%3B28%3B32*

**Parameters**

  * action: "GetEpgMobile"
  * channel: "IPAD"
  * startTimeStamp: Timestamp voor begin van periode
  * (optioneel) endTimeStamp: Timestamp voor einde van periode
  * (optioneel) channelId: ID('s) van de kanalen die gewenst zijn. Deze ID's zijn met GetLiveChannels op te vragen. ID's zijn gescheiden met ';'. Zonder deze parameter worden alle kanalen getoond
  * (optioneel) maxResultsPerChannel: Maximaal aantal resultaten per kanaal
  * (optioneel) startChannel: Begin van reeks kanalen 
  * (optioneel) endChannel: Eind van reeks kanalen

### GetLiveInfo
Doel: Ophalen van programma informatie

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetLiveInfo&channel=PCTV&contentId=314520018*

**Parameters**

  * action: "GetLiveInfo"
  * channel: "PCTV" of "IPAD"
  * contentId: ID van het programma

### GetRecordingList
Doel: Ophalen van de lijst met ingeprogrameerde opnames

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetRecordingList&channel=PCTV&typeOfRecording=individual&stateOfRecording=ALL*

**Parameters**

  * action: "GetRecordingList"
  * channel: "PCTV" of "IPAD"
  * typeOfRecording: "all", "individual", "series", mogelijkheid tot filteren op opname-type (eenmalig of serie opname)
  * stateOfRecording: "ALL", "FINISHED", "SCHEDULED", "ONGOING", mogelijkheid tot filteren op de opname-status

### SetRecording
Doel: Inprogrammeren van een opname

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=SetRecording&channel=PCTV&externalChannelId=dtvchannels_15&programRefNr=0003604642&programStartTime=1375794000000&enableAutoDelete=N*

**Parameters**

  * action: "SetRecording"
  * channel: "PCTV" of "IPAD"
  * externalChannelId: ID van het kanaal. Kan gevonden worden in het EPG (externalChannelId).
  * programRefNr: ID van het op te nemen programma. Kan gevonden worden in het EPG (externalContentId).
  * programStartTime: Timestamp van het op te nemen programma in milliseconde. Kan gevonden worden in het EPG (startTime)
  * enableAutoDelete: "N", vermoedelijk of de opname verwijderd moet worden als er ruimtegebrek is

### DeleteRecordings
Doel: Opname verwijderen

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=DeleteRecordings&channel=PCTV&recordIDList=2736100&userStartTimeMarkList=0*

**Parameters**

  * action: "DeleteRecordings"
  * channel: "PCTV" of "IPAD"
  * recordIDList: ID van te verwijderen programma. Kan gevonden worden in de recording list (recordID). 
  * userStartTimeMarkList: Onbekende functionaliteit. Kan gevonden worden in de recording list (userStartTimeMarker).

De naamgeving van de laatste twee parameters doen vermoeden dat het mogelijk is om meerdere items gelijktijdig te verwijderen. Dit moet nog uitgezocht worden.

### SearchContents
Doel: Zoeken naar content op itvonline.nl

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=SearchContents&channel=PCTV&query=filmnaam*

**Parameters**

  * action: "SearchContents"
  * channel: "PCTV" of "IPAD"
  * query: Zoekterm
  * (optioneel) type: "CATCHUPTV", "VOD"
  * (optioneel) categoryId: Categorie ID zoals gehanteerd door "Videotheek"

### GetCatchUpTVChannelsList
Doel: Ophalen van alle "Programma Gemist" kanalen

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetCatchUpTVChannelsList&channel=PCTV&endTimeStamp=1375912799&startTimeStamp=1375048800*

**Parameters**

  * action: "GetCatchUpTVChannelsList"
  * channel: "PCTV" of "IPAD"
  * (optioneel) endTimeStamp: Timestamp voor begin van periode
  * (optioneel) startTimeStamp: Timestamp voor eind van periode

### GetCatchUpTV
Doel: Ophalen van alle "Programma Gemist" programma's

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetCatchUpTV&channel=PCTV&isHorizontal=Y&maxResults=30&startDate=1375048800&endDate=1375912799*

**Parameters**

  * action: "GetCatchUpTV"
  * channel: "PCTV" of "IPAD"
  * (optioneel) isHorizontal: "Y"
  * (optioneel) maxResults: Maximaal aantal resultaten
  * (optioneel) startDate: Timestamp voor begin van periode 
  * (optioneel) endDate: Timestamp voor eind van periode

### GetCatalogueTree
Doel: Ophalen van de catalogus van "Videotheek"

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetCatalogueTree&channel=PCTV&isHorizontal=Y&maxCategoryResults=30*

**Parameters**

  * action: "GetCatalogueTree"
  * channel: "PCTV" of "IPAD"
  * (optioneel) isHorizontal: "Y"
  * (optioneel) maxCategoryResult: Maximaal aantal resultaten

### GetSpecialContents
Doel: Ophalen van de specials van "Videotheek"

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetSpecialContents&channel=PCTV&isHorizontal=Y&maxCategoryResults=30*

**Parameters**

  * action: "GetSpecialContents"
  * channel: "PCTV" of "IPAD"
  * (optioneel) isHorizontal: "Y"
  * (optioneel) maxCategoryResults: Maximaal aantal resultaten

### GetContentList
Doel: Ophalen van de programma's van "Videotheek" gefilterd op categorie

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetContentList&channel=PCTV&catToRetrieve=1100*

**Parameters**

  * action: "GetContentList"
  * channel: "PCTV" of "IPAD"
  * catToRetrieve: Categorie ID. Kan gevonden worden met GetCatalogueTree

### GetRecommendedContents
Doel: Ophalen van "Videotheek" tips

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetRecommendedContents&channel=PCTV&isAnonymous=N*

**Parameters**

  * action: "GetRecommendedContents"
  * channel: "PCTV" of "IPAD"
  * (optioneel) isAnonymous: "Y" / "N", of de content wel of niet afgestemd moet zijn op de (ingelogde) gebruiker

### GetRentedMovies
Doel: Ophalen van gehuurde films van "Videotheek" 

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetRentedMovies&channel=PCTV*

**Parameters**

  * action: "GetRentedMovies"
  * channel: "PCTV" of "IPAD"

### CheckContentRights
Doel: Controleren of de gebruiker rechten heeft om het programma van "Videotheek" of "Live" te mogen zien

Voorbeeld: *https://www.itvonline.nl/AVS/besc?action=CheckContentRights&callback=jQuery155955637651632&channel=PCTV&type=VOD&contentId=426244&_=1376528755594*

**Parameters**

  * action: "CheckContentRights"
  * channel: "PCTV" of "IPAD"
  * type: "VOD", "LIVE"
  * contentId: ID van de content. Kan bij "VOD" gevonden worden met GetSpecialContents of GetContentList, bij "LIVE" is het het kanaal ID te vinden met GetLiveChannels
  * (optioneel) callback: Zal mee worden gegeven in de response
  * (optioneel) _: Timestamp op het moment van het verzoek	

## Nog uit te zoeken API requests

### GetBaseInfo
Doel: Vermoedelijk versiecontrole voor mobiele applicaties

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetBaseInfo&channel=IPAD&beVersion=BE1000*

**Parameters**

  * action: "GetBaseInfo"
  * channel: "IPAD"
  * beVersion: "BE1000"

### GetCDN
Doel: Vermoedelijk een link naar het content delivery network manifest

Voorbeeld: *http://www.itvonline.nl/AVS/besc?action=GetCDN&channel=PCTV&id=18&type=LIVE_PROMO*

**Parameters**

  * action: "GetCDN"
  * channel: "PCTV" of "IPAD"
  * id: ID van het kanaal. Kan gevonden worden met GetLiveChannels
  * type: "LIVE_PROMO", "LIVE", "TRAILER", onbekende functionaliteit

### StopContent
Doel: Vermoedelijke het stoppen van de mediaplayer

Voorbeeld: -

**Parameters**

  * action: "StopContent"
  * channel: "PCTV" of "IPAD"
  * type: "LIVE"
  * section: Onbekend
  * bookmark: Onbekend
  * deltaThreshold: Onbekend

### ContentPurchase
Doel: Vermoedelijk het aanschaffen van content

Voorbeeld: - 

**Parameters**

  * action: "ContentPurchase"
  * channel: "PCTV" of "IPAD"
  * contentId: 
  * rememberPin: "N"
  * PURCHASE_CC: "N"
  * securityPin: 

### GetContentDetail
Lijkt niet gebruikt te worden.
