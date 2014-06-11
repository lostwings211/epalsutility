SET @cutoffdate = '2014-01-01';

SELECT COUNT(*) AS 'Actives'
FROM users
WHERE last_login > @cutoffdate
	AND reg_cobrand <= 9999
	AND disabled <> 'true';
