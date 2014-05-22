UPDATE wp_posts
	SET post_content = REPLACE(post_content,  '%<ul class=\"odd fc lc\">\r\n  <li%>notebook and pen%</li>%<li%>video camera and tripod%</li>%<li%>audio recorder%</li>%<li%>digital camera%</li>%<li%>smart phone%</li>%<li%>spare batteries and/or battery charger%</li>%<li%>extension cord%</li>%</ul>%', '- notebook and pen<br><BR> - video camera and tripod<br><BR> - audio recorder<br><BR>- digital camera<br><BR>- smart phone<br><BR>- spare batteries and/or battery charger<br><BR>- extension cord<br><BR>')
	WHERE ID IN (SELECT ID FROM wp_posts_backup);
