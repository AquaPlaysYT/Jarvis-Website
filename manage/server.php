<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/navbar.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
maintenance();
error_reporting(E_ALL ^ E_NOTICE);

$guilds = $_SESSION['guilds'];
$server_id = $_REQUEST['id'];

if(isset($_SESSION['user_id']))
{
  
}
else
{
  header('location:https://jarvischatbot.xyz/login.php?action=login');
}

if($guilds == NULL)
{
    header("location:https://jarvischatbot.xyz/manage");
}
else
{
    $arrlength = sizeof($guilds);

    for($i = 0; $i < $arrlength; $i++) {

        if(($guilds[$i]->permissions & 0x8) == 0x8)
        {
            if($guilds[$i]->id == $server_id)
            {
                $url = 'https://discordapp.com/api/guilds/'.$server_id.'';

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

                if($output['code'] == "50001")
                {
                    header("location:https://jarvischatbot.xyz/manage");
                }
                else
                {
                    $serverid = $guilds[$i]->id;
                    $servericon = $guilds[$i]->icon;
                    $servername = $guilds[$i]->name;
           
                    if($servericon == NULL)
                    {
                        $image = "https://discordapp.com/assets/322c936a8c8be1b803cd94861bdfa868.png"; 
                    }
                    else
                    {
                        $image = "https://cdn.discordapp.com/icons/$serverid/$servericon.png?size=256";
                    }

                    echo '                    
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
                
                    <div class="features">
                        
                    </div>
                    </div>
                    </div>     
                    </div>
                    </div>
                    <div class="footer">
                        Notice:<a href="https://jarvischatbot.xyz/privacy.pdf"> Jarvis Privacy Statement</a>
	                    <a href="//www.dmca.com/Protection/Status.aspx?ID=6c263011-55c1-474d-ae21-479608bfb330" title="DMCA.com Protection Status" class="dmca-badge"> <img src ="https://images.dmca.com/Badges/dmca_protected_sml_120m.png?ID=6c263011-55c1-474d-ae21-479608bfb330"  alt="DMCA.com Protection Status" /></a>  <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
                    </div>
                    <br>
                    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
                    <script src="https://jarvischatbot.xyz/style/js/now-ui-kit.min.js"></script>
                    <script src="https://jarvischatbot.xyz/scripts/navbar_load.js"></script>
                    <center>
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle"
                        style="display:inline-block;width:728px;height:90px"
                        data-ad-client="ca-pub-2972734010560512"
                        data-ad-slot="1079864640"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    </center>
                    </body>
                    </html>
                    ';
                }
            }
        }
    }
} 

?>