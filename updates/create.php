<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/navbar.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
maintenance();

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

if(!$_SESSION['verified'] == "[Developer] ")
{
    header("location:https://jarvischatbot.xyz/updates"); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script data-ad-client="ca-pub-2972734010560512" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:description"  content="View all recent updates that have been made on the website as well as the bot!"/>
    <meta property="og:title" content="Jarvis Updates">
    <meta property="og:type" content="website" />
    <meta name="theme-color" content="#0357ff">
    <meta property="og:image" content="https://jarvischatbot.xyz/images/jarvis_new_new.png" />
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/bootstrap.min.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/main.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/now-ui-kit.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    <title>JarvisTheChatbot</title>
</head>
<body>
<div class="features">
    <div class="title">Create New Updates Logs!</div>
        <?php
            if(isset($_POST["create"]))
            {
                if(!$_SESSION['verified'] == "[Developer] ")
                {
                    header("location:https://jarvischatbot.xyz/updates"); 
                }
                
                $title = $_REQUEST['title'];
                $body = $_REQUEST['body'];
                $creator = $_SESSION['discord_id'];
                $author = $_SESSION['username'];
                $timestamp = date('Y-m-d H:i:s');

                $title_format = str_replace("'", "\'", $title);
                $body_format = str_replace("'", "\'", $body);
                $body_format = str_replace("\n", "<br>", $body_format);

                $query = "INSERT INTO News (CreatorID, Title, Body, Timestamp, Author) VALUES ($creator, '$title_format', '$body_format', '$timestamp', '$author')";

                $statement = $connect->prepare($query);
                $statement->execute(); 

                $query2 = "SELECT * FROM News WHERE Title = '$title'";
                $statement2 = $connect->prepare($query2);
                $statement2->execute();    
                $result = $statement2->fetchAll();
                foreach($result as $row)
                {        
                    $ID = $row['ID'];
                }

                $url = "https://discordapp.com/api/webhooks/709744429165969490/AdTQdTiV53Fil0n8fI8nztn6uAqVjCoT2J77bTLligl6L9MccSeIB8zE__R43tp37xlh";

                $limited_body = mb_strimwidth($body, 0, 50, "...");

                $WebhookMessage = $limited_body . " [Read More](https://jarvischatbot.xyz/updates/$ID)";

                $hookObject = json_encode([
                    
                    "embeds" => [
                        [
                            "url" => "https://jarvischatbot.xyz/updates/",

                            "title" => "$title",
                    
                            "type" => "rich",
                
                            "description" => $WebhookMessage,
                
                            "color" => hexdec( "ff0000" ),

                            "footer" => [
                                "text" => $ID,
                            ],
                
                        ]
                    ]
                
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
                
                $ch = curl_init();

                //Sets headers
                
                curl_setopt_array( $ch, [
                    CURLOPT_URL => $url,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $hookObject,
                    CURLOPT_HTTPHEADER => [
                        "Content-Type: application/json"
                    ]
                ]);

                //Executes the webhook
                
                $response = curl_exec( $ch );
                curl_close( $ch );

                header("location:https://jarvischatbot.xyz/updates/");

            }
        ?>
        <form method="post">
            <div class="form-group">
                <p>Title</p>
                <textarea name="title" rows="1" cols="70"></textarea><br>
                <p>Body</p>
                <textarea name="body" rows="5" cols="70"></textarea><br>
                <button class="btn btn-secondary btn-lg" name="create" type="submit">Create Post</button>
	        </div>
        </form>
        <a class="btn btn-secondary btn-lg" href="https://jarvischatbot.xyz/updates/" role="button">Back</a>';
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
