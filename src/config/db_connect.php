
<?php 

$server = "localhost";
$username = "root";
$password = "";
$database = "pharmasoul";

$conn = mysqli_connect($server, $username, $password, $database);
if(!$conn){
    echo "error";
}

?>