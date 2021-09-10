<?php

declare(strict_types=1);

use Kayrunm\FluentRrule\Day;
use Kayrunm\FluentRrule\Month;
use Kayrunm\FluentRrule\Schedule;

require __DIR__ . '/vendor/autoload.php';

$utc = new DateTimeZone('UTC');

function example(string $name, string $expected, Schedule $actual): void
{
    echo "{$name}:\n";
    echo "EXPECTED: {$expected}\n";
    echo "ACTUAL:   {$actual}\n";

    if ($expected !== $actual->format()) {
        echo "UNEXPECTED!\n";
        exit(1);
    }
}

/*
Daily for 10 occurrences:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=DAILY;COUNT=10
 ==> (1997 9:00 AM EDT) September 2-11
*/

example(
    'Daily for 10 occurrences',
    'FREQ=DAILY;COUNT=10',
    Schedule::daily()->times(10),
);

/*
Daily until December 24, 1997:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=DAILY;UNTIL=19971224T000000Z
 ==> (1997 9:00 AM EDT) September 2-30;October 1-25
     (1997 9:00 AM EST) October 26-31;November 1-30;December 1-23
*/

example(
    'Daily until December 24, 1997',
    'FREQ=DAILY;UNTIL=19971224T000000Z',
    Schedule::daily()->until(new DateTimeImmutable('1997-12-24 00:00:00', $utc)),
);

/*
Every other day - forever:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=DAILY;INTERVAL=2
 ==> (1997 9:00 AM EDT) September 2,4,6,8...24,26,28,30;
                        October 2,4,6...20,22,24
     (1997 9:00 AM EST) October 26,28,30;
                        November 1,3,5,7...25,27,29;
                        December 1,3,...
*/

example(
    'Every other day - forever',
    'FREQ=DAILY;INTERVAL=2',
    Schedule::everyOtherDay(),
);

/*
Every 10 days, 5 occurrences:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=DAILY;INTERVAL=10;COUNT=5
 ==> (1997 9:00 AM EDT) September 2,12,22;
                        October 2,12
*/

example(
    'Every 10 days, 5 occurrences',
    'FREQ=DAILY;INTERVAL=10;COUNT=5',
    Schedule::everyNthDay(10)->times(5),
);

/*
Every day in January, for 3 years:

 DTSTART;TZID=America/New_York:19980101T090000
 RRULE:FREQ=YEARLY;UNTIL=20000131T140000Z;
  BYMONTH=1;BYDAY=SU,MO,TU,WE,TH,FR,SA
 or
 RRULE:FREQ=DAILY;UNTIL=20000131T140000Z;BYMONTH=1
 ==> (1998 9:00 AM EST)January 1-31
     (1999 9:00 AM EST)January 1-31
     (2000 9:00 AM EST)January 1-31
*/

example(
    'Every day in January, for 3 years',
    'FREQ=DAILY;UNTIL=20000131T140000Z;BYMONTH=1',
    Schedule::daily()
        ->onMonths(Month::JANUARY)
        ->until(new DateTimeImmutable('2000-01-31 14:00:00', $utc)),
);

/*
Weekly for 10 occurrences:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=WEEKLY;COUNT=10
 ==> (1997 9:00 AM EDT) September 2,9,16,23,30;October 7,14,21
     (1997 9:00 AM EST) October 28;November 4
*/

example(
    'Weekly for 10 occurrences',
    'FREQ=WEEKLY;COUNT=10',
    Schedule::weekly()->times(10),
);

/*
Weekly until December 24, 1997:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=WEEKLY;UNTIL=19971224T000000Z
 ==> (1997 9:00 AM EDT) September 2,9,16,23,30;
                        October 7,14,21
     (1997 9:00 AM EST) October 28;
                        November 4,11,18,25;
                        December 2,9,16,23
*/

example(
    'Weekly until December 24, 1997',
    'FREQ=WEEKLY;UNTIL=19971224T000000Z',
    Schedule::weekly()->until(new DateTimeImmutable('1997-12-24 00:00:00', $utc)),
);

/*
Every other week - forever:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=WEEKLY;INTERVAL=2;WKST=SU
 ==> (1997 9:00 AM EDT) September 2,16,30;
                        October 14
     (1997 9:00 AM EST) October 28;
                        November 11,25;
                        December 9,23
     (1998 9:00 AM EST) January 6,20;
                        February 3, 17
     ...
*/

example(
    'Every other week - forever',
    'FREQ=WEEKLY;INTERVAL=2;WKST=SU',
    Schedule::everyOtherWeek()->givenWeeksStartOn(Day::SUNDAY),
);

/*
Weekly on Tuesday and Thursday for five weeks:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=WEEKLY;UNTIL=19971007T000000Z;WKST=SU;BYDAY=TU,TH
 or
 RRULE:FREQ=WEEKLY;COUNT=10;WKST=SU;BYDAY=TU,TH
 ==> (1997 9:00 AM EDT) September 2,4,9,11,16,18,23,25,30;
                        October 2
*/

example(
    'Weekly on Tuesday and Thursday for five weeks',
    'FREQ=WEEKLY;COUNT=10;BYDAY=TU,TH;WKST=SU',
    Schedule::weekly()
        ->times(10)
        ->givenWeeksStartOn(Day::SUNDAY)
        ->onDays(Day::TUESDAY, Day::THURSDAY),
);

/*
Every other week on Monday, Wednesday, and Friday until December 24, 1997, starting on Monday, September 1, 1997:

 DTSTART;TZID=America/New_York:19970901T090000
 RRULE:FREQ=WEEKLY;INTERVAL=2;UNTIL=19971224T000000Z;WKST=SU;
  BYDAY=MO,WE,FR
 ==> (1997 9:00 AM EDT) September 1,3,5,15,17,19,29;
                        October 1,3,13,15,17
     (1997 9:00 AM EST) October 27,29,31;
                        November 10,12,14,24,26,28;
                        December 8,10,12,22
*/

example(
    'Every other week on Monday, Wednesday, and Friday until December 24, 1997',
    'FREQ=WEEKLY;INTERVAL=2;UNTIL=19971224T000000Z;BYDAY=MO,WE,FR;WKST=SU',
    Schedule::everyOtherWeek()
        ->until(new DateTimeImmutable('1997-12-24 00:00:00', $utc))
        ->givenWeeksStartOn(Day::SUNDAY)
        ->onDays(Day::MONDAY, Day::WEDNESDAY, Day::FRIDAY),
);

/*
Every other week on Tuesday and Thursday, for 8 occurrences:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=WEEKLY;INTERVAL=2;COUNT=8;WKST=SU;BYDAY=TU,TH
 ==> (1997 9:00 AM EDT) September 2,4,16,18,30;
                        October 2,14,16
*/

example(
    'Every other week on Tuesday and Thursday, for 8 occurrences',
    'FREQ=WEEKLY;INTERVAL=2;COUNT=8;BYDAY=TU,TH;WKST=SU',
    Schedule::everyOtherWeek()
        ->times(8)
        ->givenWeeksStartOn(Day::SUNDAY)
        ->onDays(Day::TUESDAY, Day::THURSDAY),
);

/*
Monthly on the first Friday for 10 occurrences:

 DTSTART;TZID=America/New_York:19970905T090000
 RRULE:FREQ=MONTHLY;COUNT=10;BYDAY=1FR
 ==> (1997 9:00 AM EDT) September 5;October 3
     (1997 9:00 AM EST) November 7;December 5
     (1998 9:00 AM EST) January 2;February 6;March 6;April 3
     (1998 9:00 AM EDT) May 1;June 5
*/

// TODO

/*
Monthly on the first Friday until December 24, 1997:

 DTSTART;TZID=America/New_York:19970905T090000
 RRULE:FREQ=MONTHLY;UNTIL=19971224T000000Z;BYDAY=1FR
 ==> (1997 9:00 AM EDT) September 5; October 3
     (1997 9:00 AM EST) November 7; December 5
*/

// TODO

/*
Every other month on the first and last Sunday of the month for 10 occurrences:

 DTSTART;TZID=America/New_York:19970907T090000
 RRULE:FREQ=MONTHLY;INTERVAL=2;COUNT=10;BYDAY=1SU,-1SU
 ==> (1997 9:00 AM EDT) September 7,28
     (1997 9:00 AM EST) November 2,30
     (1998 9:00 AM EST) January 4,25;March 1,29
     (1998 9:00 AM EDT) May 3,31
*/

// TODO

/*
Monthly on the second-to-last Monday of the month for 6 months:

 DTSTART;TZID=America/New_York:19970922T090000
 RRULE:FREQ=MONTHLY;COUNT=6;BYDAY=-2MO
 ==> (1997 9:00 AM EDT) September 22;October 20
     (1997 9:00 AM EST) November 17;December 22
     (1998 9:00 AM EST) January 19;February 16
*/

// TODO

/*
Monthly on the third-to-the-last day of the month, forever:

 DTSTART;TZID=America/New_York:19970928T090000
 RRULE:FREQ=MONTHLY;BYMONTHDAY=-3
 ==> (1997 9:00 AM EDT) September 28
     (1997 9:00 AM EST) October 29;November 28;December 29
     (1998 9:00 AM EST) January 29;February 26
     ...
*/

// TODO

/*
Monthly on the 2nd and 15th of the month for 10 occurrences:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=MONTHLY;COUNT=10;BYMONTHDAY=2,15
 ==> (1997 9:00 AM EDT) September 2,15;October 2,15
     (1997 9:00 AM EST) November 2,15;December 2,15
     (1998 9:00 AM EST) January 2,15
*/

example(
    'Monthly on the 2nd and 15th of the month for 10 occurrences',
    'FREQ=MONTHLY;COUNT=10;BYMONTHDAY=2,15',
    Schedule::monthly()->onMonthDays(2, 15)->times(10),
);

/*
Monthly on the first and last day of the month for 10 occurrences:

 DTSTART;TZID=America/New_York:19970930T090000
 RRULE:FREQ=MONTHLY;COUNT=10;BYMONTHDAY=1,-1
 ==> (1997 9:00 AM EDT) September 30;October 1
     (1997 9:00 AM EST) October 31;November 1,30;December 1,31
     (1998 9:00 AM EST) January 1,31;February 1
*/

// TODO

/*
Every 18 months on the 10th thru 15th of the month for 10 occurrences:

 DTSTART;TZID=America/New_York:19970910T090000
 RRULE:FREQ=MONTHLY;INTERVAL=18;COUNT=10;BYMONTHDAY=10,11,12,
  13,14,15
 ==> (1997 9:00 AM EDT) September 10,11,12,13,14,15
     (1999 9:00 AM EST) March 10,11,12,13
*/

example(
    'Every 18 months on the 10th thru 15th of the month for 10 occurrences',
    'FREQ=MONTHLY;INTERVAL=18;COUNT=10;BYMONTHDAY=10,11,12,13,14,15',
    Schedule::everyNthMonth(18)->onMonthDays(10, 11, 12, 13, 14, 15)->times(10),
);

/*
Every Tuesday, every other month:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=MONTHLY;INTERVAL=2;BYDAY=TU
 ==> (1997 9:00 AM EDT) September 2,9,16,23,30
     (1997 9:00 AM EST) November 4,11,18,25
     (1998 9:00 AM EST) January 6,13,20,27;March 3,10,17,24,31
     ...
*/

example(
    'Every Tuesday, every other month',
    'FREQ=MONTHLY;INTERVAL=2;BYDAY=TU',
    Schedule::everyOtherMonth()->onDays(Day::TUESDAY),
);

/*
Yearly in June and July for 10 occurrences:

 DTSTART;TZID=America/New_York:19970610T090000
 RRULE:FREQ=YEARLY;COUNT=10;BYMONTH=6,7
 ==> (1997 9:00 AM EDT) June 10;July 10
     (1998 9:00 AM EDT) June 10;July 10
     (1999 9:00 AM EDT) June 10;July 10
     (2000 9:00 AM EDT) June 10;July 10
     (2001 9:00 AM EDT) June 10;July 10
Note: Since none of the BYDAY, BYMONTHDAY, or BYYEARDAY components are specified, the day is gotten from "DTSTART".
*/

example(
    'Yearly in June and July for 10 occurrences',
    'FREQ=YEARLY;COUNT=10;BYMONTH=6,7',
    Schedule::yearly()->times(10)->onMonths(Month::JUNE, Month::JULY),
);

/*
Every other year on January, February, and March for 10 occurrences:

 DTSTART;TZID=America/New_York:19970310T090000
 RRULE:FREQ=YEARLY;INTERVAL=2;COUNT=10;BYMONTH=1,2,3
 ==> (1997 9:00 AM EST) March 10
     (1999 9:00 AM EST) January 10;February 10;March 10
     (2001 9:00 AM EST) January 10;February 10;March 10
     (2003 9:00 AM EST) January 10;February 10;March 10
*/

example(
    'Every other year on January, February, and March for 10 occurrences',
    'FREQ=YEARLY;INTERVAL=2;COUNT=10;BYMONTH=1,2,3',
    Schedule::everyOtherYear()->times(10)->onMonths(Month::JANUARY, Month::FEBRUARY, Month::MARCH),
);

/*
Every third year on the 1st, 100th, and 200th day for 10 occurrences:

 DTSTART;TZID=America/New_York:19970101T090000
 RRULE:FREQ=YEARLY;INTERVAL=3;COUNT=10;BYYEARDAY=1,100,200
 ==> (1997 9:00 AM EST) January 1
     (1997 9:00 AM EDT) April 10;July 19
     (2000 9:00 AM EST) January 1
     (2000 9:00 AM EDT) April 9;July 18
     (2003 9:00 AM EST) January 1
     (2003 9:00 AM EDT) April 10;July 19
     (2006 9:00 AM EST) January 1
*/

example(
    'Every third year on the 1st, 100th, and 200th day for 10 occurrences',
    'FREQ=YEARLY;INTERVAL=3;COUNT=10;BYYEARDAY=1,100,200',
    Schedule::everyNthYear(3)->times(10)->onYearDays(1, 100, 200),
);

/*
Every 20th Monday of the year, forever:

 DTSTART;TZID=America/New_York:19970519T090000
 RRULE:FREQ=YEARLY;BYDAY=20MO
 ==> (1997 9:00 AM EDT) May 19
     (1998 9:00 AM EDT) May 18
     (1999 9:00 AM EDT) May 17
     ...
*/

// TODO

/*
Monday of week number 20 (where the default start of the week is
Monday), forever:
 DTSTART;TZID=America/New_York:19970512T090000
 RRULE:FREQ=YEARLY;BYWEEKNO=20;BYDAY=MO
 ==> (1997 9:00 AM EDT) May 12
     (1998 9:00 AM EDT) May 11
     (1999 9:00 AM EDT) May 17
     ...
*/

// TODO

/*
Every Thursday in March, forever:

 DTSTART;TZID=America/New_York:19970313T090000
 RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=TH
 ==> (1997 9:00 AM EST) March 13,20,27
     (1998 9:00 AM EST) March 5,12,19,26
     (1999 9:00 AM EST) March 4,11,18,25
     ...
*/

example(
    'Every Thursday in March, forever',
    'FREQ=YEARLY;BYDAY=TH;BYMONTH=3',
    Schedule::yearly()->onMonths(Month::MARCH)->onDays(Day::THURSDAY),
);

/*
Every Thursday, but only during June, July, and August, forever:

 DTSTART;TZID=America/New_York:19970605T090000
 RRULE:FREQ=YEARLY;BYDAY=TH;BYMONTH=6,7,8
 ==> (1997 9:00 AM EDT) June 5,12,19,26;July 3,10,17,24,31;
                        August 7,14,21,28
     (1998 9:00 AM EDT) June 4,11,18,25;July 2,9,16,23,30;
                        August 6,13,20,27
     (1999 9:00 AM EDT) June 3,10,17,24;July 1,8,15,22,29;
                        August 5,12,19,26
     ...
*/

example(
    'Every Thursday, but only during June, July, and August, forever',
    'FREQ=YEARLY;BYDAY=TH;BYMONTH=6,7,8',
    Schedule::yearly()->onDays(Day::THURSDAY)->onMonths(Month::JUNE, Month::JULY, Month::AUGUST),
);

/*
Every Friday the 13th, forever:

 DTSTART;TZID=America/New_York:19970902T090000
 EXDATE;TZID=America/New_York:19970902T090000
 RRULE:FREQ=MONTHLY;BYDAY=FR;BYMONTHDAY=13
 ==> (1998 9:00 AM EST) February 13;March 13;November 13
     (1999 9:00 AM EDT) August 13
     (2000 9:00 AM EDT) October 13
     ...
*/

example(
    'Every Friday the 13th, forever',
    'FREQ=MONTHLY;BYDAY=FR;BYMONTHDAY=13',
    Schedule::monthly()->onDays(Day::FRIDAY)->onMonthDays(13),
);

/*
The first Saturday that follows the first Sunday of the month, forever:

 DTSTART;TZID=America/New_York:19970913T090000
 RRULE:FREQ=MONTHLY;BYDAY=SA;BYMONTHDAY=7,8,9,10,11,12,13
 ==> (1997 9:00 AM EDT) September 13;October 11
     (1997 9:00 AM EST) November 8;December 13
     (1998 9:00 AM EST) January 10;February 7;March 7
     (1998 9:00 AM EDT) April 11;May 9;June 13...
     ...
*/

example(
    'The first Saturday that follows the first Sunday of the month, forever',
    'FREQ=MONTHLY;BYDAY=SA;BYMONTHDAY=7,8,9,10,11,12,13',
    Schedule::monthly()->onDays(Day::SATURDAY)->onMonthDays(7, 8, 9, 10, 11, 12, 13),
);

/*
Every 4 years, the first Tuesday after a Monday in November, forever (U.S. Presidential Election day):

 DTSTART;TZID=America/New_York:19961105T090000
 RRULE:FREQ=YEARLY;INTERVAL=4;BYMONTH=11;BYDAY=TU;
  BYMONTHDAY=2,3,4,5,6,7,8
  ==> (1996 9:00 AM EST) November 5
      (2000 9:00 AM EST) November 7
      (2004 9:00 AM EST) November 2
      ...
*/

example(
    'Every 4 years, the first Tuesday after a Monday in November, forever (U.S. Presidential Election day)',
    'FREQ=YEARLY;INTERVAL=4;BYDAY=TU;BYMONTHDAY=2,3,4,5,6,7,8;BYMONTH=11',
    Schedule::everyNthYear(4)
        ->onDays(Day::TUESDAY)
        ->onMonths(Month::NOVEMBER)
        ->onMonthDays(2, 3, 4, 5, 6, 7, 8),
);

/*
The third instance into the month of one of Tuesday, Wednesday, or Thursday, for the next 3 months:

 DTSTART;TZID=America/New_York:19970904T090000
 RRULE:FREQ=MONTHLY;COUNT=3;BYDAY=TU,WE,TH;BYSETPOS=3
 ==> (1997 9:00 AM EDT) September 4;October 7
     (1997 9:00 AM EST) November 6
*/

// TODO

/*
The second-to-last weekday of the month:

 DTSTART;TZID=America/New_York:19970929T090000
 RRULE:FREQ=MONTHLY;BYDAY=MO,TU,WE,TH,FR;BYSETPOS=-2
 ==> (1997 9:00 AM EDT) September 29
     (1997 9:00 AM EST) October 30;November 27;December 30
     (1998 9:00 AM EST) January 29;February 26;March 30
     ...
*/

// TODO

/*
Every 3 hours from 9:00 AM to 5:00 PM on a specific day:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=HOURLY;INTERVAL=3;UNTIL=19970902T170000Z
 ==> (September 2, 1997 EDT) 09:00,12:00,15:00
*/

example(
    'Every 3 hours from 9:00 AM to 5:00 PM on a specific day',
    'FREQ=HOURLY;INTERVAL=3;UNTIL=19970902T170000Z',
    Schedule::everyNthHour(3)->until(new DateTimeImmutable('1997-09-02 17:00:00', $utc)),
);

/*
Every 15 minutes for 6 occurrences:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=MINUTELY;INTERVAL=15;COUNT=6
 ==> (September 2, 1997 EDT) 09:00,09:15,09:30,09:45,10:00,10:15
*/

example(
    'Every 15 minutes for 6 occurrences',
    'FREQ=MINUTELY;INTERVAL=15;COUNT=6',
    Schedule::everyNthMinute(15)->times(6),
);

/*
Every hour and a half for 4 occurrences:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=MINUTELY;INTERVAL=90;COUNT=4
 ==> (September 2, 1997 EDT) 09:00,10:30;12:00;13:30
*/

example(
    'Every hour and a half for 4 occurrences',
    'FREQ=MINUTELY;INTERVAL=90;COUNT=4',
    Schedule::everyNthMinute(90)->times(4),
);

/*
Every 20 minutes from 9:00 AM to 4:40 PM every day:

 DTSTART;TZID=America/New_York:19970902T090000
 RRULE:FREQ=DAILY;BYHOUR=9,10,11,12,13,14,15,16;BYMINUTE=0,20,40
 or
 RRULE:FREQ=MINUTELY;INTERVAL=20;BYHOUR=9,10,11,12,13,14,15,16
 ==> (September 2, 1997 EDT) 9:00,9:20,9:40,10:00,10:20,
                             ... 16:00,16:20,16:40
     (September 3, 1997 EDT) 9:00,9:20,9:40,10:00,10:20,
                             ...16:00,16:20,16:40
     ...
*/

example(
    'Every 20 minutes from 9:00 AM to 4:40 PM every day',
    'FREQ=MINUTELY;INTERVAL=20;BYHOUR=9,10,11,12,13,14,15,16',
    Schedule::everyNthMinute(20)->onHours(9, 10, 11, 12, 13, 14, 15, 16),
);

/*
An example where the days generated makes a difference because of WKST:

 DTSTART;TZID=America/New_York:19970805T090000
 RRULE:FREQ=WEEKLY;INTERVAL=2;COUNT=4;BYDAY=TU,SU;WKST=MO
 ==> (1997 EDT) August 5,10,19,24

*/

example(
    'An example where the days generated makes a difference because of WKST',
    'FREQ=WEEKLY;INTERVAL=2;COUNT=4;BYDAY=TU,SU;WKST=MO',
    Schedule::everyOtherWeek()
        ->times(4)
        ->onDays(Day::TUESDAY, Day::SUNDAY)
        ->givenWeeksStartOn(Day::MONDAY),
);

/*
changing only WKST from MO to SU, yields different results...

 DTSTART;TZID=America/New_York:19970805T090000
 RRULE:FREQ=WEEKLY;INTERVAL=2;COUNT=4;BYDAY=TU,SU;WKST=SU
 ==> (1997 EDT) August 5,17,19,31

*/

example(
    'changing only WKST from MO to SU, yields different results...',
    'FREQ=WEEKLY;INTERVAL=2;COUNT=4;BYDAY=TU,SU;WKST=SU',
    Schedule::everyOtherWeek()
        ->times(4)
        ->onDays(Day::TUESDAY, Day::SUNDAY)
        ->givenWeeksStartOn(Day::SUNDAY),
);

/*
An example where an invalid date (i.e., February 30) is ignored.
 DTSTART;TZID=America/New_York:20070115T090000
 RRULE:FREQ=MONTHLY;BYMONTHDAY=15,30;COUNT=5
 ==> (2007 EST) January 15,30
     (2007 EST) February 15
     (2007 EDT) March 15,30
 */

example(
    'An example where an invalid date (i.e., February 30) is ignored',
    'FREQ=MONTHLY;COUNT=5;BYMONTHDAY=15,30',
    Schedule::monthly()
        ->onMonthDays(15, 30)
        ->times(5),
);

echo "ALL GOOD!\n";
