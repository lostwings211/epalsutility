SELECT COUNT(*) AS 'Total Community'
FROM users
WHERE reg_cobrand <= 999
	AND disabled <> 'true';
