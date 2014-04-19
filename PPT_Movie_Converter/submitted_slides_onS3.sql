#query to get all the ppt/pptx submitted files that are stored on S3
select uploaded_files, concat('http://inventit.s3.amazonaws.com/wp-content/uploads/', postmeta.meta_value) as S3FileStorage
from wp_postmeta as postmeta
    inner join 
    (
        select submit_time,
        max(if(`field_name`='uploaded_files_ids', `field_value`, null )) AS 'uploaded_files_ids',
        max(if(`field_name`='uploaded_files', `field_value`, null )) AS 'uploaded_files'
        from wp_cf7dbplugin_submits
        group by submit_time
    ) as submittedFiles on postmeta.post_id = submittedFiles.uploaded_files_ids
where postmeta.meta_key = '_wp_attached_file'
    and (postmeta.meta_value like '%ppt' or postmeta.meta_value like '%pptx')
    and  postmeta.meta_value  not like 'http://challenges.epals.com%'
order by uploaded_files_ids;
