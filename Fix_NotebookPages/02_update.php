<?php
/********************************************************
 * Those are the Environment Specific Settings
 ********************************************************/
$DB_Host = "localhost";
$DB_User = 'root';
$DB_Password = "123456";
$DB_Name = "wp_prod";

$query = "SELECT * FROM wp_posts WHERE ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key = 'ap_tool' AND meta_value = 'notebook')";
$pattern = "<ul class=\"odd fc lc\">\r\n  " .  
            "<li.*>notebook and pen.*</li>.*" . 
            "<li.*>video camera and tripod.*</li>.*" . 
            "<li.*>audio recorder.*</li>.*" . 
            "<li.*>digital camera.*</li>.*" . 
            "<li.*>smart phone.*</li>.*" . 
            "<li.*>spare batteries and/or battery charger.*</li>.*" . 
            "<li.*>extension cord.*</li>\r\n</ul>" ;
$replacement = "\r\n- notebook and pen<BR><BR>\r\n- video camera and tripod<BR><BR>\r\n- audio recorder<BR><BR>\r\n- digital camera<BR><BR>\r\n- smart phone<BR><BR>\r\n- spare batteries and/or battery charger<BR><BR>\r\n- extension cord<BR><BR>\r\n";

$link = mysql_connect($DB_Host, $DB_User, $DB_Password) or die("Could not connect: " . mysql_error() . "\n");
mysql_select_db($DB_Name) or die("Could not select database $DB_Name \n");

$result = mysql_query($query) or die('Query failed: ' . mysql_error() . "\n");
$count = 0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)){
    $PostID = $line['ID'];
    $post_content = $line['post_content'];
    
    
    $matched = ereg($pattern, $post_content);
    if($matched) {
        print ("PostID: $PostID");
        
        $post_content_new = ereg_replace($pattern, $replacement, $post_content);
        $update_sql = "UPDATE wp_posts SET post_content  = '" . $post_content_new . "' WHERE ID = $PostID";
       
        //mysql_query($update_sql) or die('Update Query failed: ' . mysql_error() . "\n");
        if(!mysql_query($update_sql)) {
            print("  Update Query Failed");
        }
        print("\n");
        $count++;
    }   
}
print ("Total Number of Problematic NotebookPage Posts: $count\n");

