<?php

error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER['DOCUMENT_ROOT']."/scripts/navbar.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
maintenance();

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

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
    <meta property="og:description"  content="Bot / Shard infomation"/>
    <meta property="og:title" content="Jarvis Status">
    <meta property="og:type" content="website" />
    <meta name="theme-color" content="#0357ff">
    <meta property="og:image" content="https://jarvischatbot.xyz/images/jarvis_new_new.png" />
    <title>JarvisTheChatbot</title>
</head>
<style>
[data-href] {
    cursor: pointer;
}
</style>
<body>
<center>

<div class="container p-3 my-3 bg-dark text-white rounded">
    <h2><b>Jarvis Status</b></h2>
</div>

<div class="container p-3 my-3 bg-dark text-white rounded">
<br>
<?php

$count = 0;
$Status_Data = "SELECT * FROM ShardData";
$Status_statement = $connect->prepare($Status_Data);
$Status_statement->execute();
$Status_result = $Status_statement->fetchAll();
foreach($Status_result as $Status_Row)
{
    $ShardID = $Status_Row['ShardID'];
    $GatewayPing = $Status_Row['GatewayPing'];
    $RestPing = $Status_Row['RestPing'];
    $Status = $Status_Row['Status'];
    $Guilds = $Status_Row['Guilds'];
    $Timestamp = $Status_Row['Timestamp'];

    $milliseconds = round(microtime(true) * 1000);

    $i = $milliseconds - $Timestamp;
    if($i > 120000)
    {
        
    }
    else
    {
        echo '
        <div class="card" style="width: 18rem;">
            <div class="card-body">
               <h5 class="card-title">Shard '.$ShardID.'</h5>
                <h5 class="card-subtitle mb-2 text-muted"><b>Status: '.$Status.'</b></h5>
                <h5 class="card-subtitle mb-2 text-muted"><b>Ping: '.$GatewayPing.'</b></h5>
                <h5 class="card-subtitle mb-2 text-muted"><b>Serving: '.$Guilds.' Servers</b></h5>
            </div>
        </div>
        ';
        $count++;   
    }
}


if($count == 0)
{
    $Status_Message = "Jarvis is currently Experiencing issues!";
    echo '<h3><font color="red"><b>'.$Status_Message.'</b></font></h3>';
}


?>


</div>


</center>
<br>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://jarvischatbot.xyz/style/js/now-ui-kit.min.js"></script>
<script src="https://jarvischatbot.xyz/scripts/navbar_load.js"></script>
<script>
    setInterval(function(){ location.reload(); }, 70000);
</script>
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
