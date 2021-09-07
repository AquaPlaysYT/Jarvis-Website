<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");
error_reporting(E_ALL ^ E_NOTICE);

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

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


?>

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
  background-image: url('searchicon.png');
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
            <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/patterns.php">Patterns</a>
            <?php
            if($_SESSION['verified'] !== "[Jarvions] ")
            {
              echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/staff.php">Staff</a>';
            }
            ?>
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
            <?php
            if($_SESSION['verified'] !== "[Jarvions] ")
            {
              echo '<a class="collapse-item" href="https://jarvischatbot.xyz/admin/moodterms">Mood Terms</a>';
            }
            ?>
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
          <?php

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

          ?>
          <ul class="navbar-nav ml-auto">      
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-white-600 medium"><b><?php echo $username ?></b></span>
                <img class="img-profile rounded-circle" height="50" width="50" src="<?php echo $image ?>">
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
            <h1 class="h3 mb-0 text-white-800">Logged in as <b><?php echo $_SESSION['username'] . '#' . $_SESSION['discriminator'] ?></b></h1>
          </div>

            <div class="row">

            <div class="col-xl-4 col-lg-5">

              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-white">Search Patterns</h6>
                </div>
                <form method="post">
                <div class="card-body"> 
                  <input type="text" name="message" placeholder="Pattern ID / User ID..">     
                </div>
                <div class="card-footer py-3">
                  <input type="submit" name="search" class="btn btn-primary btn-lg" value="Inspect Pattern" /> 
                  <input type="submit" name="searchUsers" class="btn btn-primary btn-lg" value="User Patterns" /> 
                </div>
                </form>   
              </div>
            </div>
          </div>       

    <?php
    
    if(isset($_POST["search"]))
    {
    
      $searchresults = $_POST['message'];

      if($searchresults == "")
      {

      }
      else
      {
        $query2 = "SELECT * FROM Data WHERE ID = $searchresults";
        $statement2 = $connect->prepare($query2);
        $statement2->execute();
      
        if($statement2->rowCount() > 0)
        {
          $result = $statement2->fetchAll();
          foreach($result as $row)
          {
  
            $message = "";
            $patternid = $row["ID"];
		        $inputtext = $row["Input"];
	        	$outputtext = $row["Response"];
	        	$score = $row["Score"];
	        	$creator = $row["SenderID"];
            $user = $_SESSION['user_id'];
            $blocked = $row['Blocked'];

            if($blocked == "1")
            {
              $message = "Pattern Blocked!";
            }
            
            if($creator == NULL)
            {
              $icon = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png";
              $username = "UnknownUser";
              $discriminator = "";
            }
            else
            {
              $url = 'https://discordapp.com/api/users/'.$creator.'';

              $ch = curl_init();
              curl_setopt_array($ch, array(
                  CURLOPT_URL            => $url, 
                  CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.XvsYUg.80l_8S7w5UshHixuBxEUsjdThRc'), 
                  CURLOPT_RETURNTRANSFER => 1,
                  CURLOPT_FOLLOWLOCATION => 1,
                  CURLOPT_VERBOSE        => 1,
                  CURLOPT_SSL_VERIFYPEER => 0,
              ));
              $response = curl_exec($ch);
              curl_close($ch);
              $output = json_decode($response, true);
  
              $avatar = $output['avatar'];
              $username = $output['username'];
              $discriminator = "#" . $output['discriminator'];
  
              $icon = "https://cdn.discordapp.com/avatars/$creator/$avatar.png?size=64";
            }
  
            echo '
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h2 class="m-0 font-weight-bold text-white">Pattern ID: <b>'.$patternid.'</b> Creators Profile: '.$username.''.$discriminator.' <img class="avatar" width="64" heigh="64" src='.$icon.'></h2>
              </div>
              <div class="card-body"> 
                <h2><font color="#b80000"><b>'.$message.'</b></font></h2>
                <h2>Input: <b>'.strip_tags($inputtext).'</b></h2>
                <h2>Output: <b>'.strip_tags($outputtext).'</b></h2>
                <h2>Community Score: <b>'.$score.'</b></h2>
              </div>
              <div class="card-footer py-3">
              <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/admin/pattern/'.$patternid.'">View Pattern</a>
            ';

            if($blocked == "1")
            {
              echo '<input type="submit" onclick="location.href = `actions.php?unblock='.$patternid.'`" class="btn btn-primary btn-lg" value="Unblock Pattern" /> ';
            }
            else
            {
              echo '<input type="submit" onclick="location.href = `actions.php?block='.$patternid.'`" class="btn btn-primary btn-lg" value="Block Pattern" /> ';
            }

            echo '
            </div>
            </div>
            ';

            } 
        }
        else
        {
          echo '
          <h3>No Patterns Found!</h3>
          '; 
        }
      
    }}

    if(isset($_POST["searchUsers"]))
    {
    
      $searchresults = $_POST['message'];

      if($searchresults == "")
      {

      }
      else
      {

        $url = 'https://discordapp.com/api/users/'.$searchresults.'';

        $ch = curl_init();
        curl_setopt_array($ch, array(
          CURLOPT_URL            => $url, 
          CURLOPT_HTTPHEADER     => array('Authorization: Bot NjU3NzMzMDA0NTg3Njk2MTQ4.XvsYUg.80l_8S7w5UshHixuBxEUsjdThRc'), 
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_FOLLOWLOCATION => 1,
          CURLOPT_VERBOSE        => 1,
          CURLOPT_SSL_VERIFYPEER => 0,
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($response, true);

        $avatar = $output['avatar'];
        $username = $output['username'];
        $discriminator = "#" . $output['discriminator'];

        $icon = "https://cdn.discordapp.com/avatars/$searchresults/$avatar.png?size=64";

        $query2 = "SELECT * FROM Data WHERE SenderID = $searchresults ORDER BY ID DESC LIMIT 1000";
        $statement2 = $connect->prepare($query2);
        $statement2->execute();
      
        if($statement2->rowCount() > 0)
        {
          $result = $statement2->fetchAll();
          foreach($result as $row)
          {
  
            $message = "";
            $patternid = $row["ID"];
		        $inputtext = $row["Input"];
	        	$outputtext = $row["Response"];
	        	$score = $row["Score"];
	        	$creator = $row["SenderID"];
            $user = $_SESSION['user_id'];
            $blocked = $row['Blocked'];

            if($blocked == "1")
            {
              $message = "Pattern Blocked!";
            }
            
            if($creator == NULL)
            {
              $icon = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png";
              $username = "UnknownUser";
              $discriminator = "";
            }       
  
            echo '

            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h2 class="m-0 font-weight-bold text-white">Pattern ID: <b>'.$patternid.'</b> Creators Profile: '.$username.''.$discriminator.' <img class="avatar" width="64" heigh="64" src='.$icon.'></h2>
              </div>
              <div class="card-body"> 
                <h2><font color="#b80000"><b>'.$message.'</b></font></h2>
                <h2>Input: <b>'.strip_tags($inputtext).'</b></h2>
                <h2>Output: <b>'.strip_tags($outputtext).'</b></h2>
                <h2>Community Score: <b>'.$score.'</b></h2>
              </div>
              <div class="card-footer py-3">
              <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/admin/pattern/'.$patternid.'">View Pattern</a>
            ';

            if($blocked == "1")
            {
              echo '<input type="submit" onclick="location.href = `actions.php?unblock='.$patternid.'`" class="btn btn-primary btn-lg" value="Unblock Pattern" />';
            }
            else
            {
              echo '<input type="submit" onclick="location.href = `actions.php?block='.$patternid.'`" class="btn btn-primary btn-lg" value="Block Pattern" />';
            }

            echo '
            </div>
            </div>
            ';

            } 
        }
        else
        {
          echo '
          <h3>No User Patterns Found!</h3>
          '; 
        }
      
    }}

    ?>
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
      $('*[data-href]').on('click', function() {
          window.location = $(this).data("href");
      }); 
  });
  </script>

</body>

</html>