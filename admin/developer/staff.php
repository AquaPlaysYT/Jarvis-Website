<?php
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

if(!isset($_SESSION['user_id']))
{
  header("location:https://jarvischatbot.xyz/login.php?action=login");
}
else if($_SESSION['verified'] == "[Developer] ")
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
	<script data-ad-client="ca-pub-2972734010560512" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/bootstrap.min.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/admin_main_2.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/now-ui-kit.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    <title>JarvisTheChatbot</title>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a href="#" class="navbar-brand">Jarvis Developer</a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
        <img src="https://jarvischatbot.xyz/images/icon-toggle.png">
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/admin/developer">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="https://jarvischatbot.xyz/admin/developer/staff.php">Staff <span class="sr-only">(current)</span></a>
            </li>  
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/admin">Return</a>
            </li>
            <?php                  
                
                $id = $_SESSION['discord_id'];
                $avatar = $_SESSION['avatar'];
                    
                if($avatar == NULL)
                {
                    $image = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png"; 
                }
                else
                {
                    $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=64";  
                }
                echo '
                <li style="align:right" class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img alt="Discord Profile Picture" style="width:30px;height:30px;border-radius:50%;" class="avatar" src="'.$image.'"> '.$_SESSION['username'].'
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="https://jarvischatbot.xyz/admin/settings.php">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="https://jarvischatbot.xyz/logout.php">Logout</a>
                    </div>
                </li>
                ';
                
            ?>
        </ul>
    </div>
</nav>

<div class="features">
<div class="title">Staff Control Panel</div>
<div class="cards">
    <div class="container p-3 my-3 bg-dark text-white">
        <h3>Here you can view all staff message logs!</h3>
        <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/admin/developer/logs.php" role="button">View Logs</a>
    </div>
    <?php
    $query2 = "SELECT * FROM WebUsers WHERE verified = '[Developer] ' OR verified = '[Administrator] ' OR verified = '[Moderator] ' OR verified = '[Helper] '";
    $statement2 = $connect->prepare($query2);
    $statement2->execute();
        
    if($statement2->rowCount() > 0)
    {
        $result = $statement2->fetchAll();
        foreach($result as $row)
        {
            $username = $row['username'];
            $discordid = $row['discord_id'];
            $patters = $row['patterns'];
            $avatar = $row['avatar'];
            $role = $row['verified'];
            $color = $row['rolecolor'];
            $reports = $row['weeklyReports'];

            if($reports >=50)
            {
                $reportcolor = "09FF00";
                $reportcheck = "reached the minimum required reports!";
            }
            else
            {
                $reportcolor = "FF0000";
                $reportcheck = "not reached the minimum required reports!";
            }

            $icon = "https://cdn.discordapp.com/avatars/$discordid/$avatar.png?size=64";
                
            if($avatar == "")
            {
              $icon = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
            }
                   
            echo '
            <div class="container p-3 my-3 bg-dark text-white">
            <h1><img class="avatar" src="'.$icon.'"> '.$username.'</h1>
            <hr style="height:2px;border-width:0;color:white;background-color:white">
            <h2><font color="#'.$reportcolor.'">This user has '.$reportcheck.'</font><b> '.$reports.'</b></h2>
            <hr style="height:2px;border-width:0;color:white;background-color:white">
            <h2 class="id">Rank: <font color="'.$color.'">'.$role.'</font></h2>
            <h2>Total Patterns: '.$patters.'</h2>
            <h2 class="id">Discord ID: <b>'.$discordid.'</b></h2>
            <hr style="height:2px;border-width:0;color:white;background-color:white">
            ';

            echo '<input type="submit" onclick="location.href = `https://jarvischatbot.xyz/admin/actions.php?demotestaff='.$discordid.'`" class="btn btn-primary btn-lg" value="Demote Staff" />';
            
            echo '
            </div>
            ';
        }
    }

    ?>
</div>
<div class="footer">
    Notice:<a href="https://jarvischatbot.xyz/privacy.pdf"> Jarvis Privacy Statement</a>
	<a href="//www.dmca.com/Protection/Status.aspx?ID=6c263011-55c1-474d-ae21-479608bfb330" title="DMCA.com Protection Status" class="dmca-badge"> <img src ="https://images.dmca.com/Badges/dmca_protected_sml_120m.png?ID=6c263011-55c1-474d-ae21-479608bfb330"  alt="DMCA.com Protection Status" /></a>  <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://jarvischatbot.xyz/scripts/now-ui-kit.min.js"></script>
</body>
</html>
