<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

if(!isset($_SESSION['user_id']))
{
  header("location:https://jarvischatbot.xyz/login.php?action=login");
}
else if($_SESSION['verified'] == "[Developer] " || $_SESSION['verified'] == "[Jarvions] ")
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

  <title>Mood Term Rating</title>

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

.slidecontainer {
  width: 100%;
}

.slider {
  -webkit-appearance: none;
  width: 50%;
  height: 30px;
  background: orange;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}

.slider:hover {
  opacity: 1;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 30px;
  background: #ffff;
  cursor: pointer;
}

.slider::-moz-range-thumb {
  width: 25px;
  height: 30px;
  background: #4CAF50;
  cursor: pointer;
}
</style>

<body id="page-top">

  <div id="wrapper">

    <ul class="navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion" id="accordionSidebar">

      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="https://jarvischatbot.xyz/admin">
        <div class="sidebar-brand-text">JADMIN</div>
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
            <?php
            if($_SESSION['verified'] !== "[Jarvions] ")
            {
              echo '<a class="collapse-item active" href="https://jarvischatbot.xyz/admin/staff.php">Staff</a>';
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
                  <h4 class="m-0 font-weight-bold text-white">Mood Term Classification</h4>
                </div>
                <div class="card-body"> 
                  <?php
                    

                    $query1 = "(SELECT * FROM MoodBank WHERE Score IS NULL ORDER BY Priority DESC LIMIT 20) ORDER BY RAND()";
                    $statement1 = $connect->prepare($query1);
                    $statement1->execute();
                    $result1 = $statement1->fetchAll();
                    foreach ($result1 as $row)
                    {
                        $response = $row['Word'];
					              $priority = $row['Priority'];
                    }
                      
                    echo '<h3><img src="https://jarvischatbot.xyz/images/jarvis-new.png" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.strip_tags($response) .'</b> </h3>';
                    
                  ?>
                </div>
                <div class="card-footer py-3">
                  <div class="slidecontainer">
                      <form method="post" action="submit.php">
                        <input type="range" min="-1" max="1" step="0.000001" name="moodValue" class="slider" id="myRange"><br>
                        <input type="hidden" name="wordid" value="<?php echo $response ?>">
                        <button class="btn btn-primary btn-lg" name="moodSubmit" type="submit">Submit Mood</button>    
                        <a href="https://jarvischatbot.xyz/admin/moodterms/" class="btn btn-primary btn-lg">Skip Mood</a>              
                      </form>
                  </div>
                  <br>
                  <h2>Live Value: <span id="demo"></span></h2>        
                  <?php 
                  echo '<h2>Priority: '.$priority.'</h2>';
                  ?>
                  <h2>Mood: <span id="mood"></span></h2>
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
      $('*[data-href]').on('click', function() {
          window.location = $(this).data("href");
      }); 
  });
  </script>

  <script>
  var slider = document.getElementById("myRange");
  var output = document.getElementById("demo");
  var mood = document.getElementById("mood");

  output.innerHTML = slider.value;
  mood.innerHTML = "Unkown";

  slider.oninput = function() {
    output.innerHTML = this.value;
    if (slider.value > -1 && slider.value < -0.2) {
      console.log("between -1 and -0.2");
      slider.style.background="red";
      mood.innerHTML = "Negative";
    }
    else if (slider.value > -0.2 && slider.value < 0.2) {
      console.log("between -0.2 and 0.2");
      slider.style.background="orange";
      mood.innerHTML = "Neutral";
    }
    else if (slider.value > 0.2 && slider.value < 1) {
      console.log("between 0.2 and 1");
      slider.style.background="green";
      mood.innerHTML = "Positive";
    }
  }
  </script>

</body>

</html>