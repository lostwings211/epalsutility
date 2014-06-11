SET @cutoffdate = '2014-01-01';

SELECT Country, COUNT(*) AS 'Actives By Country'
FROM users
WHERE last_login > @cutoffdate
    AND reg_cobrand <= 9999
    AND disabled <> 'true'
	AND country <> ''
GROUP BY Country
ORDER BY COUNT(*) DESC
LIMIT 25;
