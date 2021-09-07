<?php

session_start();

  $url = "https://discordapp.com/api/webhooks/691432585926148139/NnGR5_fizc0WhObc_Pr1jZccL4dBBj1YPiYAaOR1OjuszPyc3qm-dXdQmUaCJ234qU7-";
  $message = $_REQUEST['message'];
  $discord = $_SESSION['username'];
  $id = $_SESSION['discord_id'];
  $avatar = $_SESSION['avatar'];
  $image = "https://cdn.discordapp.com/avatars/$id/$avatar.png?size=256";

  if(!isset($message))
  {
      return;
      echo "Please use the feedback link!";
  }

  $hookObject = json_encode([

      "username" => $discord,
      "avatar_url" => $image,
      "embeds" => [
           [
               "title" => "Feedback Report",
        
               "type" => "rich",
   
               "description" => $message,
   
               "color" => hexdec( "00FFFF" ),
   
           ]
       ]
   
   ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
   
   $ch = curl_init();
   
   curl_setopt_array( $ch, [
       CURLOPT_URL => $url,
       CURLOPT_POST => true,
       CURLOPT_POSTFIELDS => $hookObject,
       CURLOPT_HTTPHEADER => [
           "Content-Type: application/json"
       ]
   ]);
   
   $response = curl_exec( $ch );
   curl_close( $ch );

   echo "Thank you for sending us feedback!";

   sleep(3);
   
   header('location:https://jarvischatbot.xyz/feedback/');
   
?>
