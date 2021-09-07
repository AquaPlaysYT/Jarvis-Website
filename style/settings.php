<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
maintenance();
session_start();
error_reporting(E_ALL ^ E_NOTICE);

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

if(isset($_SESSION['user_id']))
{
  
}
else
{
  header('location:https://jarvischatbot.xyz/login.php?action=login');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script data-ad-client="ca-pub-2972734010560512" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/bootstrap.min.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/main.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/now-ui-kit.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    <title>JarvisTheChatbot</title>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a href="#" class="navbar-brand">Jarvis</a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
        <img src="images/icon-toggle.png">
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://discord.gg/ctHR9MT">Discord</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/leaderboard">Leaderboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/stats">Stats</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/feedback.php">Feedback</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/topservers">Servers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://jarvischatbot.xyz/updates">Changelogs</a>
            </li>
            <?php                  
                if(isset($_SESSION['user_id']))
                {
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
                            <img alt="Discord Profile Picture" style="width:30px;height:30px;border-radius:50%;" src="'.$image.'"> '.$_SESSION['username'].'
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="https://jarvischatbot.xyz/users/'.$_SESSION["discord_id"].'">View Profile</a>
                            ';

                            if($_SESSION['verified'] == "[Jarvions] ")
                            {
                                echo '<a class="dropdown-item" href="https://jarvischatbot.xyz/admin">Admin Panel</a>';
                                echo '<a class="dropdown-item" href="https://jarvischatbot.xyz/Jarvion.php">Jarvion Panel</a>';
                            }
                            else if($_SESSION['verified'] == "[Administrator] ")
                            {
                                echo '<a class="dropdown-item" href="https://jarvischatbot.xyz/admin">Admin Panel</a>';
                                echo '<a class="dropdown-item" href="https://jarvischatbot.xyz/Jarvion.php">Jarvion Panel</a>';
                            }
                            else if($_SESSION['verified'] == "[Developer] ")
                            {
                                echo '<a class="dropdown-item" href="https://jarvischatbot.xyz/admin">Admin Panel</a>';
                                echo '<a class="dropdown-item" href="https://jarvischatbot.xyz/Jarvion.php">Jarvion Panel</a>';
                            }
 
                            
                            echo '<a class="dropdown-item" href="https://jarvischatbot.xyz/user.php">User Panel</a>';

                            echo '
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="https://jarvischatbot.xyz/logout.php">Logout</a>
                        </div>
                    </li>
                    ';
                }
                else
                {
                    echo '<button onclick="window.location.href=`https://jarvischatbot.xyz/login.php?action=login`" class="btn btn-outline-secondary my-2 my-sm-0 btn-round btn-sm">LOGIN</button>';
                }
            ?>
        </ul>
    </div>
</nav>
<div class="features">
    <div class="title"><img alt="Discord Profile Picture" style="width:40px;height:40px;border-radius:50%;" src="<?php echo $image ?>"> <?php echo $_SESSION['username'] ?> - Servers</div>
    <div class="container p-3 my-3 bg-dark text-white rounded">

    <?php

        $guilds = $_SESSION['guilds'];

        if($guilds == "")
        {
            
        }
        else
        {
            $arrlength = sizeof($guilds);

            for($i = 0; $i < $arrlength; $i++) {

                if(($guilds[$i]->permissions & 0x8) == 0x8)
                {
                    $url = 'https://discordapp.com/api/guilds/'.$guilds[$i]->id.'';

                    $ch = curl_init();
                    curl_setopt_array($ch, array(
                        CURLOPT_URL            => $url, 
                        CURLOPT_HTTPHEADER     => array('Authorization: Bot NjMyNTkwMjEwNjcyMzYxNTAy.XwzIrA.wlMPMDt7zTzWmWZmKFa4xLk8LwQ'), 
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_FOLLOWLOCATION => 1,
                        CURLOPT_VERBOSE        => 1,
                        CURLOPT_SSL_VERIFYPEER => 0,
                    ));
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $output = json_decode($response, true);

                    
                    // For tmr you need to add the main server part eg jarvischatbot.xyz/servers/id

                    if($output['code'] == "50001")
                    {
                        //Jarvis is not in the server
                    }
                    else
                    { 
                        $serverid = $guilds[$i]->id;
                        $servericon = $guilds[$i]->icon;

                        $query3 = "SELECT * FROM Servers WHERE ID = '$serverid'";
                        $statement3 = $connect->prepare($query3);
                        $statement3->execute();
                        $count1 = $statement3->rowCount();
                        $result1 = $statement3->fetchAll();
                        foreach($result1 as $row)
                        {
                            $VerifiedCheck = $row['Verified'];
                        
                            if($VerifiedCheck == true)
                            {
                                $Verified = "https://cdn.discordapp.com/attachments/612761252875337739/699324040221032519/verified.png";
                            }
                            else
                            {
                                $Verified = "";
                            }
                        }

                        if($servericon == NULL)
                        {
                            $image = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png"; 
                        }
                        else
                        {
                            $image = "https://cdn.discordapp.com/icons/$serverid/$servericon.png?size=64";
                        }

                        echo '

                        <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="'.$image.'" alt="Card Discord image">
                        <div class="card-body">
                            <h5 class="card-title"><img style="width: 40px; height: 40px" src='.$Verified.'> '.$guilds[$i]->name.'</h5>
                            <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/servers/'.$serverid.'" role="button">Dashboard</a>
                        </div>
                        </div>
                        <br>
                        ';
                    }
                }
                else
                {
            
                }
            }
        } 

    ?>

</div>

        
    </div>
</div>
<div class="footer">
    Notice:<a href="https://jarvischatbot.xyz/privacy.pdf"> Jarvis Privacy Statement</a>
</div>
<br>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://jarvischatbot.xyz/style/js/now-ui-kit.min.js"></script>
</body>
</html>
