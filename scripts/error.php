<?php

function maintenance(){

    include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
    $query = "SELECT * FROM WebMessage";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        $downtimecheck = $row['downcheck'];
    }
    if($downtimecheck == "1")
    {
        if($_SESSION['verified'] == "[Developer] ")
        {
            echo '
            <div class="alertdt">
                <centeR><b>Alert:</b> Website is currently in maintenance mode! You can view this page because your rank is '.$_SESSION['verified'].'
            </div>
            ';
        }
        else
        {
            header("location:https://jarvischatbot.xyz/scripts/error.html");
        }
    }
    else
    {
        
    }

}

?>