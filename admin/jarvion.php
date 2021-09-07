<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");
error_reporting(E_ALL ^ E_NOTICE);

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

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

//

//Define default vars

$discord_id = $_SESSION['discord_id'];
$getPatternID = $_REQUEST['id'];
$userAvatar = $_SESSION['avatar'];
$userID = $_SESSION['discord_id'];
$userUsername = $_SESSION['username'];
$error = "";
$input = "";
$output = "";
$score = "";
$creator = "";
$patterns = "Rate to view";
$patternid = "";
$ratedpatterns = "";
$totalpatterns = "";
$noData = 0;
$error = "";
$break = 50;

if(isset($_POST["getPatternID"]))
{
    $query = "SELECT ID FROM Data WHERE Blocked = 0 AND Rating = 0 ORDER BY RAND() LIMIT 1";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row)
    {
        $pattern_id_grab = $row['ID'];
    }

    header("location:https://jarvischatbot.xyz/admin/pattern/$pattern_id_grab");
}

if(isset($_POST["RateReport"]))
{
    $query1 = "INSERT INTO NewReports (PatternID, ReporterID, Pending, Flag) VALUES ('$getPatternID', '$userID', 1, 'JARVIONS_REPORT')";
    $statement1 = $connect->prepare($query1);
    $statement1->execute();

    $query2 = "UPDATE WebUsers SET patterns = patterns + 1, weeklyPatterns = weeklyPatterns + 1, handling = NULL WHERE user_id = $userid";
    $statement2 = $connect->prepare($query2);
    $statement2->execute();

    $query = "SELECT ID FROM Data WHERE Blocked = 0 AND Rating = 0 ORDER BY RAND() LIMIT 1";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row)
    {
        $pattern_id_grab = $row['ID'];
    }
    header("location:https://jarvischatbot.xyz/admin/pattern/$pattern_id_grab");
}

if(isset($_POST["RateNo"]))
{
    $userid = $_SESSION['user_id'];

    $query = "SELECT * FROM WebUsers WHERE user_id = $userid";
    $statement = $connect->prepare($query);
    $statement->execute();	
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        $newID = $row["handling"];
        if ($newID !== NULL) {
            echo "Stat Update!";
            $query = "UPDATE WebUsers SET patterns = patterns + 1, weeklyPatterns = weeklyPatterns + 1, handling = NULL WHERE user_id = $userid";
        }
        $statement = $connect->prepare($query);
        $statement->execute();
    }

    //Sets the pattern to have a rating of 1 -> It wont be used

    $query = "UPDATE Data SET Rating='1' WHERE id=$newID";
    $statement = $connect->prepare($query);
	$statement->execute();
    echo '<script>console.log("Data Set"); </script>';
    $webhook_url = "https://discordapp.com/api/webhooks/735506444383944704/yLi8FurjZcLUqqp6tCmEvo9PZLK7cjrPoCXaDxf5dW-YGd_cYpWSKsexmwC4Qm2H5trq";
    
    //Sets webhook message for discord logs

    $WebhookMessage = "The user **${userUsername}** posted a rating of **1** for the PatternID **${newID}**";

    //creates a webhook for discord to execute

    $hookObject = json_encode([
        
        "embeds" => [
            [
                "title" => "Jarvis Web Logs",
        
                "type" => "rich",
    
                "description" => $WebhookMessage,
    
                "color" => hexdec( "ff0000" ),
    
            ]
        ]
    
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    $ch = curl_init();  
    curl_setopt_array( $ch, [
        CURLOPT_URL => $webhook_url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $hookObject,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ]
    ]);
    $response = curl_exec( $ch );
    curl_close( $ch );

    $query = "SELECT ID FROM Data WHERE Blocked = 0 AND Rating = 0 ORDER BY RAND() LIMIT 1";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row)
    {
        $pattern_id_grab = $row['ID'];
    }
    header("location:https://jarvischatbot.xyz/admin/pattern/$pattern_id_grab");
}

if(isset($_POST["RateYes"]))
{
    $userid = $_SESSION['user_id'];

    $query = "SELECT * FROM WebUsers WHERE user_id = $userid";
    $statement = $connect->prepare($query);
    $statement->execute();	
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        $newID = $row["handling"];
        if ($newID != NULL) {
            echo "Stat Update!";
            $query = "UPDATE WebUsers SET patterns = patterns + 1, weeklyPatterns = weeklyPatterns + 1, handling = NULL WHERE user_id = $userid";
        }
        $statement = $connect->prepare($query);
        $statement->execute();
    }

    //Sets the pattern to have a rating of 1 -> It wont be used

    $query = "UPDATE Data SET Rating='3', Score = Score + 5 WHERE id=$newID";
    $statement = $connect->prepare($query);
	$statement->execute();
    echo '<script>console.log("Data Set"); </script>';

    $webhook_url = "https://discordapp.com/api/webhooks/735506444383944704/yLi8FurjZcLUqqp6tCmEvo9PZLK7cjrPoCXaDxf5dW-YGd_cYpWSKsexmwC4Qm2H5trq";

    //Formats the pattern into a string

    $inputFormatted = preg_replace('/[[:^print:]]/', '', $patternInput);
    $outputFormatted = preg_replace('/[[:^print:]]/', '', $patternOutput);

    //Sets webhook message for discord logs

    $WebhookMessage = "The user **${userUsername}** posted a rating of **3** for the PatternID **${newID}**";

    //creates a webhook for discord to execute

    $hookObject = json_encode([
        
        "embeds" => [
            [
                "title" => "Jarvis Web Logs",
        
                "type" => "rich",
    
                "description" => $WebhookMessage,
    
                "color" => hexdec( "#03fc0f" ),
    
            ]
        ]
    
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    $ch = curl_init();  
    curl_setopt_array( $ch, [
        CURLOPT_URL => $webhook_url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $hookObject,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ]
    ]);
    $response = curl_exec( $ch );
    curl_close( $ch );

    $query = "SELECT ID FROM Data WHERE Blocked = 0 AND Rating = 0 ORDER BY RAND() LIMIT 1";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row)
    {
        $pattern_id_grab = $row['ID'];
    }
    header("location:https://jarvischatbot.xyz/admin/pattern/$pattern_id_grab");
}

if($getPatternID == "")
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
        ping: 12px 20px 12px 40px;
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
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/reports.php">Reports</a>
                <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/jarvion.php">Patterns</a>
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
                    <h3 class="m-0 font-weight-bold text-white">Jarvis Pattern Managment</h3>
                    </div>
                    <form method="post">
                    <div class="card-body"> 
                        <h1>Welcome to Jarvis Pattern Rating!</h1>
                        <br>           
                        <h2>If you need any help during this process make sure to contact your higher up and ask any questions</h2>              
                    </div>
                    <div class="card-footer py-3">
                        <form method="post">
                            <div class="form-group">
                                <input type="submit" name="getPatternID" class="btn btn-primary btn-lg" value="Get Started" />
                            </div>
                        </form>                         
                    </div>
                    </form>   
                </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                    <h3 class="m-0 font-weight-bold text-white">Pattern Rating Help</h3>
                    </div>
                    <form method="post">
                    <div class="card-body"> 
                        <h3><font color = green>[Yes]</font> Jarvis responds in a valid way to respond to the user input</b></h3>
                        <p><b>Input: Whats the weather like</b></p>
                        <p><b>Output: Its very sunny!</b></p>
                        <h3><font color = red>[No]</font> Jarvis responds is a unrelated/not promoting conversation with the user input</b></h3>         
                        <p><b>Input: What are you doing?</b></p>
                        <p><b>Output: Yes I love their first album!</b></p>
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
    //Sql Statement
    $query = "SELECT * FROM Data WHERE ID = $getPatternID LIMIT 1";
    $statement = $connect->prepare($query);
    $statement->execute();
    $count = $statement->rowCount();
    if ($count > 0)
    {
        $result = $statement->fetchAll();
        foreach ($result as $row)
        {
            $patternID = $row["ID"];
            $patternInput = $row["Input"];
            $patternOutput = $row["Response"];
            $patternScore = $row["Score"];
            $patternCreator = $row["SenderID"];
            $patternRating = $row["Rating"];
            $patternBlocked = $row["Blocked"];
            $userID = $_SESSION['user_id'];

            if($patternCreator == NULL)
            {
                $patternCreator = "Unkown";
            }
            
            $query1 = "UPDATE WebUsers SET handling = $patternID WHERE user_id = $userID";
            $statement1 = $connect->prepare($query1);
            $statement1->execute();
            break;
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
                <a class="collapse-item" href="https://jarvischatbot.xyz/admin/reports.php">Reports</a>
                <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/jarvion.php">Patterns</a>
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

        if($patternRating > 0)
        {
            echo '
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h3 class="m-0 font-weight-bold text-white">Pattern Rated</h3>
                    </div>
                    <div class="card-body"> 
                        <h3><img src="'.$image.'" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($patternInput) .'</b> </h3>
                        <h3><img src="https://jarvischatbot.xyz/images/jarvis-new.png" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($patternOutput) .'</b> </h3>          
                    </div>
                    <div class="card-footer py-3">';

                        if($patternBlocked == 1)
                        {
                            echo '<h2 class="m-0 font-weight-bold text-red"><font color="red">Pattern Blocked</font></h2><br>';
                        }

                        echo '
                        <h3 class="m-0 font-weight-bold text-white">PatternID: '.$patternID.'</h3>
                        <h3 class="m-0 font-weight-bold text-white">Pattern Creator: '.$patternCreator.'</h3>
                        <h3 class="m-0 font-weight-bold text-white">Community Rating: '.$patternScore.'</h3>
                        <h3 class="m-0 font-weight-bold text-white">Pattern Status: Rated '.$patternRating.'</h3>
                    </div>
                    <div class="card-footer py-3">
                    <form method="post">
                            <div class="form-group">
                                <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/admin/jarvion.php">Return</a>    
                                <input type="submit" name="getPatternID" class="btn btn-primary btn-lg" value="New Pattern" />
                            </div>
                        </form>    
                    </div>
                </div>
            </div>
            ';
        }
        else
        {
            echo '
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h3 class="m-0 font-weight-bold text-white">Pattern Rated</h3>
                    </div>
                    <div class="card-body"> 
                        <h3><img src="'.$image.'" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($patternInput) .'</b> </h3>
                        <h3><img src="https://jarvischatbot.xyz/images/jarvis-new.png" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($patternOutput) .'</b> </h3>          
                    </div>
                    <div class="card-footer py-3">
                        <form method="post">
                            <div class="form-group">
                                <h2>Would this be an acceptable way for Jarvis to respond to the above pattern?</h2>
                                <button class="btn btn-primary btn-lg" name="RateYes" type="submit">Yes</button>
                                <button class="btn btn-primary btn-lg" name="RateNo" type="submit">No</button>  
                                <button class="btn btn-primary btn-lg" name="RateReport" type="submit">Report Pattern</button>        
                                <button class="btn btn-primary btn-lg" name="getPatternID" type="submit">Skip Pattern</button>                               
                            </div>
                        </form> 
                    </div>
                </div>
            ';

            echo '
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h3 class="m-0 font-weight-bold text-white">Pattern Debugging</h3>
                    </div>
                    <div class="card-body"> 
                        ';
                        if($patternRating == "0")
                        {
                            $patternRating = "N/A";
                        }
                        echo '
                        <h3 class="m-0 font-weight-bold text-white">PatternID: '.$patternID.'</h3>
                        <h3 class="m-0 font-weight-bold text-white">Pattern Creator: '.$patternCreator.'</h3>
                        <h3 class="m-0 font-weight-bold text-white">Community Rating: '.$patternScore.'</h3>
                        <h3 class="m-0 font-weight-bold text-white">Pattern Status: Rated '.$patternRating.'</h3>
                        ';
                        if($patternBlocked == 1)
                        {
                            echo '<h2 class="m-0 font-weight-bold text-red"><font color="red">Pattern Blocked</font></h2><br>';
                        }
                        echo '
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                    <h3 class="m-0 font-weight-bold text-white">Pattern Rating Help</h3>
                    </div>
                    <form method="post">
                    <div class="card-body"> 
                        <h3><font color = green>[Yes]</font> Jarvis responds in a valid way to respond to the user input</b></h3>
                        <p><b>Input: Whats the weather like</b></p>
                        <p><b>Output: Its very sunny!</b></p>
                        <h3><font color = red>[No]</font> Jarvis responds is a unrelated/not promoting conversation with the user input</b></h3>         
                        <p><b>Input: What are you doing?</b></p>
                        <p><b>Output: Yes I love their first album!</b></p>
                    </div>
                    </form>
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
    else
    {
      echo "Pattern Not Found";   
    }
}

?>