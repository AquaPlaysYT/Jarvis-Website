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
    <meta property="og:description"  content="View the top users that have actively helped and used Jarvis!"/>
    <meta property="og:title" content="Jarvis Leaderboard">
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

<div class="title text-white">Jarvis Leaderboard</div>
<div class="container p-3 my-3 bg-dark text-white rounded">

<?php

echo '<table style="width:100%;"class="table table-hover table-dark"><tr><th scope="col">Rank</th><th scope="col">User</th><th scope="col">XP</th><th scope="col">Messages</th></tr>';

$limit = 12;
$rank = 1;
$Q_UserData = "SELECT * FROM UserData ORDER BY xp DESC LIMIT $limit";
$User_statement = $connect->prepare($Q_UserData);
$User_statement->execute();
$User_result = $User_statement->fetchAll();
foreach($User_result as $User_Row)
{
    $UserID = $User_Row['UserID'];
    $UserXP = $User_Row['xp'];
    $UserMessages = $User_Row['Messages'];
    $User = $User_Row['User'];

    echo "<tr><td style='width:5%;'>" . $rank . "</td><td>" . "<span style=''> $User</span>" . "</td><td style='width:30%;'>" . $UserXP . "</td>" . "<td>" . $UserMessages . "</td></tr>";      
    $rank++; 
}

?>
</div>


</center>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://jarvischatbot.xyz/style/js/now-ui-kit.min.js"></script>
<script src="https://jarvischatbot.xyz/scripts/navbar_load.js"></script>
<script>
jQuery(document).ready(function($) {
    $('*[data-href]').on('click', function() {
        window.location = $(this).data("href");
    });
});
</script>
</body>
</html>
