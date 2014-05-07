select count(*) as 'GC Users' from users where date_added <= '2014-04-01 00:00:00' and reg_cobrand <=9999;

select count(*) as 'SM Legacy' from users where date_added <= '2014-04-01 00:00:00' and reg_cobrand > 9999;
