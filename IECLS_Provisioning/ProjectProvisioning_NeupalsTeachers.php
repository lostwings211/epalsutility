<?php
    
    /********************************************************************************************************
     * You have to provide with the filename and the Project ID as the parameter arguments
     * 1) The file contains usernames (without @neupals.com suffix), with the first one being the teacher
     * 2) The Project ID is the Project's corresponding Telligent GroupID
     ********************************************************************************************************/

    require_once("../TelligentRest.php");
    require_once("../UserDBUtils.php");
    require_once("../Project.php");
    require_once("../EpalsWriteSql.php");
    require_once("../CommunityServerDB.php");

    if($argc != 3) {
        echo("Usage: $argv[0] file_path projectID \r\n");
        exit();
    }

    $f = $argv[1];
    $project_id = $argv[2];
    $accountsProvisioned = 0;

    $project = new Project();
    $project->fetchOne("csId", intval($project_id));
    $t = new TelligentRest();

    if(isset($project->id) && $project->csId > 0) {
        echo "The project " . $project->id . " with CS GroupID " .  $project->csId . " is fully prepared!\r\n\r\n";
        $members = $project->getMembers();

        $lines = file($f);
        foreach ($lines as $line) {
            $accountsProvisioned =  $accountsProvisioned + 1;
            $username = trim(preg_replace('/\s\s+/', ' ', $line));
            
            if(in_array($username, $members)) {
                echo $username . " already is in the GC Project!\r\n";
            }
            else {
                array_push($members, $username);
                echo $username . " added into Membership Array of GC Project!\r\n";
            }
            
            $userData = UserDBUtils::getUserRecord($username,617);
            $fname = $userData["fname"];
            $lname = $userData["lname"];
            $displayName = "$fname $lname[0]";
            $username = $username . "@neupals.com";

            $t->createUser($username,$displayName);
            print("$username account created on the Telligent! \r\n");
            $t->updateUserLanguage($username,"zh-CN");
            print("zh-CN set for $username on the Telligent! \r\n");
            if($accountsProvisioned > 1) {
                $t->addUserToGroup($project->csId,$username,"Manager");
                print("$username added to Group as Member! \r\n\r\n");
            }
            else {
                $t->addUserToGroup($project->csId,$username,"Manager");
                print("$username added to Group as Manager! \r\n\r\n");
            }
            echo "\r\n";
        }
        $project->members = $members;
        $project->update();
        
        echo "There are " . $accountsProvisioned . " Neupals accounts provisoned to Project " . $project->id .  "\r\n";
        
        $gcmysql = new EpalsWriteSql();
        $result = $gcmysql->get_results("update smUsers set flags=flags & ~8 where namespace =  617;");
        echo "Updated the Neupals GC Users' FLAG to avoid reset password first!" .  "\r\n";
        
        
        $csDB = new CommunityServerDB();
        $csDB->doQuery("exec [ops_copyGroupPermission] @SourceGroupID =  6059, @DestGroupID = $project->csId");
        echo "Permissions for CS GroupID " . $project->csId . " has been synchronized!\r\n";
	
	$csDB->doQuery("exec [ops_copyThemeWidgets] @SourceGroupID = 5785, @DestGroupID = $project->csId");
	echo "Theme Widget for CS GroupID " . $project->csId . " has been synchronzied!\r\n";
        
    }
    else {
        echo "The Project either does not exist or does not have CS group provisioned already!\r\n";
        exit();
    }
?>
