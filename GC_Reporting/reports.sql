SET @cutoffdate = '2014-01-01 00:00:00';

select count(*) as 'GC Users' from users where date_added <= @cutoffdate and reg_cobrand <=9999;

select count(*) as 'SM Legacy' from users where date_added <= @cutoffdate and reg_cobrand > 9999;
