<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");
error_reporting(E_ALL ^ E_NOTICE);

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];
$userid = $_REQUEST['id'];

//

if(!isset($_SESSION['user_id']))
{
  header("location:https://jarvischatbot.xyz/login.php?action=login");
}

if($userid !== NULL)
{
    if($userid == $_SESSION['discord_id'] || $_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
    
    }
    else
    {
      header("location:https://jarvischatbot.xyz");
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
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    
    </head>
    
    <style>
    input[type=text] {
      width: 330px;
      box-sizing: border-box;
      border: 2px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
      background-color: white;
      background-image: url(`searchicon.png`);
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
                <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/staff.php">Staff</a>
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
    
            ';

            if(!isset($_GET['page']))
            {
                $page = 1;
            }
            else
            {
                $page = (int)$_GET['page'];
            }

            $query = "SELECT * FROM StaffQuotas WHERE DiscordID = $userid ORDER BY Date DESC";
            $result = mysqli_query($connect1, $query);
            $chart_data1 = '';
            $chart_data2 = '';
            while($row = mysqli_fetch_array($result))
            {
                $chart_data1 .= "{ Date:'".$row["Date"]."', Reports:".$row["Reports"].", Patterns:".$row["Patterns"]."}, ";
                $chart_data2 .= "{ Date:'".$row["Date"]."', newTotalReports:".$row["newTotalReports"].", newTotalPatterns:".$row["newTotalPatterns"]."}, ";
            }
            $chart_data1 = substr($chart_data1, 0, -2);
            $chart_data2 = substr($chart_data2, 0, -2);
    
            $query2 = "SELECT * FROM WebUsers WHERE discord_id = $userid";
            $statement2 = $connect->prepare($query2);
            $statement2->execute();
            
            if($statement2->rowCount() > 0)
            {
                $result = $statement2->fetchAll();
                foreach($result as $row)
                {
    
                    $message = "";
                    $username = $row['username'];
                    $discordid = $row['discord_id'];
                    $avatar = $row['avatar'];
                    $VerifiedCheck = $row['isVerified'];
                    $role = $row['verified'];
                    $color = $row['rolecolor'];
                    $patterns = $row['patterns'];
                    $totalReports = $row['weeklyReports'];
                    $totalPatterns = $row['weeklyPatterns'];
    
                    $icon = "https://cdn.discordapp.com/avatars/$discordid/$avatar.png?size=64";
                    
                    if($VerifiedCheck == true)
                    {
                        $VerifiedIcon = "https://cdn.discordapp.com/attachments/612761252875337739/699324040221032519/verified.png";
                    }
                    else
                    {
                        $VerifiedIcon = "";
                    }
                    if($avatar == NULL)
                    {
                        $icon = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
                    }
                       
                    echo '
                        <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <h2 class="m-0 font-weight-bold text-white"><img class="avatar" src="'.$icon.'"> '.$username.' <img class="icon" onerror="this.style.display=`none`" src="'.$VerifiedIcon.'"></h2>
                        </div>
                        <div class="card-body"> 
                        <h2 class="id">Rank: <font color="'.$color.'"><b>'.$role.'</b></font></h2>
                        <h2>Total Patterns: <b>'.$patterns.'</b></h2>
                        <h2 class="id">Discord ID: <b>'.$discordid.'</b></h2>
                        </div>
                        <div class="card-footer py-3">            
                    ';
    
                    echo '<b><input type="submit" onclick="location.href = `https://jarvischatbot.xyz/admin/staff.php`" class="btn btn-primary btn-lg" value="Return" /></b> ';
    
                    echo '
                        </div>
                    </div>  
                    ';

                    echo '
                        <div class="row">

                        <div class="col-xl-4 col-lg-5">

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h2 class="m-0 font-weight-bold text-white">Graph Display</h2>
                            </div>
                            <div class="card-body"> 
                                <div id="data"></div>
                                <div id="totaldata"></div>
                            </div>
                        </div>  
                        </div>
                    ';

                    $query2 = "SELECT * FROM StaffQuotas WHERE DiscordID = $userid ORDER BY Date DESC LIMIT 1";
                    $statement2 = $connect->prepare($query2);
                    $statement2->execute();
                    $result = $statement2->fetchAll();
                    foreach($result as $row)
                    {
                        $date = $row['Date'];
                        $UserReports = $row['Reports'];
                        $UserPatterns = $row['Patterns'];
                        $hitReportsTarget = $row['hitReportsTarget'];
                        $hitPatternsTarget = $row['hitPatternsTarget'];
                        if($hitReportsTarget == 1)
                        {
                            $hitReportsTarget = "Target Reached";
                            $hitReportsTargetColor = "09FF00";
                        }
                        else
                        {
                            $hitReportsTarget = "Target Not Reached";
                            $hitReportsTargetColor = "FF0000";
                        }
                        if($hitPatternsTarget == 1)
                        {
                            $hitPatternsTarget = "Target Reached";
                            $hitPatternsTargetColor = "09FF00";
                        }
                        else
                        {
                            $hitPatternsTarget = "Target Not Reached";
                            $hitPatternsTargetColor = "FF0000";
                        }

                        echo '
                        <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h2 class="m-0 font-weight-bold text-white">Last Week</h2>
                            </div>
                            <div class="card-body"> 
                                <h2>Week#<b>'.$date.'</b></h2>
                                <h2>Reports: <b>'.$UserReports.'</b></h2>
                                <h2>Patterns: <b>'.$UserPatterns.'</b><h2>
                                <h2>Report Target: <font color="'.$hitReportsTargetColor.'"><b>'.$hitReportsTarget.'</b></font></h2>
                                <h2>Pattern Target: <font color="'.$hitPatternsTargetColor.'"><b>'.$hitPatternsTarget.'</b></font></h2>
                            </div>
                        </div>                         
                        ';

                        $query3 = "SELECT * FROM BotData";
                        $statement3 = $connect->prepare($query3);
                        $statement3->execute();
                        $result = $statement3->fetchAll();
                        foreach($result as $row)
                        {
                           $PatternsQuota = $row['PatternsQuota'];
                           $ReportsQuota = $row['ReportsQuota'];
                        }

                        if($totalPatterns >= $PatternsQuota)
                        {
                          $hitPatternTargetCurrent = "Target Reached";
                          $hitPatternTargetColorCurrent = "09FF00";
                        }
                        else
                        {
                          $hitPatternTargetCurrent = "Target Not Reached";
                          $hitPatternTargetColorCurrent = "FF0000";
                        }
                        if($totalReports >= $ReportsQuota)
                        {
                          $hitReportsTargetCurrent = "Target Reached";
                          $hitReportsTargetColorCurrent = "09FF00";
                        }
                        else
                        {
                          $hitReportsTargetCurrent = "Target Not Reached";
                          $hitReportsTargetColorCurrent = "FF0000";
                        }

                        echo '
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h2 class="m-0 font-weight-bold text-white">This Week</h2>
                            </div>
                            <div class="card-body">                          
                                <h2>Reports: <b>'.$totalReports.'</b></h2>
                                <h2>Patterns: <b>'.$totalPatterns.'</b><h2>
                                <h2>Report Target: <font color="'.$hitReportsTargetColorCurrent.'"><b>'.$hitReportsTargetCurrent.'</b></font></h2>
                                <h2>Pattern Target: <font color="'.$hitPatternTargetColorCurrent.'"><b>'.$hitPatternTargetCurrent.'</b></font></h2>
                            </div>
                        </div>  
                        </div>
                        ';
                    }
                } 
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
      jQuery(document).ready(function($) {
          $("*[data-href]").on("click", function() {
              window.location = $(this).data("href");
          }); 
      });
      </script>

      <script>
      Morris.Line({
        element : "data",
        data:['.$chart_data1.'],
        xkey:"Date",
        ykeys:["Reports", "Patterns"],
        labels:["Reports", "Patterns"],
        hideHover:"auto",
        stacked:true,
        resize:true,
        pointStrokeColors:"#"
       });
            document.addEventListener("DOMContentLoaded", function(event) {
            document.querySelectorAll("img").forEach(function(img){
            img.onerror = function(){this.style.display="none";};
        })
       });
       
       Morris.Line({
        element : "totaldata",
        data:['.$chart_data2.'],
        xkey:"Date",
        ykeys:["newTotalReports", "newTotalPatterns"],
        labels:["Total Reports", "Total Patterns"],
        hideHover:"auto",
        stacked:true,
        resize:true,
        pointStrokeColors:"#"
       });
            document.addEventListener("DOMContentLoaded", function(event) {
            document.querySelectorAll("img").forEach(function(img){
            img.onerror = function(){this.style.display="none";};
        })
       });
      </script>
    
    </body>
    
    </html>

    ';
}
else 
{
    if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
    
    }
    else
    {
      header("location:https://jarvischatbot.xyz");
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
      width: 330px;
      box-sizing: border-box;
      border: 2px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
      background-color: white;
      background-image: url(`searchicon.png`);
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
                <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/staff.php">Staff</a>
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
    
            ';
    
            $query2 = "SELECT * FROM WebUsers WHERE verified = '[Jarvions] ' OR verified = '[Administrator] ' OR verified = '[Developer] ' ORDER BY isVerified DESC";
            $statement2 = $connect->prepare($query2);
            $statement2->execute();
            
            if($statement2->rowCount() > 0)
            {
                $result = $statement2->fetchAll();
                foreach($result as $row)
                {
    
                    $message = "";
                    $username = $row['username'];
                    $discordid = $row['discord_id'];
                    $avatar = $row['avatar'];
                    $VerifiedCheck = $row['isVerified'];
                    $role = $row['verified'];
                    $color = $row['rolecolor'];
                    $patterns = $row['patterns'];
                    $reports = $row['weeklyReports'];
    
                    $icon = "https://cdn.discordapp.com/avatars/$discordid/$avatar.png?size=64";
                    
                    if($VerifiedCheck == true)
                    {
                        $VerifiedIcon = "https://cdn.discordapp.com/attachments/612761252875337739/699324040221032519/verified.png";
                    }
                    else
                    {
                        $VerifiedIcon = "";
                    }
                    if($avatar == NULL)
                    {
                        $icon = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
                    }
                    if($role == "")
                    {
                        $role = "Member";
                    }
    
                    echo '
                        <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <h2 class="m-0 font-weight-bold text-white"><img class="avatar" src="'.$icon.'"> '.$username.' <img class="icon" onerror="this.style.display=`none`" src="'.$VerifiedIcon.'"></h2>
                        </div>
                        <div class="card-body"> 
                        <h2><font color="#b80000">'.$banmessage.'</font></h2>
                        <h2>Rank: <font color="'.$color.'">'.$role.'</font></h2>
                        <h2>Patterns: '.$patterns.'</h2>
                        <h2>Weekly Reports: '.$reports.'</h2>
                        <h2 class="id">Discord ID: <a href="https://jarvischatbot.xyz/users/'.$discordid.'"><b>'.$discordid.'</b></a></h2>  
                        </div>
                        <div class="card-footer py-3">            
                    ';
    
                    echo '<b><input type="submit" onclick="location.href = `https://jarvischatbot.xyz/admin/staff/'.$discordid.'`" class="btn btn-primary btn-lg" value="View User" /></b> ';
    
                    echo '
                        </div>
                    </div>  
                    ';
    
                } 
            }      
    
            echo '
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

?>