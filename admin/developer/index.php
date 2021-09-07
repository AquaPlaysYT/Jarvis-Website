<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();
//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

$query = "SELECT * FROM GraphData as date";
$result = mysqli_query($connect1, $query);
$chart_data1 = '';
$chart_data2 = '';
$chart_data3 = '';
while($row = mysqli_fetch_array($result))
{
 $chart_data1 .= "{ Date:'".$row["Date"]."', Members:".$row["Members"]."}, ";
 $chart_data2 .= "{ Date:'".$row["Date"]."', Servers:".$row["Servers"]."}, ";
 $chart_data3 .= "{ Date:'".$row["Date"]."', Patterns:".$row["Patterns"].", BlockedPatterns:".$row["BlockedPatterns"].", TotalPatterns:".$row["TotalPatterns"]."}, ";
}
$chart_data1 = substr($chart_data1, 0, -2);
$chart_data2 = substr($chart_data2, 0, -2);
$chart_data3 = substr($chart_data3, 0, -2);

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
            <li class="nav-item active">
                <a class="nav-link" href="https://jarvischatbot.xyz/admin/developer">Home <span class="sr-only">(current)</span></a>
            </li> 
            <li class="nav-item">
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
                        <img alt="Discord Profile Picture" style="width:30px;height:30px;border-radius:50%;" src="'.$image.'"> '.$_SESSION['username'].'
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
    <div class="title">Welcome back <?php echo $_SESSION['username']; ?>#<?php echo $_SESSION['discriminator']; ?></div>
    <div class="container p-3 my-3 bg-dark text-white">
        <h1>Current Statistics</h1>
        <hr style="height:2px;border-width:0;color:white;background-color:white">
         <?php

            $Members = '';
            $Servers = '';
            $Patterns = '';
            $Responses = '';
            $Scan = '';

            $query = "SELECT * FROM GraphData as date ORDER BY Date";
            $result = mysqli_query($connect1, $query);
            while($row = mysqli_fetch_array($result))
            {
                $Members = $row['Members'];
                $Servers = $row['Servers'];
                $Patterns = $row['Patterns'];
                $Responses = $row['TotalResponses'];
            }

            $query2 = "SELECT * FROM BotData";
            $statement2 = $connect->prepare($query2);
            $statement2->execute();
            $result = $statement2->fetchAll();
            foreach($result as $row)
            {
                 $Scan = $row['Scan'];
            }

            echo '
            <h2>Members: '.number_format($Members).'</h2>
            <h2>Servers: '.number_format($Servers).'</h2>
            <h2>Patterns: '.number_format($Patterns).'</h2>
            <h2>Total Responses: '.number_format($Responses).'</h2>
            <h2>AntiSpam Scan: '.number_format($Scan).'</h2>
            ';

         ?>
        <hr style="height:2px;border-width:0;color:white;background-color:white">
    </div>

    <div class="container p-3 my-3 bg-dark text-white">
        <h1>Total Users Interacted With Jarvis</h1>
        <br /><br />
        <div id="members"></div>
        <h1>Total Servers</h1>
        <br /><br />
        <div id="servers"></div>
        <h1>Total Patterns</h1>
        <br /><br />
        <div id="patterns"></div>
    </div>

</div>
<div class="footer">
    Notice:<a href="https://jarvischatbot.xyz/privacy.pdf"> Jarvis Privacy Statement</a>
	<a href="//www.dmca.com/Protection/Status.aspx?ID=6c263011-55c1-474d-ae21-479608bfb330" title="DMCA.com Protection Status" class="dmca-badge"> <img src ="https://images.dmca.com/Badges/dmca_protected_sml_120m.png?ID=6c263011-55c1-474d-ae21-479608bfb330"  alt="DMCA.com Protection Status" /></a>  <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://jarvischatbot.xyz/scripts/now-ui-kit.min.js"></script>
<script>

Morris.Line({
 element : 'members',
 data:[<?php echo $chart_data1; ?>],
 xkey:'Date',
 ykeys:['Members'],
 labels:['Members'],
 hideHover:'auto',
 stacked:true
});

     document.addEventListener("DOMContentLoaded", function(event) {
   document.querySelectorAll('img').forEach(function(img){
  	img.onerror = function(){this.style.display='none';};
   })
});

Morris.Line({
 element : 'servers',
 data:[<?php echo $chart_data2; ?>],
 xkey:'Date',
 ykeys:['Servers'],
 labels:['Servers'],
 hideHover:'auto',
 stacked:true
});

     document.addEventListener("DOMContentLoaded", function(event) {
   document.querySelectorAll('img').forEach(function(img){
  	img.onerror = function(){this.style.display='none';};
   })
});

Morris.Line({
 element : 'patterns',
 data:[<?php echo $chart_data3; ?>],
 xkey:'Date',
 ykeys:['Patterns', 'BlockedPatterns', 'TotalPatterns'],
 labels:['Patterns', 'Blocked Patterns', 'Total Patterns'],
 hideHover:'auto',
 stacked:true
});

     document.addEventListener("DOMContentLoaded", function(event) {
   document.querySelectorAll('img').forEach(function(img){
  	img.onerror = function(){this.style.display='none';};
   })
});


</script>
</body>
</html>
