<?php

error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER['DOCUMENT_ROOT']."/scripts/navbar.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
maintenance();

$userid = $_REQUEST['id'];

if($userid == "")
{
  header("location:".$_SESSION['current_page']);
}


$query = "SELECT * FROM WebUsers WHERE discord_id = '$userid' OR username = '$userid'";
$statement = $connect->prepare($query);
$statement->execute();
$count = $statement->rowCount();
if($count > 0)
{	
  $result = $statement->fetchAll();
	foreach($result as $row)
	{
        $discord_id = $row['discord_id'];
        $username = $row['username'];
        $discriminator = $row['discriminator'];
        $avatar = $row['avatar'];
        $color = $row['rolecolor'];
        $verified = $row['verified']; 
        $isVerified = $row['isVerified']; 
        $connections = $row['connections'];
        $isBanned = $row['isBanned']; 
        $banner = $row['banner_image'];
        $background = $row['profile_background'];

        if($verified == "")
        {
            $verified = "Member";
        }

        $query2 = "SELECT * FROM UserData WHERE UserID = '$discord_id'";
        $statement2 = $connect->prepare($query2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        foreach($result2 as $row2)
        {
            $xp = $row2['xp'];
            $messages = $row2['Messages'];
        }
    }
}
else
{
   header("location:https://jarvischatbot.xyz/errors/User_NotFound");
}

$icon = "https://cdn.discordapp.com/avatars/$discord_id/$avatar.png?size=64";

if(!@getimagesize($icon))
{
    $icon = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
}
if($icon == "")
{
    $icon = "https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png";
}

if($isVerified == true)
{
    $Verified_icon = "https://cdn.discordapp.com/attachments/612761252875337739/699324344467456080/verified_v2.png";
}
else
{
    $Verified_icon = "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script data-ad-client="ca-pub-2972734010560512" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:description"  content="This shows the users Jarvis stats!"/>
    <meta property="og:title" content="<?php echo $username ?> - User Stats">
    <meta property="og:type" content="website" />
    <meta name="theme-color" content="<?php echo $color ?>">
    <meta property="og:image" content="<?php echo $icon ?>" />
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/bootstrap.min.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/main.css">
    <link rel="stylesheet" href="https://jarvischatbot.xyz/style/now-ui-kit.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700,800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?php $icon; ?>"/>
    <title><?php echo $username ?> - Profile</title>
</head>
<style>
.profile {
      text-align: center !important;
}
.profile .title {
      font-size: 40px !important;
      color: #ffffff !important;
      line-height: 70px !important;
}
.profile .subtitle {
      font-size: 20px !important;
      color: #a2a8bd;
}
.profile_background {
    text-align: center !important;
    background-blend-mode: overlay;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    position: relative;
}
.container.banner {
    background-color: #23272a;
    background-blend-mode: overlay;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    position: relative;
}
.container.row {
    width: 100%;
    padding-right: 0;
    padding-left: 0;
    margin-right: auto;
    margin-left: auto;
}
img.banner {
    max-width: 100%;
    border-radius: 1px;
    width: 725px;
    height: 400px;
    
}
.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -28px;
    margin-left: -28px;
}
.pattern_item {
    object-fit: cover !important;
    object-position: 100% 1%;
    width: 800px;
    height: 400px;
}
</style>
<body style="background-image: url('<?php echo $background ?>');" class="profile_background">
<div class="profile">
<div style="background-image: url('<?php echo $banner ?>'); border: 3px solid black; border-color: #101010" class="container p-3 my-3 text-white rounded banner">
    <div class="usertext">
        <br><br><h1><b><img style="vertical-align:middle; border-radius: 50%;" src=<?php echo $icon; ?>> <?php echo $username."#".$discriminator; ?></b></h2>
    </div>
        <?php
          $connections_data = json_decode($connections, true);
          if($connections_data !== NULL)
          {
            $i = 0;
            foreach($connections_data as $element)
            {
              $con_type = $connections_data[$i]['type'];
              $con_name = $connections_data[$i]['name'];
              $con_id = $connections_data[$i]['id'];

              $con_arraydata = array(
                'https://www.blizzard.com/' => 'battlenet',
                'https://www.facebook.com/' => 'facebook',
                'https://github.com/' => 'github',
                'https://www.reddit.com/u/' => 'reddit',
                'https://open.spotify.com/user/' => 'spotify',
                'https://steamcommunity.com/profiles/' => 'steam',
                'https://www.twitch.tv/' => 'twitch',
                'https://twitter.com/' => 'twitter',
                'https://xbox.com/' => 'xbox',
                'https://www.youtube.com/channel/' => 'youtube'
              );

              if($con_type === "youtube" || $con_type === "steam")
              {
                $url = array_search($con_type, $con_arraydata) . $con_id;
              }
              else
              {
                $url = array_search($con_type, $con_arraydata) . $con_name;
              }

              echo '<a href="'.$url.'" class="btn btn-secondary btn-md"><img width="25" height="25" src="https://jarvischatbot.xyz/images/icons/'.$con_type.'.png"> '.$con_name.'</img></a>';
              $i++;
            }
          }
        ?>
    </div>
</div>
<div class="container">
<div class="row">
<div class="col-12 col-sm-8">
    <div id="demo" class="carousel slide" data-ride="carousel">
        <div style="-webkit-backdrop-filter: blur(5px); backdrop-filter: blur(5px); height:500px" class="carousel-inner">
          <div class="carousel-item active">
            <img onerror="this.src=`https://cdn.discordapp.com/attachments/758698299297103882/768583327606636584/unknown.png`" src="<?php echo $banner; ?>" alt="Custom Skins" style="filter: brightness(50%);" class="d-block w-100 pattern_item">
            <div class="carousel-caption d-none d-md-block mb-4">
              <h1><b><?php echo $username."#".$discriminator; ?><br><a style="color: <?php echo $color ?>"><?php echo $verified ?></a></b></h1>
            </div>
          </div>

          <?php

          $query = "SELECT * FROM Data WHERE SenderID = $discord_id AND Blocked = 0 ORDER BY Score DESC LIMIT 5";
          $statement = $connect->prepare($query);
          $statement->execute();
          $count = $statement->rowCount();
          if($count > 0)
          {	
            $result = $statement->fetchAll();
            foreach($result as $row)
            {

                $Input = $row['Input'];
                $Response = $row['Response'];
                $Score = $row['Score'];

                echo '
                <div class="carousel-item">
                <img onerror="this.src=`https://cdn.discordapp.com/attachments/758698299297103882/768583327606636584/unknown.png`" src="'.$banner.'" alt="Custom Skins" style="filter: brightness(50%);" class="d-block w-100 pattern_item">
                    <div class="carousel-caption d-none d-md-block mb-4">
                        <h2><b>Popular Pattern</b></h2>
                        <h2><img src="'.$icon.'" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.$Input.'</b> </h2>
                        <h2><img src="https://jarvischatbot.xyz/images/jarvis-new.png" style="vertical-align:middle;border-radius:50%;" alt=" Discord Profile Pic" width="50" height="50"> <b>'.$Response.'</b> </h2>
                    </div>
                </div>
                ';

            }
          }
          else
          {
            echo '
            <div class="carousel-item">
              <img onerror="this.src=`https://cdn.discordapp.com/attachments/758698299297103882/768583327606636584/unknown.png`" src="'.$banner.'" alt="Custom Skins" style="filter: brightness(50%);" class="d-block w-100 pattern_item">
              <div class="carousel-caption d-none d-md-block mb-4">
              <h2><b>'.$username."#".$discriminator.'<br> <a style="color: '.$color.'">'.$verified.'</a></b></h2>
              </div>
            </div>

            <div class="carousel-item">
              <img onerror="this.src=`https://cdn.discordapp.com/attachments/758698299297103882/768583327606636584/unknown.png`" src="'.$banner.'" alt="Custom Skins" style="filter: brightness(50%);" class="d-block w-100 pattern_item">
              <div class="carousel-caption d-none d-md-block mb-4">
              <h2><b>'.$username."#".$discriminator.'<br> <a style="color: '.$color.'">'.$verified.'</a></b></h2>
              </div>
            </div>
            ';
          }

          ?>
          
        </div>

        <a style="height:400px" class="carousel-control-prev" href="#demo" data-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </a>
        <a style="height:400px" class="carousel-control-next" href="#demo" data-slide="next">
          <span class="carousel-control-next-icon"></span>
        </a>

        </div>
        </div>

        <div style="-webkit-backdrop-filter: blur(5px); backdrop-filter: blur(5px);" class="container text-white rounded col col-sm-4 d-none d-sm-flex flex-column">
          <br>
            <h2 style="text-align: center;"><b>Statistics</b></h2>
            <a style="border-left: 4px solid black; border-color: <?php echo $color ?>" class="btn btn-secondary btn-md"><?php echo $verified ?></a>
            <a style="border-left: 4px solid black; border-color: <?php echo $color ?>" class="btn btn-secondary btn-md"><?php echo $xp ?>XP</a>
            <a style="border-left: 4px solid black; border-color: <?php echo $color ?>" class="btn btn-secondary btn-md"><?php echo $messages ?> Messages Sent</a>
            <?php
            if($isVerified == 1)
            {
                echo '<a style="border-left: 4px solid black; border-color: '.$color.'" class="btn btn-secondary btn-md">Verified User<img style="margin-left: 5px" width="25" height="25" src="'.$Verified_icon.'"></img></a>';
            }
            ?>
          </br>
        </div>
    </div>
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
