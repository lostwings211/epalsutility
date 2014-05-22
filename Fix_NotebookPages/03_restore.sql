UPDATE wp_posts, wp_posts_backup
SET wp_posts.post_content = wp_posts_backup.post_content
WHERE wp_posts.ID = wp_posts_backup.ID;
