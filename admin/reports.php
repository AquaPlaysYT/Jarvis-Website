<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");
error_reporting(E_ALL ^ E_NOTICE);

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

// Checks Users Privs

if(!isset($_SESSION['user_id']))
{
  header("location:https://jarvischatbot.xyz/login.php?action=login");
}
else if($_SESSION['verified'] == "[Jarvions] " || $_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
{

}
else
{
  header("location:https://jarvischatbot.xyz");
}

$reportID = $_REQUEST['id'];
$viewPattern = $_REQUEST['view'];

$noData = 0;
$error = "";
$break = 50;

if(isset($_POST["getReportID"]))
{
    $query = "SELECT * FROM NewReports WHERE Pending = 1 ORDER BY
    case Flag
    when 'JARVION_REPORT' then 1
    when 'USER_COMMAND_REPORT' then 2
    when 'POSSIBLE_PROFANITY_DETECTION' then 3
    when 'USER_REACTION_REPORT' then 4
    when 'ANTISPAM_DETECTION' then 5
    when 'LOW_ENGLISH_CONTENT' then 6
    ELSE 6
    end
    , RAND() LIMIT 1";
    $statement = $connect->prepare($query);
    $statement->execute();
    $count = $statement->rowCount();
    if ($count > 0)
    {
        $result = $statement->fetchAll();
        foreach ($result as $row)
        {
            echo '<script>console.log("Request Found"); </script>';

            $ReportIDSQL = $row['ReportID'];

            header("location:https://jarvischatbot.xyz/admin/report/$ReportIDSQL");
            
            break;

        }
    }
    else
    {
        
    }
}

if(isset($_POST["ReportA"]))
{
    $DiscordID = $_SESSION['discord_id'];

    if($_POST['user_blacklist'] == 'value1')
    {
        $user_blacklisted = 1;
    }
    else
    {
        $user_blacklisted = 0;
    }

    if($_SESSION['TotalCount'] == "")
    {
        
    }
    else
    {
        echo '<script>console.log("Sent"); </script>';

        $query1 = "UPDATE NewReports SET Pending = 0, Accepted = 1, userPunish = $user_blacklisted, HandlerID = $DiscordID, Processed = 0 where ReportID = $reportID";
        $statement1 = $connect->prepare($query1);
        $statement1->execute();
    
        $query2 = "UPDATE WebUsers SET weeklyReports = weeklyReports + 1 WHERE discord_id = $DiscordID";
        $statement2 = $connect->prepare($query2);
        $statement2->execute();

        $url = "https://discordapp.com/api/webhooks/729007759252783215/QLqa4xiDTtLUxXvqWbpR9uqQRtCOxqBHSdL-fN0aRuyEdO9LrlevCC7vimOvjg3gjb1Q";
        $discord = $_SESSION['username'];
        $id = $_SESSION['discord_id'];
        $avatar = $_SESSION['avatar'];
        $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=256";

        $input_discord = $_SESSION['Input'];
        $output_discord = $_SESSION['Response'];
        $FormattedReportTextInput = implode(PHP_EOL, str_split($input_discord, $break));
        $FormattedReportTextOutput = implode(PHP_EOL, str_split($output_discord, $break));
        $PatternID = $_SESSION['PatternID'];

        if($_POST['pattern_blacklist'] == 'value1')
        {
            $query = "INSERT INTO Filter (Word, Submitter) VALUES ('$output_discord', $id)";
            $statement = $connect->prepare($query);
            $statement->execute();
            
        }

        //creates a webhook for discord to execute
      
        $hookObject = json_encode([
            "username" => $discord,
            "avatar_url" => $image,
            "embeds" => [
                [
                    "title" => "Report Accepted | $reportID",
                    "type" => "rich",
                    "url" => "https://jarvischatbot.xyz/admin/report/$reportID/view",
        
                    "color" => hexdec( "11FF00" ),
        
                    "fields" => [
                        [
                            "name" => "Input",
                            "value" => $FormattedReportTextInput,
                            "inline" => false
                        ],
                        [
                            "name" => "Output",
                            "value" => $FormattedReportTextOutput,
                            "inline" => true
                        ],
                        [
                            "name" => "HandlerID",
                            "value" => $DiscordID,
                            "inline" => false
                        ],
                        [
                            "name" => "User Punished",
                            "value" => $user_blacklisted,
                            "inline" => true
                        ],
                        [
                            "name" => "PatternID",
                            "value" => $PatternID,
                            "inline" => true
                        ],
                    ]
                ]
            ]
        
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    
        $ch = curl_init();
        
        curl_setopt_array( $ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ]);
        
        $response = curl_exec( $ch );
        curl_close( $ch );

        $query = "SELECT * FROM NewReports WHERE Pending = 1 ORDER BY
        case Flag
        when 'JARVION_REPORT' then 1
        when 'USER_COMMAND_REPORT' then 2
        when 'POSSIBLE_PROFANITY_DETECTION' then 3
        when 'USER_REACTION_REPORT' then 4
        when 'ANTISPAM_DETECTION' then 5
        when 'LOW_ENGLISH_CONTENT' then 6
        ELSE 6
        end
        , RAND() LIMIT 25";
        $statement = $connect->prepare($query);
        $statement->execute();
        $count = $statement->rowCount();
        if ($count > 0)
        {
            $result = $statement->fetchAll();
            foreach ($result as $row)
            {
                echo '<script>console.log("Request Found"); </script>';
    
                $ReportIDSQL = $row['ReportID'];
    
                header("location:https://jarvischatbot.xyz/admin/report/$ReportIDSQL");

                break;
    
            }
        }
    }
}

if (isset($_POST["ReportN"]))
{
    $DiscordID = $_SESSION['discord_id'];

    if($_SESSION['TotalCount'] == "")
    {
        
    }
    else
    {
        $query1 = "UPDATE NewReports SET Pending = 0, Accepted = 0, userPunish = 0, Processed = 1, HandlerID = $DiscordID WHERE ReportID = $reportID";
        $statement1 = $connect->prepare($query1);
        $statement1->execute();
    
        $query4 = "UPDATE WebUsers SET weeklyReports = weeklyReports + 1 WHERE discord_id = $DiscordID";
        $statement4 = $connect->prepare($query4);
        $statement4->execute();
    
        $url = "https://discordapp.com/api/webhooks/729007759252783215/QLqa4xiDTtLUxXvqWbpR9uqQRtCOxqBHSdL-fN0aRuyEdO9LrlevCC7vimOvjg3gjb1Q";
        $discord = $_SESSION['username'];
        $id = $_SESSION['discord_id'];
        $avatar = $_SESSION['avatar'];
        $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=256";

        $input_discord = $_SESSION['Input'];
        $output_discord = $_SESSION['Response'];
        $FormattedReportTextInput = implode(PHP_EOL, str_split($input_discord, $break));
        $FormattedReportTextOutput = implode(PHP_EOL, str_split($output_discord, $break));
        $PatternID = $_SESSION['PatternID'];

        //creates a webhook for discord to execute
      
        $hookObject = json_encode([
            "username" => $discord,
            "avatar_url" => $image,
            "embeds" => [
                [
                    "title" => "Report Ignored | $reportID",
                    "type" => "rich",
                    "url" => "https://jarvischatbot.xyz/admin/report/$reportID/view",
        
                    "color" => hexdec( "FF0000" ),
        
                    "fields" => [
                        [
                            "name" => "Input",
                            "value" => $FormattedReportTextInput,
                            "inline" => false
                        ],
                        [
                            "name" => "Output",
                            "value" => $FormattedReportTextOutput,
                            "inline" => true
                        ],
                        [
                            "name" => "HandlerID",
                            "value" => $DiscordID,
                            "inline" => false
                        ],
                        [
                            "name" => "PatternID",
                            "value" => $PatternID,
                            "inline" => true
                        ],
                    ]
                ]
            ]
        
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    
        $ch = curl_init();
        
        curl_setopt_array( $ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ]);
        
        $response = curl_exec( $ch );
        curl_close( $ch );

        $query = "SELECT * FROM NewReports WHERE Pending = 1 ORDER BY
        case Flag
        when 'JARVION_REPORT' then 1
        when 'USER_COMMAND_REPORT' then 2
        when 'POSSIBLE_PROFANITY_DETECTION' then 3
        when 'USER_REACTION_REPORT' then 4
        when 'ANTISPAM_DETECTION' then 5
        when 'LOW_ENGLISH_CONTENT' then 6
        ELSE 6
        end
        , RAND() LIMIT 25";
        $statement = $connect->prepare($query);
        $statement->execute();
        $count = $statement->rowCount();
        if ($count > 0)
        {
            $result = $statement->fetchAll();
            foreach ($result as $row)
            {
                echo '<script>console.log("Request Found"); </script>';
    
                $ReportIDSQL = $row['ReportID'];
    
                header("location:https://jarvischatbot.xyz/admin/report/$ReportIDSQL");
    
                break;

            }
        }
        
    }
}

function clean($string) {
    $string = str_replace(' ', '-', $string); 
 
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); 
}

//Grabs ReportID From URL

if($viewPattern > 0)
{
    $query1 = "SELECT * FROM NewReports WHERE ReportID = $viewPattern = 1 ORDER BY
    case Flag
    when 'USER_COMMAND_REPORT' then 1
    when 'POSSIBLE_PROFANITY_DETECTION' then 2
    when 'USER_REACTION_REPORT' then 3
    when 'ANTISPAM_DETECTION' then 4
    when 'LOW_ENGLISH_CONTENT' then 5
    ELSE 6
    end
    , RAND() LIMIT 1";
    $statement1 = $connect->prepare($query1);
    $statement1->execute();
    $count1 = $statement1->rowCount();
    if ($count1 > 0)
    {
        $result = $statement1->fetchAll();
        foreach ($result as $row)
        {
            echo '<script>console.log("Request Found"); </script>';

            $_SESSION['ID'] = $row['ReportID'];
            $_SESSION['PatternID'] = $row['PatternID'];
            $_SESSION['ReporterID'] = $row['ReporterID'];
			$_SESSION['Flag'] = $row['Flag'];
            $_SESSION['Notes'] = $row['Notes'];
            $_SESSION['Accepted'] = $row['Accepted'];
            $_SESSION['userPunish'] = $row['userPunish'];
            $_SESSION['HandlerID'] = $row['HandlerID'];
            $EmojiString = "";

            $PatternID = $_SESSION['PatternID'];

            $query2 = "SELECT * FROM Data WHERE ID = $PatternID LIMIT 1";
            $statement2 = $connect->prepare($query2);
            $statement2->execute();
            $count2 = $statement2->rowCount();
            if ($count2 > 0)
            {
                $result1 = $statement2->fetchAll();
                foreach ($result1 as $row1)
                {
                    $_SESSION['Input'] = $row1['Input'];
                    $_SESSION['Response'] = $row1['Response'];
                }

            }
                      
            $string = $_SESSION['Input']; 
            $string1 = $_SESSION['Response'];  

            //Input

            $FormattedReportText = implode(PHP_EOL, str_split($string, $break));
            $FormattedReportText1 = str_replace("<", "[", $FormattedReportText);
            $FormattedReportText = str_replace(">", "]", $FormattedReportText1);

            //Output

            $FormattedReportText2 = implode(PHP_EOL, str_split($string1, $break));
            $FormattedReportText3 = str_replace("<", "[", $FormattedReportText2);
            $FormattedReportText2 = str_replace(">", "]", $FormattedReportText3);

            #Discord Emoji Detection

            //Input

            if(strpos($FormattedReportText, '<:') !== false) {

                $FormattedReportText = clean($FormattedReportText);
            }

            //Output

            if(strpos($FormattedReportText2, '<:') !== false) {

                $FormattedReportText2 = clean($FormattedReportText2);
            }

            #Discord Role Detection

            //Input

            if(strpos($FormattedReportText, '<@&') !== false) {

                $FormattedReportText = '#' . clean($FormattedReportText);
            }

            //Output

            if(strpos($FormattedReportText2, '<@&') !== false) {

                $FormattedReportText2 = '#' . clean($FormattedReportText2);
            }

            #Discord Bot Detection

            //Input

            else if(strpos($FormattedReportText, '<@!') !== false) {
                $int = (int) filter_var($FormattedReportText, FILTER_SANITIZE_NUMBER_INT);
                
                $url = "https://discordapp.com/api/users/$int";
    
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url, 
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.Xv3fOQ.rwwwh-tWY9BeS_OxXbFKfvXHsvA'), 
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $output = json_decode($response, true);

                $username = $output['username'];

                $FormattedReportText = '@' . clean($username);
            }

            //Output

            else if(strpos($FormattedReportText2, '<@!') !== false) {
                $int = (int) filter_var($FormattedReportText2, FILTER_SANITIZE_NUMBER_INT);
                
                $url = "https://discordapp.com/api/users/$int";
    
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url, 
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.Xv3fOQ.rwwwh-tWY9BeS_OxXbFKfvXHsvA'), 
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $output = json_decode($response, true);

                $username = $output['username'];

                $FormattedReportText2 = '@' . clean($username);
            }

            #Discord User Detection


            //input

            else if(strpos($FormattedReportText, '<@') !== false) {
                $int = (int) filter_var($FormattedReportText, FILTER_SANITIZE_NUMBER_INT);
                
                $url = "https://discordapp.com/api/users/$int";
    
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url, 
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.Xv3fOQ.rwwwh-tWY9BeS_OxXbFKfvXHsvA'), 
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $output = json_decode($response, true);

                $username = $output['username'];

                $FormattedReportText = '@' . clean($username);
            }

            //Output

            else if(strpos($FormattedReportText2, '<@') !== false) {
                $int = (int) filter_var($FormattedReportText2, FILTER_SANITIZE_NUMBER_INT);
                
                $url = "https://discordapp.com/api/users/$int";
    
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url, 
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.Xv3fOQ.rwwwh-tWY9BeS_OxXbFKfvXHsvA'), 
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $output = json_decode($response, true);

                $username = $output['username'];

                $FormattedReportText2 = '@' . clean($username);
            }
        }
    }
    else
    {       
        echo '<script>console.log("No Data To Show"); </script>';
        $noData = 1;
        $error = "No report found for " . $viewPattern;
    }

    echo '
    <!DOCTYPE html>
    <html lang="en">

    <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Jarvis - Dashboard</title>

    <link href="https://jarvischatbot.xyz/admin/style/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://jarvischatbot.xyz/admin/style/sb-admin-2.min.main4.css" rel="stylesheet">

    </head>

    <style>
    input[type=text] {
        width: 220px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        background-color: white;
        background-image: url("searchicon.png");
        background-position: 10px 10px; 
        background-repeat: no-repeat;
        padding: 12px 20px 12px 40px;
        -webkit-transition: width 0.4s ease-in-out;
        transition: width 0.4s ease-in-out;
    }

    .card-footer {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: #26262c;
    border-top: 1px solid #e3e6f0
    }

    .avatar {
    border-radius: 50%;
    }

    .icon {
    height: 40px;
    width: 40px;
    }

    .close {
        float: right;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #ffffff;
        text-shadow: 0 1px 0 #fff;
        opacity: .5
    }
    
    .close:hover {
        color: #ffffff;
        text-decoration: none
    }
    </style>

    <body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="https://jarvischatbot.xyz/admin">
            <div class="sidebar-brand-text">Jarvis Admin</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item">
            <a class="nav-link" href="https://jarvischatbot.xyz/admin">
            <span>Home Page</span></a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="https://jarvischatbot.xyz/">
            <span>Return</span></a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Discord
        </div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-folder"></i>
            <span>Discord Information</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <h6 class="collapse-header">Discord Lookup</h6>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/servers.php">Servers</a>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/members.php">Members</a>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/patterns.php">Patterns</a>
                ';
                if($_SESSION['verified'] !== "[Jarvions] ")
                {
                  echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/staff.php">Staff</a>';
                }
                echo '
            </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Manage Information</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <h6 class="collapse-header">Reports / Patterns</h6>
                <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/reports.php">Reports</a>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/jarvion.php">Patterns</a>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodphrases">Mood Phrases</a>
                ';
                if($_SESSION['verified'] !== "[Jarvions] ")
                {
                    echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodterms">Mood Terms</a>';
                }
                echo '
            </div>
            </div>
        </li>
        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
        </ul> 
    
        <div id="content-wrapper" class="d-flex flex-column bg-dark">

        <!-- Main Content -->
        <div id="content">
            <nav class="navbar navbar-expand navbar-dark bg-dark-nav topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
            ';

                $id = $_SESSION['discord_id'];  
                $avatar = $_SESSION['avatar'];
                $username = $_SESSION['username'];
                        
                if($avatar == NULL)
                {
                    $image = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png"; 
                }
                else
                {
                    $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=64";  
                }

            echo '
            <ul class="navbar-nav ml-auto">      
                <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-white-600 medium"><b>'.$username.'</b></span>
                    <img class="img-profile rounded-circle" height="50" width="50" src="'.$image.'">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-white-400"></i>
                    Logout
                    </a>
                </div>
                </li>
            </ul>
            </nav>

            <div class="container-fluid">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-white-800">Logged in as <b>'.$_SESSION['username'] . '#' . $_SESSION['discriminator'].'</b></h1>
            </div>

            <div class="row">
            ';

            $accepted = $_SESSION['Accepted'];
            $punish = $_SESSION['userPunish'];

            if($punish == "1")
            {
                $punish = "User punished";
            }
            else
            {
                $punish = "User was not punished";
            }

            if($accepted == "1")
            {
                $accepted = "Report Accepted";
            }
            else
            {
                $accepted = "Report Denied";
            }

            if($_SESSION['HandlerID'] == "")
            {
                $discord_id_output = "This report has not been handled!";
            }
            else
            {
                $Profile_ID = $_SESSION['HandlerID'];
                $discord_id_output = "This report was handled by <a href='https://jarvischatbot.xyz/users/$Profile_ID'><b>" . $Profile_ID . "</b></a>";
            }

            if($noData == 1)
            {
                echo '<h2>'.$error.'</h2>';
            }
            else
            {
                echo'
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <h3 class="m-0 font-weight-bold text-white">Report #'.$viewPattern.'</h3>
                        </div>
                        <form method="post">
                            <div class="card-body"> 
                                <h3><img src="'.$image.'" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($FormattedReportText) .'</b> </h3>
                                <h3><img src="https://jarvischatbot.xyz/images/jarvis-new.png" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($FormattedReportText2) .'</b> </h3>
                            </div>
                        </form>   
                        <div class="card-footer py-3">
                            <h3>'.$discord_id_output.'</h3>
                            <h3>Report Status: '.$accepted.'</h3>
                            <h3>User Status: '.$punish.'</h3>
                            <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/admin/reports.php" role="button">Return</a>
                        </div>
                    </div>
                </div> 
                ';
            }

            echo '

            </div>  
            </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">X</span>
            </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="https://jarvischatbot.xyz/logout.php">Logout</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="http://jarvischatbot.xyz/admin/style/jquery.min.js"></script>
    <script src="http://jarvischatbot.xyz/admin/style/bootstrap.bundle.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="http://jarvischatbot.xyz/admin/style/sb-admin-2.min.js"></script>

    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
  </script>

    <script>
    jQuery(document).ready(function($) {
        $("*[data-href]").on("click", function() {
            window.location = $(this).data("href");
        }); 
    });
    </script>

    </body>
    </html>
    ';
}
else if($reportID == "")
{
    echo '
    <!DOCTYPE html>
    <html lang="en">

    <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Jarvis - Dashboard</title>

    <link href="https://jarvischatbot.xyz/admin/style/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://jarvischatbot.xyz/admin/style/sb-admin-2.min.main4.css" rel="stylesheet">

    </head>

    <style>
    input[type=text] {
        width: 220px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        background-color: white;
        background-image: url("searchicon.png");
        background-position: 10px 10px; 
        background-repeat: no-repeat;
        padding: 12px 20px 12px 40px;
        -webkit-transition: width 0.4s ease-in-out;
        transition: width 0.4s ease-in-out;
    }

    .card-footer {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: #26262c;
    border-top: 1px solid #e3e6f0
    }

    .avatar {
    border-radius: 50%;
    }

    .icon {
    height: 40px;
    width: 40px;
    }

    .close {
        float: right;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #ffffff;
        text-shadow: 0 1px 0 #fff;
        opacity: .5
    }
    
    .close:hover {
        color: #ffffff;
        text-decoration: none
    }
    </style>

    <body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="https://jarvischatbot.xyz/admin">
            <div class="sidebar-brand-text">Jarvis Admin</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item">
            <a class="nav-link" href="https://jarvischatbot.xyz/admin">
            <span>Home Page</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="https://jarvischatbot.xyz/">
            <span>Return</span></a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Discord
        </div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-folder"></i>
            <span>Discord Information</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <h6 class="collapse-header">Discord Lookup</h6>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/servers.php">Servers</a>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/members.php">Members</a>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/patterns.php">Patterns</a>
                ';
                if($_SESSION['verified'] !== "[Jarvions] ")
                {
                  echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/staff.php">Staff</a>';
                }
                echo '
            </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Manage Information</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-dark py-2 collapse-inner rounded">
                <h6 class="collapse-header">Reports / Patterns</h6>
                <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/reports.php">Reports</a>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/jarvion.php">Patterns</a>
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodphrases">Mood Phrases</a>
                ';
                if($_SESSION['verified'] !== "[Jarvions] ")
                {
                    echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodterms">Mood Terms</a>';
                }
                echo '
            </div>
            </div>
        </li>
        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
        </ul> 
    
        <div id="content-wrapper" class="d-flex flex-column bg-dark">

        <!-- Main Content -->
        <div id="content">
            <nav class="navbar navbar-expand navbar-dark bg-dark-nav topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
            ';

                $id = $_SESSION['discord_id'];  
                $avatar = $_SESSION['avatar'];
                $username = $_SESSION['username'];
                        
                if($avatar == NULL)
                {
                    $image = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png"; 
                }
                else
                {
                    $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=64";  
                }

            echo '
            <ul class="navbar-nav ml-auto">      
                <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-white-600 medium"><b>'.$username.'</b></span>
                    <img class="img-profile rounded-circle" height="50" width="50" src="'.$image.'">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-white-400"></i>
                    Logout
                    </a>
                </div>
                </li>
            </ul>
            </nav>

            <div class="container-fluid">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-white-800">Logged in as <b>'.$_SESSION['username'] . '#' . $_SESSION['discriminator'].'</b></h1>
            </div>

            <div class="row">

                <div class="col-xl-4 col-lg-5">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                    <h3 class="m-0 font-weight-bold text-white">Jarvis Report Managment</h3>
                    </div>
                    <form method="post">
                    <div class="card-body"> 
                        <h1>Welcome to Jarvis Pattern Reports!</h1>
                        <br>
                        <h2><font color = red>If you have not been trained to use this panel please ask for training from staff!</font></h2>
                        <br>
                        <h2>If you need any help during this process make sure to contact your higher up and ask any questions</h2>
                        <form method="post">
                            <div class="form-group">
                                <input type="submit" name="getReportID" class="btn btn-primary btn-lg" value="Get Started" />
                            </div>
                        </form>
                    </div>
                    </form>   
                </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                    <h3 class="m-0 font-weight-bold text-white">Report Guidelines</h3>
                    </div>
                    <form method="post">
                    <div class="card-body"> 
                        <h2>Before getting started please make sure you have read the report Guidelines!</h2>
                        <a class="btn btn-primary btn-lg" href="https://docs.google.com/document/d/1GRHb9JHyZgv-B3ydc4tir5MqWHUKkUB2IF_ahmNMUpM/edit?usp=drive_web&ouid=117131297955982298604" role="button">Read Here</a>
                    </div>
                    </form>   
                </div>
                </div>
            </div> 
            
            </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">X</span>
            </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="https://jarvischatbot.xyz/logout.php">Logout</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="http://jarvischatbot.xyz/admin/style/jquery.min.js"></script>
    <script src="http://jarvischatbot.xyz/admin/style/bootstrap.bundle.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="http://jarvischatbot.xyz/admin/style/sb-admin-2.min.js"></script>

    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
  </script>

    <script>
    jQuery(document).ready(function($) {
        $("*[data-href]").on("click", function() {
            window.location = $(this).data("href");
        }); 
    });
    </script>

    </body>
    </html>
    ';
}
else
{

    $query = "SELECT * FROM NewReports WHERE Pending = 1";
    $statement = $connect->prepare($query);
    $statement->execute();
    $count = $statement->rowCount();
    $_SESSION['TotalCount'] = $count;

    $query1 = "SELECT * FROM NewReports WHERE Pending = 1 AND ReportID = $reportID ORDER BY
    case Flag
    when 'USER_COMMAND_REPORT' then 1
    when 'POSSIBLE_PROFANITY_DETECTION' then 2
    when 'USER_REACTION_REPORT' then 3
    when 'ANTISPAM_DETECTION' then 4
    when 'LOW_ENGLISH_CONTENT' then 5
    ELSE 6
    end
    , RAND() LIMIT 1";
    
    $statement1 = $connect->prepare($query1);
    $statement1->execute();
    $count1 = $statement1->rowCount();
    if ($count1 > 0)
    {
        $result = $statement1->fetchAll();
        foreach ($result as $row)
        {
            echo '<script>console.log("Request Found"); </script>';

            $_SESSION['ID'] = $row['ReportID'];
            $_SESSION['PatternID'] = $row['PatternID'];
            $_SESSION['ReporterID'] = $row['ReporterID'];
			$_SESSION['Flag'] = $row['Flag'];
            $_SESSION['Notes'] = $row['Notes'];
            $EmojiString = "";

            $PatternID = $_SESSION['PatternID'];

            $query2 = "SELECT * FROM Data WHERE ID = $PatternID LIMIT 1";
            $statement2 = $connect->prepare($query2);
            $statement2->execute();
            $count2 = $statement2->rowCount();
            if ($count2 > 0)
            {
                $result1 = $statement2->fetchAll();
                foreach ($result1 as $row1)
                {
                    $_SESSION['Input'] = $row1['Input'];
                    $_SESSION['Response'] = $row1['Response'];
                }

            }
                      
            $string = $_SESSION['Input']; 
            $string1 = $_SESSION['Response'];  

            //Input

            $FormattedReportText = implode(PHP_EOL, str_split($string, $break));
            $FormattedReportText1 = str_replace("<", "[", $FormattedReportText);
            $FormattedReportText = str_replace(">", "]", $FormattedReportText1);

            //Output

            $FormattedReportText2 = implode(PHP_EOL, str_split($string1, $break));
            $FormattedReportText3 = str_replace("<", "[", $FormattedReportText2);
            $FormattedReportText2 = str_replace(">", "]", $FormattedReportText3);

            #Discord Emoji Detection

            //Input

            if(strpos($FormattedReportText, '<:') !== false) {

                $FormattedReportText = clean($FormattedReportText);
            }

            //Output

            if(strpos($FormattedReportText2, '<:') !== false) {

                $FormattedReportText2 = clean($FormattedReportText2);
            }

            #Discord Role Detection

            //Input

            if(strpos($FormattedReportText, '<@&') !== false) {

                $FormattedReportText = '#' . clean($FormattedReportText);
            }

            //Output

            if(strpos($FormattedReportText2, '<@&') !== false) {

                $FormattedReportText2 = '#' . clean($FormattedReportText2);
            }

            #Discord Bot Detection

            //Input

            else if(strpos($FormattedReportText, '<@!') !== false) {
                $int = (int) filter_var($FormattedReportText, FILTER_SANITIZE_NUMBER_INT);
                
                $url = "https://discordapp.com/api/users/$int";
    
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url, 
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.Xv3fOQ.rwwwh-tWY9BeS_OxXbFKfvXHsvA'), 
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $output = json_decode($response, true);

                $username = $output['username'];

                $FormattedReportText = '@' . clean($username);
            }

            //Output

            else if(strpos($FormattedReportText2, '<@!') !== false) {
                $int = (int) filter_var($FormattedReportText2, FILTER_SANITIZE_NUMBER_INT);
                
                $url = "https://discordapp.com/api/users/$int";
    
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url, 
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.Xv3fOQ.rwwwh-tWY9BeS_OxXbFKfvXHsvA'), 
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $output = json_decode($response, true);

                $username = $output['username'];

                $FormattedReportText2 = '@' . clean($username);
            }

            #Discord User Detection


            //input

            else if(strpos($FormattedReportText, '<@') !== false) {
                $int = (int) filter_var($FormattedReportText, FILTER_SANITIZE_NUMBER_INT);
                
                $url = "https://discordapp.com/api/users/$int";
    
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url, 
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.Xv3fOQ.rwwwh-tWY9BeS_OxXbFKfvXHsvA'), 
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $output = json_decode($response, true);

                $username = $output['username'];

                $FormattedReportText = '@' . clean($username);
            }

            //Output

            else if(strpos($FormattedReportText2, '<@') !== false) {
                $int = (int) filter_var($FormattedReportText2, FILTER_SANITIZE_NUMBER_INT);
                
                $url = "https://discordapp.com/api/users/$int";
    
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url, 
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.Xv3fOQ.rwwwh-tWY9BeS_OxXbFKfvXHsvA'), 
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $output = json_decode($response, true);

                $username = $output['username'];

                $FormattedReportText2 = '@' . clean($username);
            }
        }
        echo '
        <!DOCTYPE html>
        <html lang="en">
    
        <head>
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
    
        <title>Jarvis - Dashboard</title>
    
        <link href="https://jarvischatbot.xyz/admin/style/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="https://jarvischatbot.xyz/admin/style/sb-admin-2.min.main4.css" rel="stylesheet">
    
        </head>
    
        <style>
        input[type=text] {
            width: 220px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: white;
            background-image: url("searchicon.png");
            background-position: 10px 10px; 
            background-repeat: no-repeat;
            padding: 12px 20px 12px 40px;
            -webkit-transition: width 0.4s ease-in-out;
            transition: width 0.4s ease-in-out;
        }
    
        .card-footer {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: #26262c;
        border-top: 1px solid #e3e6f0
        }
    
        .avatar {
        border-radius: 50%;
        }
    
        .icon {
        height: 40px;
        width: 40px;
        }

        .close {
            float: right;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #ffffff;
            text-shadow: 0 1px 0 #fff;
            opacity: .5
        }
        
        .close:hover {
            color: #ffffff;
            text-decoration: none
        }
        </style>
    
        <body id="page-top">
    
        <div id="wrapper">
    
            <ul class="navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion" id="accordionSidebar">
    
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="https://jarvischatbot.xyz/admin">
                <div class="sidebar-brand-text">Jarvis Admin</div>
            </a>
    
            <hr class="sidebar-divider my-0">
    
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/admin">
                <span>Home Page</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/">
                 <span>Return</span></a>
            </li>


    
            <hr class="sidebar-divider">
    
            <div class="sidebar-heading">
                Discord
            </div>
    
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-folder"></i>
                <span>Discord Information</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-dark py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Discord Lookup</h6>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/servers.php">Servers</a>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/members.php">Members</a>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/patterns.php">Patterns</a>
                    ';
                if($_SESSION['verified'] !== "[Jarvions] ")
                {
                  echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/staff.php">Staff</a>';
                }
                echo '
                </div>
                </div>
            </li>
    
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Manage Information</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-dark py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Reports / Patterns</h6>
                    <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/reports.php">Reports</a>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/jarvion.php">Patterns</a>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodphrases">Mood Phrases</a>
                    ';
                    if($_SESSION['verified'] !== "[Jarvions] ")
                    {
                        echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodterms">Mood Terms</a>';
                    }
                    echo '
                </div>
                </div>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
    
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
            </ul> 
        
            <div id="content-wrapper" class="d-flex flex-column bg-dark">
    
            <!-- Main Content -->
            <div id="content">
                <nav class="navbar navbar-expand navbar-dark bg-dark-nav topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                ';
    
                    $id = $_SESSION['discord_id'];  
                    $avatar = $_SESSION['avatar'];
                    $username = $_SESSION['username'];
                            
                    if($avatar == NULL)
                    {
                        $image = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png"; 
                    }
                    else
                    {
                        $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=64";  
                    }
    
                echo '
                <ul class="navbar-nav ml-auto">      
                    <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-white-600 medium"><b>'.$username.'</b></span>
                        <img class="img-profile rounded-circle" height="50" width="50" src="'.$image.'">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-white-400"></i>
                        Logout
                        </a>
                    </div>
                    </li>
                </ul>
                </nav>
    
                <div class="container-fluid">
    
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-white-800">Logged in as <b>'.$_SESSION['username'] . '#' . $_SESSION['discriminator'].'</b></h1>
                </div>
                <div class="row">
                ';
                         
                echo'
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <h3 class="m-0 font-weight-bold text-white">Report #'.$reportID.'</h3>
                        </div>
                        <form method="post">
                            <div class="card-body"> 
                                <h3><img src="'.$image.'" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($FormattedReportText) .'</b> </h3>
                                <h3><img src="https://jarvischatbot.xyz/images/jarvis-new.png" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($FormattedReportText2) .'</b> </h3>          
                            </div>
                            <div class="card-footer py-3">
                            <div class="form-group">
                                <input type="checkbox" name="user_blacklist" value="value1">
                                <label for="user_blacklist">BlackList User</label><br>
                                <input type="checkbox" name="pattern_blacklist" value="value1">
                                <label for="pattern_blacklist">Add Response To Filter</label><br>  
                                <input type="submit" name="ReportA" class="btn btn-primary btn-lg" value="Accept Report" />
                                <input type="submit" name="ReportN" class="btn btn-primary btn-lg" value="Deny Report" />
                                <input type="submit" name="getReportID" class="btn btn-primary btn-lg" value="Skip Report" />
                            </div>
                            </div>
                        </form>   
                    </div>
                </div> 
                ';
            
                echo '<h2><font color="red">'.$error.'</font></h2>';

                echo '
                <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                    <h3 class="m-0 font-weight-bold text-white">Pattern Debugging</h3>
                    </div>
                    <div class="card-body"> 
                        <h2 class="id">Flag: <b>'.$_SESSION['Flag'].'</h2>
						<h2 class="id">PatternID: <b>'.$_SESSION['PatternID'].'</h2>
                        <h2 class="id">ReportID: <b>'.$reportID.'</h2>
						<h2 class="id">Notes: <b>'.$_SESSION['Notes'].'</h2>
                        <h2 class="id">ReporterID: <b>'.$_SESSION['ReporterID'].'</h2>
                        <h2 class="id">Remaining Reports: <b>'.$_SESSION['TotalCount'].'</h2>
                    </div>
                </div> 
                </div>  
                </div>
                </div>
                </div>
            </div>
        </div>
    
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="https://jarvischatbot.xyz/logout.php">Logout</a>
                </div>
            </div>
            </div>
        </div>
    
        <!-- Bootstrap core JavaScript-->
        <script src="http://jarvischatbot.xyz/admin/style/jquery.min.js"></script>
        <script src="http://jarvischatbot.xyz/admin/style/bootstrap.bundle.min.js"></script>
        
        <!-- Custom scripts for all pages-->
        <script src="http://jarvischatbot.xyz/admin/style/sb-admin-2.min.js"></script>
    
        <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
      </script>

        <script>
        jQuery(document).ready(function($) {
            $("*[data-href]").on("click", function() {
                window.location = $(this).data("href");
            }); 
        });
        </script>
    
        </body>
        </html>
    ';

    }
    else
    {
        $FormattedReportText = "";
        $_SESSION['ID'] = "";
        $_SESSION['ReporterID'] = "";
        $_SESSION['ReportedText'] = "";
		$_SESSION['Flag'] = "";
		$_SESSION['Notes'] = "";
        $error = "The report has been handled!";
                 
        echo '
        <!DOCTYPE html>
        <html lang="en">
    
        <head>
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
    
        <title>Jarvis - Dashboard</title>
    
        <link href="https://jarvischatbot.xyz/admin/style/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="https://jarvischatbot.xyz/admin/style/sb-admin-2.min.main4.css" rel="stylesheet">
    
        </head>
    
        <style>
        input[type=text] {
            width: 220px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: white;
            background-image: url("searchicon.png");
            background-position: 10px 10px; 
            background-repeat: no-repeat;
            padding: 12px 20px 12px 40px;
            -webkit-transition: width 0.4s ease-in-out;
            transition: width 0.4s ease-in-out;
        }
    
        .card-footer {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: #26262c;
        border-top: 1px solid #e3e6f0
        }
    
        .avatar {
        border-radius: 50%;
        }
    
        .icon {
        height: 40px;
        width: 40px;
        }

        .close {
            float: right;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #ffffff;
            text-shadow: 0 1px 0 #fff;
            opacity: .5
        }
        
        .close:hover {
            color: #ffffff;
            text-decoration: none
        }
        </style>
    
        <body id="page-top">
    
        <div id="wrapper">
    
            <ul class="navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion" id="accordionSidebar">
    
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="https://jarvischatbot.xyz/admin">
                <div class="sidebar-brand-text">Jarvis Admin</div>
            </a>
    
            <hr class="sidebar-divider my-0">
    
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/admin">
                <span>Home Page</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/">
                 <span>Return</span></a>
            </li>
    
            <hr class="sidebar-divider">
    
            <div class="sidebar-heading">
                Discord
            </div>
    
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-folder"></i>
                <span>Discord Information</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-dark py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Discord Lookup</h6>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/servers.php">Servers</a>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/members.php">Members</a>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/patterns.php">Patterns</a>
                    ';
                if($_SESSION['verified'] !== "[Jarvions] ")
                {
                  echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/staff.php">Staff</a>';
                }
                echo '
                </div>
                </div>
            </li>
    
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Manage Information</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-dark py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Reports / Patterns</h6>
                    <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/reports.php">Reports</a>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/jarvion.php">Patterns</a>
                    <a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodphrases">Mood Phrases</a>
                    ';
                    if($_SESSION['verified'] !== "[Jarvions] ")
                    {
                        echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodterms">Mood Terms</a>';
                    }
                    echo '
                </div>
                </div>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
    
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
            </ul> 
        
            <div id="content-wrapper" class="d-flex flex-column bg-dark">
    
            <!-- Main Content -->
            <div id="content">
                <nav class="navbar navbar-expand navbar-dark bg-dark-nav topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                ';
    
                    $id = $_SESSION['discord_id'];  
                    $avatar = $_SESSION['avatar'];
                    $username = $_SESSION['username'];
                            
                    if($avatar == NULL)
                    {
                        $image = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png"; 
                    }
                    else
                    {
                        $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=64";  
                    }
    
                echo '
                <ul class="navbar-nav ml-auto">      
                    <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-white-600 medium"><b>'.$username.'</b></span>
                        <img class="img-profile rounded-circle" height="50" width="50" src="'.$image.'">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-white-400"></i>
                        Logout
                        </a>
                    </div>
                    </li>
                </ul>
                </nav>
    
                <div class="container-fluid">
    
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-white-800">Logged in as <b>'.$_SESSION['username'] . '#' . $_SESSION['discriminator'].'</b></h1>
                </div>
    
                <div class="row">
                ';
                         
                echo'
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <h3 class="m-0 font-weight-bold text-white">Error</h3>
                        </div>
                        <div class="card-body"> 
                            <h2><font color="red">'.$error.'</font></h2>
                            <h2><a href="https://jarvischatbot.xyz/admin/report/'.$reportID.'/view">View here!</a></h2>
                        </div>
                    </div>
                </div>
    
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="https://jarvischatbot.xyz/logout.php">Logout</a>
                </div>
            </div>
            </div>
        </div>
    
        <!-- Bootstrap core JavaScript-->
        <script src="http://jarvischatbot.xyz/admin/style/jquery.min.js"></script>
        <script src="http://jarvischatbot.xyz/admin/style/bootstrap.bundle.min.js"></script>
        
        <!-- Custom scripts for all pages-->
        <script src="http://jarvischatbot.xyz/admin/style/sb-admin-2.min.js"></script>
    
        <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
        </script>

        <script>
        jQuery(document).ready(function($) {
            $("*[data-href]").on("click", function() {
                window.location = $(this).data("href");
            }); 
        });
        </script>
    
        </body>
        </html>
        ';
        
    }
}

?>