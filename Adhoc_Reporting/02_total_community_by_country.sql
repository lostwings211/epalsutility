SELECT Country, COUNT(*) AS 'Total Community By Country'
FROM users
WHERE reg_cobrand <= 999
    AND disabled <> 'true'
	AND country <> ''
GROUP BY Country
ORDER BY COUNT(*) DESC
LIMIT 25;
