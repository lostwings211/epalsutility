<?php
    
    /********************************************************************************************************
     * You have to provide with the filename and the Project ID as the parameter arguments
     * 1) The file contains usernames (without @neupals.com suffix), with the first one being the teacher
     * 2) The Project ID is the Project's corresponding Telligent GroupID
     ********************************************************************************************************/

    require_once("../Project.php");
    require_once("../EpalsWriteSql.php");

    if($argc != 3) {
        echo("Usage: $argv[0] file_path projectID \r\n");
        exit();
    }

    $f = $argv[1];
    $project_id = $argv[2];
    $accountsRemoved = 0;
    $project = new Project();
    $project->fetchOne("csId", intval($project_id));

    if(isset($project->id) && $project->csId > 0) {
        echo "The project " . $project->id . " with CS GroupID " .  $project->csId . " is fully prepared!\r\n\r\n";
        $members = $project->getMembers();
        $members_removed = array();
        $lines = file($f);
        foreach ($lines as $line) {
            $username = trim(preg_replace('/\s\s+/', ' ', $line));
            
            if(in_array($username, $members)) {
                echo $username . " removed from the GC Project!\r\n";
		array_push($members_removed, $username);
		$accountsRemoved =  $accountsRemoved + 1;
            }
            else {
                //array_push($members, $username);
                echo $username . " is not associated with the  GC Project!\r\n";
            }
            
            echo "\r\n";
        }
	$members_new = array();
	foreach(array_diff($members, $members_removed) as $key=> $val) {
		array_push($members_new, $val);
	}
        //print_r ($members_new);
        $project->members = $members_new;
        $project->update();
        
        echo "There are " . $accountsRemoved . " Neupals accounts removed from Project " . $project->id .  "\r\n";
    }
    else {
        echo "The Project either does not exist or does not have CS group provisioned already!\r\n";
        exit();
    }
?>
