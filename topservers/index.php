<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/navbar.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/error.php");
header("Content-Type: text/html; charset=ISO-8859-1");
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
    <meta property="og:description"  content="View all top servers using Jarvis!"/>
    <meta property="og:title" content="Jarvis Servers">
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
<center>
<div class="title text-white">Trusted Servers Using Jarvis!</div>

<?php

    $query = "SELECT * FROM Servers ORDER BY MemberCount DESC LIMIT 10";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        $Name = $row['Name'];
        $MemberCount = $row['MemberCount'];
        $AvatarID = $row['AvatarID'];
        $Messages = $row['Messages'];
        $ID = $row['ID'];
        $VerifiedCheck = $row['Verified'];
        $bancheck = $row['isBanned'];
        $icon = "https://cdn.discordapp.com/icons/$ID/$AvatarID.png?size=64";
        $Verified = "";
        
        if($Name !== NULL)
        {
            if($bancheck !== "1")
            {
                if($VerifiedCheck == true)
                {
                    $Verified = "https://cdn.discordapp.com/attachments/612761252875337739/699324344467456080/verified_v2.png";
                }      
    
                echo '
                <div class="container p-3 my-3 bg-dark text-white">
                <h1><img style="border-radius:50%;" onerror="this.src=`https://cdn.discordapp.com/attachments/612761252875337739/699397410165620746/icon_default.png`" src="'.$icon.'"> '.$Name.' <img onerror="this.style.display=`none`" src="'.$Verified.'"></h1>
                <h3><b>This Server has '.$MemberCount.' Members!</b></h3>
                <a class="btn btn-primary btn-lg" href="https://jarvischatbot.xyz/servers/'.$ID.'" role="button">View Server</a>
                </div>
                ';	
            } 
        }          	
    }    

    ?>

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
