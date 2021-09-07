<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/navbar.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
maintenance();
error_reporting(E_ALL ^ E_NOTICE);

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

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
    <div class="title">Recent Updates Logs!</div>
    
    <?php

        if($_SESSION['verified'] == "[Developer] ")
        {
           echo '<a class="btn btn-secondary btn-lg" href="https://jarvischatbot.xyz/updates/create.php" role="button">Create Post</a>';
        }

        $query2 = "SELECT * FROM News ORDER BY Timestamp DESC";
        $statement2 = $connect->prepare($query2);
        $statement2->execute();
        $rank = 1;
        if($statement2->rowCount() > 0)
        {
            $result = $statement2->fetchAll();
            foreach($result as $row)
            {
                $ID = $row['ID'];
                $CreatorID = $row['CreatorID'];
                $Title = $row['Title'];
                $Body = $row['Body'];
                $Timestamp = $row['Timestamp'];
                $Author = $row['Author'];
                $Likes = $row['Likes'];
                $OutputTime = time_elapsed_string($Timestamp);

                if($Likes == "-1")
                {
                    $Likes = "";
                }
    
                if ($rank > 0 && $rank <= 20) {
    
                    echo '
                    <div class="container p-3 my-3 bg-dark text-white">
                    <h1>'.$Title.'</h1>
                    <h3><b>Uploaded By: '.$Author.' | '.$OutputTime.'</b></h3>
                    <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/updates/'.$ID.'" role="button">View Post</a> <img width="50" heigh="50" src="https://jarvischatbot.xyz/images/Thumbs-UP-PNG-Transparent-Image.png" <h2><b>'.$Likes.'</b></h2>                 
                    </div>
                    ';
                }	
                $rank++;		
            }    
        }
        else
        {
            echo "<div class='title'>No updates found!</div>";
        }

        ?>
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
