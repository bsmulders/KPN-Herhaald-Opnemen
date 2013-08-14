# KPN Herhaald Opnemen

## Database documentatie

### channels

Kanaal namen, voor de autocomplete functionaliteit in de client

### programs

Programma namen, voor de autocomplete functionaliteit in de client. De "lastupdate" kolom wordt gebruikt om oude programma's te herkennen en te verwijderen.

### requests

Opname verzoeken. Bevat de volgende kolommen:

 *  name: Naam van het programma
 *  day: Dag van de week waarop gezocht moet worden (enum: every, monday, tuesday, wednesday, thursday, friday, saturday, sunday)
 *  timeslot: Tijd van de dag waarop gezocht moet worden (enum: entire, morning, afternoon, evening, night)
 *  exact: Of de naam van het programma exact overeen moet komen of slechst gedeeltelijk (boolean)
 *  channel: Kanaal waarop gezocht moet worden (NULL voor zoeken op alle kanalen)
 *  lastscan: Wanneer het verzoek voor het laatst opgevolgd is