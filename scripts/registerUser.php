<?php
//require("../db/Conn.php");
require("../db/MySQLDAO.php");
$config = parse_ini_file('../db/dbaccess.ini');


$returnValue = array();

if(empty($_REQUEST["userEmail"]) || empty($_REQUEST["userPassword"])
        || empty($_REQUEST["userFirstName"])
        || empty($_REQUEST["userLastName"]))
{
    $returnValue["status"]="400";
    $returnValue["message"]="Missing required information";
    echo json_encode($returnValue);
    return;
}

$userEmail = htmlentities($_REQUEST["userEmail"]);
$userPassword = htmlentities($_REQUEST["userPassword"]);
$userFirstName = htmlentities($_REQUEST["userFirstName"]);
$userLastName = htmlentities($_REQUEST["userLastName"]);

//Generate secure password
$salt = openssl_random_pseudo_bytes(16);
$secured_password = sha1($userPassword . $salt);

$dbhost = trim($config["dbhost"]);
$dbuser = trim($config["dbuser"]);
$dbpassword = trim($config["dbpassword"]);
$dbname = trim($config["dbname"]);

$dao = new MySQLDAO($dbhost, $dbuser, $dbpassword, $dbname);
$dao->openConnection();

//Check to see if user with provided username is available
$userDetails = $dao->getUserDetails($userEmail);
if(!empty($userDetails))
{
    $returnValue["status"]="400";
    $returnValue["message"]="Email address already in use. Try again.";
    echo json_encode($returnValue);
    return;
}

//Register new user
$result = $dao->registerUser($userEmail, $userFirstName, $userLastName, $secured_password, $salt);

if($result)
{
    $userDetails = $dao->getuserDetails($userEmail);
    $returnValue["status"]="200";
    $returnValue["message"]="Successfully registered new user";
    $returnValue["userId"]= $userDetails["user_id"];
    $returnValue["userFirstName"]= $userDetails["user_firstname"];
    $returnValue["userLastName"]= $userDetails["user_lastname"];
    $returnValue["userEmail"]= $userDetails["email"];
}
else
{
    $returnValue["status"]="400";
    $returnValue["message"]="Could not register new user with info provided.";
}

$dao->closeConnection();

echo json_encode($returnValue);

?>