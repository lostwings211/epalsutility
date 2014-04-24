# pptx, pptm, ppt
set @extension = 'ppt';

#1 Update "submission_type" of the wp_postmeta
update wp_postmeta
set meta_value = 'Video'
where post_ID in 
(
    select posts.ID as postID
    from wp_posts as posts
        inner join wp_posts as subposts on posts.ID = subposts.post_parent
        inner join wp_postmeta_backup as postmeta on postmeta.post_id = posts.ID
        inner join wp_postmeta_backup as subpostmeta on subpostmeta.post_id = subposts.ID
    where posts.post_type = 'challenge_entry'
        and postmeta.meta_key = 'submission_type'
        and postmeta.meta_value = 'Presentation'
        and subpostmeta.meta_key = '_wp_attached_file'
        and subpostmeta.meta_value  like concat ('%.', @extension)
)
    and meta_key = 'submission_type';

#2 Update "_wp_attached_file" of the wp_postmeta
update wp_postmeta
set meta_value = replace(meta_value, concat('.', @extension), '.mp4')
where post_ID in 
(
    select subposts.ID 
    from wp_posts as posts
        inner join wp_posts as subposts on posts.ID = subposts.post_parent
        inner join wp_postmeta_backup as postmeta on postmeta.post_id = posts.ID
        inner join wp_postmeta_backup as subpostmeta on subpostmeta.post_id = subposts.ID
    where posts.post_type = 'challenge_entry'
        and postmeta.meta_key = 'submission_type'
        and postmeta.meta_value = 'Presentation'
        and subpostmeta.meta_key = '_wp_attached_file'
        and subpostmeta.meta_value like concat ('%.', @extension)
)
    and meta_key = '_wp_attached_file';

#3 Delete the 'amazonS3_info', '_wp_attachment_metadata' key values from wp_postmeta table
delete from wp_postmeta
where post_ID in 
(
    select subposts.ID 
    from wp_posts as posts
        inner join wp_posts as subposts on posts.ID = subposts.post_parent
        inner join wp_postmeta_backup as postmeta on postmeta.post_id = posts.ID
        inner join wp_postmeta_backup as subpostmeta on subpostmeta.post_id = subposts.ID
    where posts.post_type = 'challenge_entry'
        and postmeta.meta_key = 'submission_type'
        and postmeta.meta_value = 'Presentation'
        and subpostmeta.meta_key = '_wp_attached_file'
        and subpostmeta.meta_value like concat ('%.', @extension)
)
    and ((meta_key = 'amazonS3_info') or (meta_key = '_wp_attachment_metadata'));
