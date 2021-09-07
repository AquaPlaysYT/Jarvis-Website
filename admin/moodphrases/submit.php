<?php 

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");

//Used for login redirect

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

//

if(!isset($_SESSION['user_id']))
{
  header("location:https://jarvischatbot.xyz/login.php?action=login");
}
else if($_SESSION['verified'] == "[Developer] " || $_SESSION['discord_id'] == "210068801713209345" || $_SESSION['verified'] == "[Jarvions] ")
{

}
else
{
  header("location:https://jarvischatbot.xyz");
}

$slider_value = $_REQUEST['moodValue'];       
$pattern_id_grab = $_REQUEST['patternid'];   

$query = "UPDATE Data SET Mood = $slider_value WHERE ID = $pattern_id_grab";
$statement = $connect->prepare($query);
$statement->execute();
header("location: https://jarvischatbot.xyz/admin/moodphrases/");

?>