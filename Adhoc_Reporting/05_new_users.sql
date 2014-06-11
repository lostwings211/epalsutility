SET @cutoffdate = '2014-01-01';

SELECT COUNT(*) AS 'New Users'
FROM users
WHERE reg_cobrand <= 9999
	AND disabled <> 'true'
	AND date_added > @cutoffdate;
