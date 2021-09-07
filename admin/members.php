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
            <a class="collapse-item active" href="https://jarvischatbot.xyz/admin/members.php">Members</a>
            <a class="collapse-item" href="https://jarvischatbot.xyz/admin/patterns.php">Patterns</a>
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
                  <h6 class="m-0 font-weight-bold text-white">Search Members</h6>
                </div>
                <form method="post">
                <div class="card-body"> 
                  <input type="text" name="message" placeholder="Member Name..">     
                </div>
                <div class="card-footer py-3">
                  <input type="submit" name="searchBot" class="btn btn-primary btn-lg" value="Bot Users" />
                  <input type="submit" name="searchWeb" class="btn btn-primary btn-lg" value="Web Users" />  
                </div>
                </form>   
              </div>
            </div>
          </div>       

  <?php
    
    if(isset($_POST["searchBot"]))
    {
      function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
      }

      $searchresults = $_POST['message'];

      if($searchresults == "")
      {

      }
      else
      {
        $query2 = "SELECT * FROM UserData WHERE UPPER(User) LIKE UPPER('%$searchresults%') OR UserID LIKE '$searchresults' ORDER BY xp DESC";
        $statement2 = $connect->prepare($query2);
        $statement2->execute();
      
        if($statement2->rowCount() > 0)
        {
          $result = $statement2->fetchAll();
          foreach($result as $row)
          {
  
            $message = "";
            $username = $row['User'];
            $discordid = $row['UserID'];
            $xp = $row['xp'];
            $lastMessage = $row['LatestMessage'];    
            $lastMessageTS = $row['MessageTimestamp'];
            if($lastMessageTS !== NULL)
            {
              $TimeDate = date("Y-m-d H:i:s", substr($lastMessageTS, 0, 10));
              $OutputTime = time_elapsed_string($TimeDate); 
            }
            else
            {
              $OutputTime = "Unkown";
            }
          
            $icon = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
                 
            $query3 = "SELECT * FROM WebUsers WHERE discord_id = $discordid";
            $statement3 = $connect->prepare($query3);
            $statement3->execute();
            if($statement3->rowCount() > 0)
            {
              $result2 = $statement3->fetchAll();
              foreach($result2 as $row2)
              { 
                $avatar = $row2['avatar'];

                $icon = "https://cdn.discordapp.com/avatars/$discordid/$avatar.png?size=64";        

              }
            }

            echo '
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h2 class="m-0 font-weight-bold text-white"><img class="avatar" src="'.$icon.'"> '.$username.'</h2>
              </div>
              <div class="card-body"> 
                <h2>Last Active: <b>'.$OutputTime.'</b></h2>
                <h2>Last Message: <b>'.$lastMessage.'</b></h2>
                <h2>User XP: <b>'.$xp.'</b></h2>
                <h2 class="id">Discord ID: <b>'.$discordid.'</b></h2>
                </div>
              <div class="card-footer py-3">
                <b><input type="submit" onclick="location.href = `actions.php?deleteuser='.$discordid.'`" class="btn btn-primary btn-lg" value="Delete User" /></b>        
              </div>
            </div>
            ';

            } 
        }
        else
        {
          echo '
          <h3>No Users Found!</h3>
          '; 
        }
      
    }}

    if(isset($_POST["searchWeb"]))
    {
    
      $searchresults = $_POST['message'];

      if($searchresults == "")
      {

      }
      else
      {
        $query2 = "SELECT * FROM WebUsers WHERE UPPER(username) LIKE UPPER('%$searchresults%') OR discord_id LIKE '$searchresults' ORDER BY isVerified DESC";
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
            $bancheck = $row['isBanned'];

            $icon = "https://cdn.discordapp.com/avatars/$discordid/$avatar.png?size=64";
            
            if($VerifiedCheck == true)
            {
              $VerifiedIcon = "https://cdn.discordapp.com/attachments/612761252875337739/699324040221032519/verified.png";
            }
            else if($role == "[Jarvions] ")
            {
              $VerifiedIcon = "https://cdn.discordapp.com/attachments/612761252875337739/701837370432946239/icon-2-c.png";
            }
            else
            {
              $VerifiedIcon = "";
            }
  
            if($avatar == "")
            {
              $icon = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
            }
            else
            {
  
            }

            if($bancheck == "1")
            {
              $banmessage = "User Blacklisted";
            }
            else
            {
              $banmessage = "";
            }

            if($role == "")
            {
              $role = "Member";
            }
  
            echo '

            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h2 class="m-0 font-weight-bold text-white"><img class="avatar" src="'.$icon.'"> '.$username.' <img class="icon" src="'.$VerifiedIcon.'"></h2>
              </div>
              <div class="card-body"> 
                <h2><font color="#b80000">'.$banmessage.'</font></h2>
                <h2>Rank: <font color="'.$color.'">'.$role.'</font></h2>
                <h2 class="id">Discord ID: <a href="https://jarvischatbot.xyz/users/'.$discordid.'"><b>'.$discordid.'</b></a></h2>  
              </div>
              <div class="card-footer py-3">            
            ';

            if($bancheck == "1")
            {
              echo '<input type="submit" onclick="location.href = `actions.php?unbanuserid='.$discordid.'`" class="btn btn-primary btn-lg" value="Unblacklist User" /> ';
            }
            else
            {
              echo '<input type="submit" onclick="location.href = `actions.php?banuserid='.$discordid.'`" class="btn btn-primary btn-lg" value="Blacklist User" /> ';
            }

            if($VerifiedCheck == "1")
            {
              echo '<input type="submit" onclick="location.href = `actions.php?unverifyuser='.$discordid.'`" class="btn btn-primary btn-lg" value="Unverify User" /> ';
            }
            else
            {
              echo '<input type="submit" onclick="location.href = `actions.php?verifyuser='.$discordid.'`" class="btn btn-primary btn-lg" value="Verify User" /> ';
            }

            if($role == "[Jarvions] ")
            {
              echo '<input type="submit" onclick="location.href = `actions.php?depromoteuser='.$discordid.'`" class="btn btn-primary btn-lg" value="Demote Jarvion" /> ';
            }
            else
            {
              echo '<input type="submit" onclick="location.href = `actions.php?promoteuser='.$discordid.'`" class="btn btn-primary btn-lg" value="Promote Jarvion" /> ';
            }

            echo '<b><input type="submit" onclick="location.href = `actions.php?deleteuser='.$discordid.'`" class="btn btn-primary btn-lg" value="Delete User" /></b> ';

            echo '
              </div>
            </div>  
            ';

            } 
        }
        else
        {
          echo '
          <br>
          <h3>No Users Found!</h3>
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