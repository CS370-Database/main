<?php
$con = @mysqli_connect("localhost", "root", "password", "movie_db");
if(mysqli_connect_errno()) {
    echo "Failed XD" . "<br/>";
}
echo "Done!" . "<br/>";
?>
<?php include_once("header.php");?>
