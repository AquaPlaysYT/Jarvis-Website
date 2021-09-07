<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
include($_SERVER['DOCUMENT_ROOT']."/scripts/stats_db-connect.php");
session_start();

$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

if(!isset($_SESSION['user_id']))
{
  header("location:https://jarvischatbot.xyz/login.php?action=login");
}
else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
{

}
else
{
  header("location:https://jarvischatbot.xyz");
}


if(isset($_GET['banuserid'])) {

    $ban_id = $_REQUEST['banuserid'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE WebUsers SET isBanned = 1 WHERE discord_id = '$ban_id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:members.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['unbanuserid'])) {

    $ban_id = $_REQUEST['unbanuserid'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE WebUsers SET isBanned = 0 WHERE discord_id = '$ban_id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:members.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['banserverid'])) {

    $ban_id = $_REQUEST['banserverid'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE Servers SET isBanned = 1 WHERE ID = '$ban_id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:servers.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }   
}

if(isset($_GET['unbanserverid'])) {

    $ban_id = $_REQUEST['unbanserverid'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE Servers SET isBanned = 0 WHERE ID = '$ban_id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:servers.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['verifyid'])) {

    $id = $_REQUEST['verifyid'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE Servers SET Verified = 1 WHERE ID = '$id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:servers.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['unverifyid'])) {

    $id = $_REQUEST['unverifyid'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE Servers SET Verified = 0 WHERE ID = '$id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:servers.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['verifyuser'])) {

    $id = $_REQUEST['verifyuser'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE WebUsers SET isVerified = 1 WHERE discord_id = '$id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:members.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['unverifyuser'])) {

    $id = $_REQUEST['unverifyuser'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE WebUsers SET isVerified = 0 WHERE discord_id = '$id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:members.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['demotestaff'])) {

    $id = $_REQUEST['demotestaff'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE WebUsers SET verified = NULL WHERE discord_id = '$id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:https://jarvischatbot.xyz/admin/developer/staff.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['depromoteuser'])) {

    $id = $_REQUEST['depromoteuser'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "UPDATE WebUsers SET rank = 'user' WHERE discord_id = '$id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:members.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['deleteuser'])) {

    $id = $_REQUEST['deleteuser'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] ")
    {
        $query = "DELETE FROM WebUsers WHERE discord_id = '$id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        $query1 = "DELETE FROM UserData WHERE UserID = '$id'";
        $statement1 = $connect->prepare($query1);
        $statement1->execute(); 
        header("location:members.php");
    }
    else if($_SESSION['verified'] == "[Helper] ")
    {
        echo "You do not have access to use this command";
        header('refresh: 5; url=index.php');
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['block'])) {

    $ban_id = $_REQUEST['block'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Moderator] " || $_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] " || $_SESSION['verified'] == "[Helper] ")
    {
        $query = "UPDATE Data SET Blocked = 1 WHERE ID = '$ban_id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:patterns.php");
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}

if(isset($_GET['unblock'])) {

    $ban_id = $_REQUEST['unblock'];

    if(!isset($_SESSION['user_id']))
    {
        header("location:https://jarvischatbot.xyz/login.php?action=login");
    }
    else if($_SESSION['verified'] == "[Moderator] " || $_SESSION['verified'] == "[Administrator] " || $_SESSION['verified'] == "[Developer] " || $_SESSION['verified'] == "[Helper] ")
    {
        $query = "UPDATE Data SET Blocked = 0 WHERE ID = '$ban_id'";
        $statement = $connect->prepare($query);
        $statement->execute(); 
        header("location:patterns.php");
    }
    else
    {
        echo "You do not have access to view this page!";
        header('refresh: 5; url=https://jarvischatbot.xyz');
    }
}


?>