<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/navbar.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
maintenance();
//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

if(!isset($_SESSION['discord_id']))
{
    header("location: https://jarvischatbot.xyz/login.php?action=login");
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
<style>
[data-href] {
    cursor: pointer;
}
</style>
<body>
<center>

<?php

$discord_id = $_SESSION['discord_id'];

$query = "SELECT * FROM UserData WHERE UserID = $discord_id";
$statement = $connect->prepare($query);
$statement->execute();
$count = $statement->rowCount();
if($count > 0)
{	
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
        echo '<script>console.log("Request Found"); </script>';
            
        $xp = $row['xp'];
        $last_message = $row['LatestMessage'];

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

        if($xp == NULL)
        {
            $xp = "0";
        }
        else if($last_message == NULL)
        {
            $last_message = "0";
		}
    }
}

?>

<div class="parent-container d-flex">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="container p-3 my-3 bg-dark text-white rounded">
                    <h2><img alt="Discord Profile Picture" style="width:50px;height:50px;border-radius:50%;" src="<?php echo $image ?>"> <?php echo $_SESSION['username'] ?> | Account XP: <?php echo $xp ?></h2>
                    <h2>Discord Rank: <font color="<?php echo $_SESSION['rolecolor'] ?>"><?php echo $_SESSION['verified'] ?></font></h2></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container p-3 my-3 bg-dark text-white rounded">
    <h3><b>Current Challenges</b></h3>

<hr style="height:2px;border-width:0;color:white;background-color:white">

    <?php /*

        $query = "SELECT * FROM Challenges WHERE ChallengeActive = 1";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $count = $statement->rowCount();
        if($count > 0)
        {
            foreach($result as $row)
            {
                $Challenge_ID = $row['ID'];
                $Challenge_Name = $row['ChallengeName'];
                $Challenge_Description = $row['ChallengeDescription'];
                $Challenge_Active = $row['ChallengeActive'];
            }

            echo '
            <h2>'.$Challenge_Name.'</h2>
            <h3>'.$Challenge_Description.'</h3>
            ';

        }
        else
        {
            $Challenge_Name = "No Active Challenges";
            $Challenge_Description = "";
        }
        

        if(isset($_POST["EnterChallenge"]))
        { 
            $query = "SELECT * FROM ChallengeData WHERE UserID = $discord_id";
            $statement = $connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if($count > 0)
            {

            }
            else
            {
                $query2 = "INSERT INTO ChallengeData (ChallengeID, UserID) VALUES ($Challenge_ID, $discord_id);";
                $statement2 = $connect->prepare($query2);
                $statement2->execute();
            }
    
        }

        $query = "SELECT * FROM ChallengeData WHERE UserID = $discord_id";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $count = $statement->rowCount();
        if($count > 0)
        {
            foreach($result as $row)
            {
                $Challenge_Progress = $row['ChallengeProgress'];

                if($Challenge_Progress == "100")
                {
                    echo '<h3><font color="green">Challenge Complete</font></h3>';

                }
                else
                {
                    echo '
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" style="width: '.$Challenge_Progress.'%;" aria-valuenow="'.$Challenge_Progress.'" aria-valuemin="0" aria-valuemax="100">'.$Challenge_Progress.'% Complete</div>
                    </div>
                    ';
                }
            }
        }
        else
        {
            echo '
            <form method="post">
                <div class="form-group">
                    <button class="btn btn-primary btn-lg" name="EnterChallenge" type="submit">Accept Challenge</button>
                </div>
            </form>
            ';  
        }
*/
    ?>

    <h3>Coming Soon...</h3>

    <hr style="height:2px;border-width:0;color:white;background-color:white">

</div>

<center>

<div class="container p-3 my-3 bg-dark text-white rounded">

<h2>XP Leaderboard - Top 10</h2>

<?php

echo '<table style="width:75%;"class="table table-hover table-dark"><tr><th scope="col">Rank</th><th scope="col">User</th><th scope="col">XP</th></tr>';

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
    $User = $User_Row['User'];

    $Q_WebUsers = "SELECT * FROM WebUsers WHERE discord_id = $UserID LIMIT $limit";
    $WebUser_statement = $connect->prepare($Q_WebUsers);
    $WebUser_statement->execute();
    $WebUser_result = $WebUser_statement->fetchAll();
    foreach($WebUser_result as $WebUser_Row)
    {
        $verified = $WebUser_Row['verified'];
        $color = $WebUser_Row['rolecolor'];
        $avatar = $WebUser_Row['avatar'];
        $bancheck = $WebUser_Row['isBanned'];
        $verifycheck = $WebUser_Row['isVerified'];
        $image = "https://cdn.discordapp.com/avatars/$UserID/$avatar.png?size=256";

        if($verifycheck == true)
        {
          $VerifiedIcon = "https://cdn.discordapp.com/attachments/612761252875337739/699324040221032519/verified.png";
        }
        else
        {
          $VerifiedIcon = "";
        }
        
        if(!@getimagesize($image))
        {
           $image = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
        }
        if($image == "")
        {
            $image = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
        }

        echo "<tr data-href='https://jarvischatbot.xyz/users/$UserID'><td style='width:10%;'>" . $rank . "</td><td>" . "<img onerror='this.style.display=`none`' width='25' height='25' src='$VerifiedIcon'> <img onerror='this.style.display=`none`' width='25' height='25'style='border-radius: 50%;' src='$image'><font color='$color'> $verified</font>" . "<img1 style='vertical-align:middle' src=$image width='30' height='30' alt=''> <span style=''> $User</span>" . "</td><td>" . $UserXP . "</td></tr>";      
     
        $rank++; 
    }
}

?>

</center>

</div>
<br>
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
