<?php
/********************************************************
 * Those are the Environment Specific Settings
 ********************************************************/
$dir_ppt = "/Users/zhilihua/PPT";
$dir_mov = "/Users/zhilihua/PPT_Movie";

// Help function to check if string starts with something or not
function startsWith($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

// Help Function to check if String ends with something or not
function endsWith($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function FileCount($dir, $type) {
    $i = 0; 
    $handle = opendir($dir);
    while (($file = readdir($handle)) !== false) {
        if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) {
            if($type === 'slides') {
                if((endsWith($file, ".ppt")) or (endsWith($file, ".pptx"))) {
                    $i++;
                }
            }
            else if($type === 'movie') {
                if (endsWith($file, ".mov")) {
                    $i++;
                }
            }
            else {
                $i++;
            }
        }
    }
    return $i;
}

// Validation Code to ensure the $dir_ppt and $dir_mov folder contains the same number of files
if(FileCount($dir_ppt, 'slides') !== FileCount($dir_mov, 'movie')) {
    echo ("The File Counts for PPT/MOV Directoreis do not match with each other, please take a look!\n");
    return;
}

$folder_ppt = opendir($dir_ppt); 
$index = -1;
while (false != ($file_ppt = readdir($folder_ppt))) {
    if((endsWith($file_ppt, ".ppt") or (endsWith($file_ppt, ".pptx"))) and (startsWith($file_ppt, ".") == FALSE)) {
        $index = $index + 1;
        $file_mov = '';
        if(endsWith($file_ppt, ".ppt")) {
           $file_mov = substr($file_ppt, 0, strlen($file_ppt) - 4) . ".mov"; 
        } 
        else {
            $file_mov = substr($file_ppt, 0, strlen($file_ppt) - 5) . ".mov"; 
        }
        
        $original_name = '';
        $modified_name = $dir_mov . "/" . $file_mov;
        if($index == 0) {
            $original_name = $dir_mov . "/Untitled.mov";
        }
        else {
            $original_name = $dir_mov . "/Untitled" . $index . ".mov";
        }
        
        $res = rename($original_name, $modified_name);
        if($res) {
            echo ("rename(\"$original_name\", \"$modified_name\") : Success\n");
        }
        else {
            echo ("rename(\"$original_name\", \"$modified_name\") : Failure\n");
        }
    }
}
?>
