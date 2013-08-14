# KPN Herhaald Opnemen

## Server documentatie

### ItvApi klasse (itvapi.php)

De ItvApi klasse is een wrapper voor de KPN Interactieve Televisie website. De volgende API calls worden ondersteund:

  * Login
  * GetProfile
  * Logout
  * GetLiveChannels
  * GetEpg
  * GetLiveInfo
  * GetRecordingList
  * SetRecording
  * DeleteRecordings
  * SearchContents

Inloggen wordt automatisch gedaan wanneer dit nodig is.

### Automator klasse (automator.php)
De Automator klasse voert de taken uit die nodig zijn voor de backend van dit project.

Er wordt in de EPG gekeken of er programma's in staan die overeenkomen met de opname verzoeken. Tevens wordt er een lijst met kanaal namen en een lijst met programma namen in de database opgeslagen (voor de autocomplete functionaliteit van de client).

### Configuratie (config.php)

De door de gebruiker in te stellen configuratie parameters (database, inloggegevens van itvonline.nl, persoonlijke voorkeuren).

### Cron (cron.php)

Dit script instantieert een ItvApi en een Automator object, om vervolgende Automator de benodigde taken uit te laten voeren.

Dit script hoort elke minuut door een crontab aangeroepen te worden.