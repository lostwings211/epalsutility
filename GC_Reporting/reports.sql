SET @cutoffdate = '2014-05-01';
SET @startarchivedate = '2014-04-01';

select count(*) as 'GC Users' from users where date_added <= @cutoffdate and reg_cobrand <=9999;

select (select count(*) from users where date_added <= @cutoffdate and reg_cobrand > 9999) + (select count(*) from archivedUsers where archiveTime > @startarchivedate) as 'SM Legacy';
