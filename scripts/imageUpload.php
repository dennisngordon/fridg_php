<?php

$user_id = $_POST["userId"];
$target_dir = "/Applications/XAMPP/xamppfiles/htdocs/fridgapp/profile-pictures/" . $user_id;

if(!file_exists($target_dir))
{
    mkdir($target_dir, 0777, true);
}

$target_dir = $target_sir . "/" . basename($_FILES["file"]["name"]);

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir))
{
    echo jason_encode([
        "Message" => "The file " . basename($_FILES["file"]["name"]) . " has been uploaded",
        "Status" => "OK",
        "userId" => $user_id
    ]);
}
else
{
    echo json_encode([
        "Message" => "There was an error uploading your file",
        "Status" => "Error",
        "user_Id" => $user_id
    ]);
}


?>