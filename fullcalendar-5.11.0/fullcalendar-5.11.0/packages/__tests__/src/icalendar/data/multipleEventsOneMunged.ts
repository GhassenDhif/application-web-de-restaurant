export default `BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:events@fullcalendar.test
X-WR-TIMEZONE:Europe/Paris
BEGIN:VEVENT
DTEND;VALUE=DATE:20190413
DTSTAMP:20201006T124223Z
UID:1234578
CREATED:20190408T110429Z
DESCRIPTION:
LAST-MODIFIED:20190409T110738Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY:Munged conference (No DTSTART)
TRANSP:OPAQUE
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20190416
DTEND;VALUE=DATE:20190417
DTSTAMP:20201008T153019Z
UID:1234578
DTSTAMP:20201008T153019Z
DESCRIPTION:
LAST-MODIFIED:20190409T110738Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY:Valid conference
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR`
