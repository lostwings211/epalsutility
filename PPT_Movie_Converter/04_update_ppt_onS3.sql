#script to update the submitted ppt file stored on S3
update wp_postmeta
set meta_value = replace(meta_value, '.ppt', '.mov')
where post_id in 
    (
        select uploaded_files_ids
        from wp_postmeta_backup as postmeta
            inner join 
            (
                select submit_time,
                max(if(`field_name`='uploaded_files_ids', `field_value`, null )) AS 'uploaded_files_ids',
                max(if(`field_name`='uploaded_files', `field_value`, null )) AS 'uploaded_files'
                from wp_cf7dbplugin_submits
                group by submit_time
            ) as submittedFiles on postmeta.post_id = submittedFiles.uploaded_files_ids
        where postmeta.meta_key = '_wp_attached_file'
            and postmeta.meta_value like '%ppt' 
            and postmeta.meta_value not like 'http://challenges.epals.com%'
        order by uploaded_files_ids
    ) 
    and ((meta_key = '_wp_attached_file') or 
        (meta_key = 'amazonS3_info') or 
        (meta_key = '_wp_attachment_metadata'));
