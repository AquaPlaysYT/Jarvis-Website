<?php

header("Content-Type: text/html; charset=ISO-8859-1");
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
<div class="cards">
<div class="container p-3 my-3 bg-dark text-white">
<?php

$query = "SELECT * FROM ModLogs ORDER BY Timestamp DESC";
$statement = $connect->prepare($query);
$statement->execute();
$count = $statement->rowCount();
$result = $statement->fetchAll();

echo '

<center><h1>Staff Message Logs</h1>

<h2>Total Messages: '.$count.'</h2>

<h3>Note - The VPS is 1 hour ahead!</h3>

<h3><font color="red">Warning: This page is not made for mobile</h3>

<a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/admin/developer/staff.php" role="button">Return</a>

<input type="text" id="myInput" onkeyup="SearchMembers()" placeholder="Search Username..">

<input type="text" id="myInput1" onkeyup="SearchMessages()" placeholder="Search Message..">

<br>
<hr style="height:2px;border-width:0;color:white;background-color:black">

<center><table class="table table-striped table-dark" id="myTable"><tr><th scope="col">Username</th><th scope="col">Message</th><th scope="col">Time Of Message</th></tr>

';

foreach ($result as $row)
{
    $UserID = $row['UserID'];
    $Message = $row['Message'];
    $Time = $row['Timestamp'];

    $TimeDate = date("d-m-y H:i", substr($Time, 0, 10));

    $query = "SELECT * FROM WebUsers WHERE discord_id = $UserID ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row)
    {
        $username = $row['username'];
        $avatar = $row['avatar'];

        $image = "https://cdn.discordapp.com/avatars/$UserID/$avatar.png?size=64";
    
       echo '
        <tr><td style="width:10%;"><img heigh="40" width="40" class="avatar" style="vertical-align:middle" src="'.$image.'"> '.$username.'</td><td style="width:40%;">'.$Message.'</td><td style="width:10%;">'.$TimeDate.'</td></tr>
       ';
    }
}


?>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://jarvischatbot.xyz/scripts/now-ui-kit.min.js"></script>
<script>
function SearchMembers() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function SearchMessages() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
</body>
</html>