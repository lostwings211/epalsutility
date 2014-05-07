<?php
/********************************************************
 * Those are the Environment Specific Settings
 ********************************************************/
$DB_Host = "inventit-prod-db01.c0f2oekk3smp.us-east-1.rds.amazonaws.com";
$DB_User = 'root';
$DB_Password = "letmein123";
$DB_Name = "wordpress";

$link = mysql_connect($DB_Host, $DB_User, $DB_Password) or die("Could not connect: " . mysql_error());
mysql_select_db($DB_Name) or die("Could not select database");

$query = "select submit_time,
                max(if(`field_name`='uploaded_files_ids', `field_value`, null )) AS 'uploaded_files_ids',
                max(if(`field_name`='uploaded_files', `field_value`, null )) AS 'uploaded_files'
            from wp_cf7dbplugin_submits
            group by submit_time";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());


$count = 0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)){
    foreach($line as $col_value) {
        echo "$col_value\t";
    }
    $count++;
    echo "\n";
}


echo "Retrieved $count record(s)\n";
?>
