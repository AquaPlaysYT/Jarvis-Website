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

$serverid = $_REQUEST['id'];

if($serverid == "")
{
  header("location:https://jarvischatbot.xyz/topservers");
}

$query = "SELECT * FROM Servers WHERE ID = '$serverid' OR VantityURL = '$serverid'";
$statement = $connect->prepare($query);
$statement->execute();
$count = $statement->rowCount();
if($count > 0)
{	
  $result = $statement->fetchAll();
	foreach($result as $row)
	{
        $name = $row['Name'];
        $membercount = $row['MemberCount'];
        $icon = $row['AvatarID'];
        $messages = $row['Messages'];
        $serverid_icon = $row['ID']; 
        $VerifiedCheck = $row['Verified'];
        if($VerifiedCheck == true)
        {
            $Verified = "https://cdn.discordapp.com/attachments/612761252875337739/699324344467456080/verified_v2.png";
            $server_color = "#33C6FF";
        }
        else
        {
            $Verified = "";
            $server_color = "#FFFFFF";
        }
    }
}
else
{
   header("location:error.html");
}

$icon = "https://cdn.discordapp.com/icons/$serverid_icon/$icon.png?size=64";

if($name == "")
{
  $name = "Please use Jarvis in your server for this data!";
  $membercount = "Unknown";
  $icon = "https://cdn.iconscout.com/icon/free/png-256/warning-board-alarm-attention-error-exclamation-38927.png";
  $messages = "Unknown";
}
else if($icon == "")
{
  $icon = "https://cdn.iconscout.com/icon/free/png-256/warning-board-alarm-attention-error-exclamation-38927.png";
}

$guilds = $_SESSION['guilds'];

// @ Removed the error for peeps who are not logged in

$arrlength = @sizeof($guilds);

$query = "SELECT serverBackground FROM Servers WHERE ID = $serverid";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
foreach($result as $row)
{
    $image_discord_background = "https://jarvischatbot.xyz/images/headers/" . $row['serverBackground'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script data-ad-client="ca-pub-2972734010560512" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:description"  content="This Server has <?php echo $membercount ?> Members!"/>
    <meta property="og:title" content="<?php echo $name ?> - Server Stats">
    <meta property="og:type" content="website" />
    <meta name="theme-color" content="<?php echo $server_color ?>">
    <meta property="og:image" content="<?php echo $icon ?>" />
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/bootstrap.min.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/main.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/now-ui-kit.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    <title>JarvisTheChatbot</title>
</head>
<style>
img {
    border-radius: 50%;
}
</style>
<body>
<div class="features">
<div style="background-image: url('<?php echo $image_discord_background ?>')" class="container p-3 my-3 bg-dark text-white rounded">
        <br><br>
        <h1 align="middle"><b><img src=<?php echo $Verified; ?>> <?php echo $name; ?> <img align="middle" src=<?php echo $icon; ?>></b></h2>
    </div>
    <div class="container p-3 my-3 bg-dark text-white rounded">
        <h3>Members: <?php echo $membercount; ?></h3>
        <h3>Messages Sent: <?php echo $messages; ?></h3>
        <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/topservers" role="button">Return</a>
        <br>

        <?php

        $query = "SELECT * FROM Servers WHERE Invite != '' AND ID = $serverid";
        $statement = $connect->prepare($query);
        $statement->execute();
        $count = $statement->rowCount();
        if($count > 0)
        {	
            $server_invite = $row['Invite'];
            echo '
            <p>Join The Discord <a href="https://discord.gg/'.$server_invite.'">Here!</a></p>
            </div>
            '; 
        }
        else
        {

        }

        for($i = 0; $i < $arrlength; $i++) {

        if(($guilds[$i]->permissions & 0x8) == 0x8)
        {
            if($guilds[$i]->id == $serverid_icon)
            {
                if($guilds[$i]->owner == "1")
                {
                    $query = "SELECT Verified FROM Servers WHERE ID = $serverid";
                    $statement = $connect->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach($result as $row)
                    {
                        $verifiedcheck = $row['Verified'];
                    }

                    if($verifiedcheck == 1)
                    {
                        $_SESSION['Server_Auth_ID'] = $serverid;

                        echo '
            
                        <div class="container p-3 my-3 bg-dark text-white rounded">
                            <h2>- Settings -</h2>
                            <form action="upload.php" method="post" enctype="multipart/form-data">
                                <h2>User Banner</h2>
                                <h3>Select a file to upload!</h3>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload">
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                                <input class="btn btn-primary btn-lg" type="submit" value="Upload Image" name="submit">
                            </form>
                        </div>
                        
                        ';
                    }             
                }
            }
        }}

        echo 
        "</header>";
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
