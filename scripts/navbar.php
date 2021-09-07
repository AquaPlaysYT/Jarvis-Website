<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
session_start();

echo "
<link rel='stylesheet' href='https://jarvischatbot.xyz/style/bootstrap.min.css'>
<link rel='stylesheet' href='https://jarvischatbot.xyz/style/main.css'>
<link rel='stylesheet' href='https://jarvischatbot.xyz/style/now-ui-kit.css'>
<nav id='nav' class='navbar navbar-expand-sm navbar-dark bg-dark'>
    <a href='#' class='navbar-brand'>Jarvis</a>
    <button class='navbar-toggler' data-toggle='collapse' data-target='#navbarMenu'>
        <img src='https://jarvischatbot.xyz/images/icon-toggle.png'>
    </button>
    <div class='collapse navbar-collapse' id='navbarMenu'>
        <ul class='navbar-nav'>
            <li class='nav-item' id='https://jarvischatbot.xyz/home/'>
                <a class='nav-link' href='https://jarvischatbot.xyz/home/'>Home</a>
            </li>
            <li class='nav-item' id='https://jarvischatbot.xyz/discord/'>
                <a class='nav-link' href='https://jarvischatbot.xyz/discord/'>Discord</a>
            </li>
            <li class='nav-item' id='https://jarvischatbot.xyz/leaderboard/'>
                <a class='nav-link' href='https://jarvischatbot.xyz/leaderboard/'>Leaderboard</a>
            </li>
            <li class='nav-item' id='https://jarvischatbot.xyz/stats/'>
                <a class='nav-link' href='https://jarvischatbot.xyz/stats/'>Stats</a>
            </li>
            <li class='nav-item' id='https://jarvischatbot.xyz/status/'>
                <a class='nav-link' href='https://jarvischatbot.xyz/status/'>Status</a>
            </li>
            <li class='nav-item' id='https://jarvischatbot.xyz/feedback/'>
                <a class='nav-link' href='https://jarvischatbot.xyz/feedback/'>Feedback</a>
            </li>
            <li class='nav-item' id='https://jarvischatbot.xyz/topservers/'>
                <a class='nav-link' href='https://jarvischatbot.xyz/topservers/'>Servers</a>
            </li>
            <li class='nav-item' id='https://jarvischatbot.xyz/updates/'>
                <a class='nav-link' href='https://jarvischatbot.xyz/updates/'>Changelogs</a>
            </li>
            ";   
                if(isset($_SESSION['user_id']))
                {
                    $id = $_SESSION['discord_id'];
                    $avatar = $_SESSION['avatar'];
                    
                    if($avatar == NULL)
                    {
                        $image = 'https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png'; 
                    }
                    else
                    {
                        $image = 'https://cdn.discordapp.com/avatars/'.$id.'/'.$avatar.'.png?size=64';  
                    }

                    echo "
                    <li style='align:right' class='nav-item dropdown'>
                        <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            <img alt='Discord Profile Picture' style='width:30px;height:30px;border-radius:50%;' src='".$image."'> ".$_SESSION['username']."
                        </a>
                        <div class='dropdown-menu' aria-labelledby='navbarDropdown'>
                            <a class='dropdown-item' href='https://jarvischatbot.xyz/users/".$_SESSION['discord_id']."'>View Profile</a>
                            ";

                            if($_SESSION['verified'] == '[Jarvions] ' || $_SESSION['verified'] == '[Administrator] ' || $_SESSION['verified'] == '[Developer] ')
                            {
                                echo "<a class='dropdown-item' href='https://jarvischatbot.xyz/admin'>Admin Panel</a>";     
                            }
                            
                            echo "
                            <div class='dropdown-divider'></div>
                            <a class='dropdown-item' href='https://jarvischatbot.xyz/logout.php'>Logout</a>
                        </div>
                    </li>
                    ";
                }
                else
                {
                    echo "<button onclick='window.location.href=`https://jarvischatbot.xyz/login.php?action=login`' class='btn btn-outline-secondary my-2 my-sm-0 btn-round btn-sm'>LOGIN</button>";
                }
                echo "
        </ul>
    </div>
</nav>
";

$query = "SELECT * FROM WebMessage";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
foreach($result as $row)
{
    $announcement_users = $row['users'];
}

if($announcement_users !== "")
{
    echo '
    <div class="alert">
        <center><b>Announcement:</b> '.$announcement_users.'</center>
    </div>
    ';
}

?>
