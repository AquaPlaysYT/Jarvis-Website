<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
session_start();
error_reporting(E_ALL);
define('OAUTH2_CLIENT_ID', '');
define('OAUTH2_CLIENT_SECRET', '');
$authorizeURL = 'https://discordapp.com/api/oauth2/authorize';
$tokenURL = 'https://discordapp.com/api/oauth2/token';
$apiURLBase = 'https://discordapp.com/api/users/@me';
$apiUrlGuilds = 'https://discordapp.com/api/users/@me/guilds';
$apiUrlConnections = 'https://discordapp.com/api/users/@me/connections';
$revokeURL = 'https://discordapp.com/api/oauth2/token/revoke';

function Home() {
  header("Location: index.php");
}

if(get('action') == 'login') {
  $params = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'redirect_uri' => 'https://jarvischatbot.xyz/login.php',
    'response_type' => 'code',
    'scope' => 'identify guilds connections'
  );
  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
  die();
}

if(get('code')) {
    $token = apiRequest($tokenURL, array(
      "grant_type" => "authorization_code",
      'client_id' => OAUTH2_CLIENT_ID,
      'client_secret' => OAUTH2_CLIENT_SECRET,
      'redirect_uri' => 'https://jarvischatbot.xyz/login.php',
      'code' => get('code')
    ));
    $logout_token = $token->access_token;
    $_SESSION['access_token'] = $token->access_token;
    header('Location: ' . $_SERVER['PHP_SELF']);
  }

if(session('access_token')) {

  $user = apiRequest($apiURLBase);
  $guilds = apiRequest($apiUrlGuilds);
  $connections = apiRequest($apiUrlConnections);
  $arrlength = sizeof($guilds);

  $i = 0;
  foreach($connections as $element)
  {
    if($connections[$i]->visibility == 0)
    {
      unset($connections[$i]);
    }
    $i++;
  }
  $connections = array_values($connections);
  $connections_insert = json_encode($connections);

  // Sets sessions guilds

  $_SESSION['guilds'] = $guilds; 

  //
  login_check:
  $check_query = "SELECT * FROM WebUsers WHERE discord_id = :discord_id";
	$statement = $connect->prepare($check_query);
	$check_data = array(
		':discord_id'		=>	$user->id
	);
	if($statement->execute($check_data))
	{
		if($statement->rowCount() > 0)
		{
      $result = $statement->fetchAll();
      foreach($result as $row)
      {
        if($row['isBanned'] == "1")
        {
           $message = "Error, You have been banned from using Jarvis!";
        }
        else
        {
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['username'] = $row['username'];
          $_SESSION['discord_id'] = $row['discord_id'];
          $_SESSION['patterns'] = $row['patterns'];
          $_SESSION['connections'] = $row['connections'];
          $_SESSION['locale'] = $row['locale'];
          $_SESSION['avatar'] = $user->avatar;
          $_SESSION['discriminator'] = $row['discriminator'];
          $_SESSION['verified'] = $row['verified'];

          $avatar = $_SESSION['avatar'];
          $discriminator = $user->discriminator;
          $id = $_SESSION['discord_id'];

          $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=256";

          if($user->avatar !== $row['avatar'])
          {
            $query = "UPDATE WebUsers SET avatar = '$avatar' WHERE discord_id = '$id'";
            $statement = $connect->prepare($query);
            $statement->execute();  
          }
          if($user->username !== $row['username'])
          {
            $query = "UPDATE WebUsers SET username = '$user->username', discriminator = '$discriminator' WHERE discord_id = $id";
            $statement = $connect->prepare($query);
            $statement->execute();  
          }
          if($connections_insert !== $row['connections'])
          {
            $query = "UPDATE WebUsers SET connections = '$connections_insert' WHERE discord_id = '$id'";
            $statement = $connect->prepare($query);
            $statement->execute();  
          }
          home();
        }
      }
		}
		else
		{
			if($message == '')
			{
				$data = array(
          ':id'  =>  $user->id,
					':username'		=>	$user->username,
          ':avatar'		=>	$user->avatar,
          ':discriminator'		=>	$user->discriminator,
          ':locale'  =>  $user->locale,
          ':connections' => $connections_insert
        );
        
        if($row['isBanned'] == "1")
        {
           $message = "Error, You have been banned from using Jarvis Web!";
        }
        else
        {
          $query = "
          INSERT INTO WebUsers 
          (discord_id, username, discriminator, avatar, locale, connections) 
          VALUES (:id, :username, :discriminator, :avatar, :locale, :connections)
          ";
          
          $statement = $connect->prepare($query);
        }
				if($statement->execute($data))
				{
          goto login_check;
				}
			}
		}
  }
} 

if(get('action') == 'logout') {
    apiRequest($revokeURL, array(
        'token' => session('access_token'),
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
      ));
    unset($_SESSION['access_token']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    die();
}

function apiRequest($url, $post=FALSE, $headers=array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    if($post)
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $headers[] = 'Accept: application/json';
    if(session('access_token'))
      $headers[] = 'Authorization: Bearer ' . session('access_token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    return json_decode($response);
  }
  function get($key, $default=NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
  }
  function session($key, $default=NULL) {
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
  }
?>
<html>  
    <head>  
		<script data-ad-client="ca-pub-2972734010560512" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <title>Jarvis-Bot Log</title>  
	    	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
			<br />
			
			<h3 align="center">Jarvis-Bot Login</a></h3><br />
			<br />
			<div class="panel panel-default">
  				<div class="panel-heading">Login</div>
				<div class="panel-body">
					<form method="post">
						<p class="text-danger"><?php echo $message; ?></p>
						<div class="form-group">
							 <?php echo '<h3>Not logged in</h3>';
               echo '<h2><a href="?action=login">Log In</a></h2>'; ?>
						</div>
          </form>
          <h2>Notice: <a href="https://docs.google.com/document/d/1xOStu7BiKQxltj6NhWC9fF0RrHDU_Jx_iD42kt7FBFU/edit#heading=h.py31omqpikod">Jarvis Privacy Statement</a></h2>
				</div>
			</div>
		</div>
    </body>  
</html>