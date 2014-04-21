#script to update the submitted pptx file stored on S3

#1 To update the '_wp_attached_file' field of wp_postmeta
update wp_postmeta
set meta_value = concat('http://challenges.epals.com/wp-content/uploads/', replace(meta_value, '.pptx', '.mp4'))
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
            and postmeta.meta_value like '%pptx' 
            and postmeta.meta_value not like 'http://challenges.epals.com%' 
        order by uploaded_files_ids
    ) 
    and meta_key = '_wp_attached_file';

#2 Delete the 'amazonS3_info', '_wp_attachment_metadata' key values from wp_postmeta table
delete from wp_postmeta
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
            and postmeta.meta_value like '%pptx' 
            and postmeta.meta_value not like 'http://challenges.epals.com%'           
        order by uploaded_files_ids
    )
    and ((meta_key = 'amazonS3_info') or (meta_key = '_wp_attachment_metadata')); 

