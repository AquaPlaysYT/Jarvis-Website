<?php

include($_SERVER['DOCUMENT_ROOT']."/scripts/database_connection.php");
session_start();

$AuthID = $_SESSION['Server_Auth_ID'];

$guilds = $_SESSION['guilds'];
$arrlength = @sizeof($guilds);

for($i = 0; $i < $arrlength; $i++) {

    if(($guilds[$i]->permissions & 0x8) == 0x8)
    {
        if($guilds[$i]->id == $AuthID)
        {
            if($guilds[$i]->owner == "1")
            {
                $query = "SELECT Verified FROM Servers WHERE ID = $AuthID";
                $statement = $connect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll();
                foreach($result as $row)
                {
                    $verifiedcheck = $row['Verified'];
                }

                if($verifiedcheck == 1)
                {
                
                    $target_dir = "/opt/lampp/htdocs/images/headers/";
                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                    // Check if image file is a actual image or fake image
                    if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        header("location:https://jarvischatbot.xyz/");
                        $uploadOk = 0;
                    }
                    }

                    // Check file size
                    if ($_FILES["fileToUpload"]["size"] > 9000000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                    }

                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                    }

                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                    } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $AuthID.".".$imageFileType)) {
                        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

                        $image_name = $AuthID.".".$imageFileType;

                        $query = "UPDATE Servers SET serverBackground = '$image_name' WHERE ID = $AuthID";
                        $statement = $connect->prepare($query);
                        $statement->execute();

                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }}
                    header("location:https://jarvischatbot.xyz/servers/$AuthID");   
                }
            }
        }
    }
}

?>