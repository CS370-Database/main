<?php
$import_attempted = false;
$import_succeeded = false;
$import_error_message = "";
if( $_SERVER[ "REQUEST_METHOD"] == "POST") {
    $import_attempted = true;
    $con = @mysqli_connect("localhost:3306", "root", "password", "movie_db");
    if(mysqli_connect_errno()){
        $import_error_message = "Failed to connect to MySQL: "
            . mysqli_connect_error();
    } else {
        try {
            $contents = file_get_contents($_FILES["importFile"]["tmp_name"]);
            $lines = explode("\n", $contents);
            $update_counter=0;
            $insert_counter=0;
            foreach($lines as $line) {
                $parsed_csv_line = str_getcsv($line);
                $fname = $parsed_csv_line[0];
                $lname = $parsed_csv_line[1];
                $gender = $parsed_csv_line[2];
                $sql_select = 'SELECT * FROM actor WHERE Fname="'.$fname.'" AND Lname="'.$lname.'"';
                $result = $con->query($sql_select);
                if($result->num_rows > 0) {
                    $sql_update = 'UPDATE actor SET Gender="'.$gender.'" WHERE Fname="'.$fname.'" AND Lname="'.$lname.'"';
                    $update_counter++;
                    $con->query($sql_update);
                }
                else {
                    $sql_insert = 'INSERT INTO actor (Fname, Lname, Gender) VALUES ("'.$fname.'","'.$lname.'","'.$gender.'")';
                    $insert_counter++;
                    $con->query($sql_insert);
                }
            }
            echo "Uploaded " . $update_counter+$insert_counter . " rows of data." . "<br/>";
            echo $update_counter . " rows were updated. <br/>";
            echo $insert_counter . " rows were inserted. <br/>";
            $import_succeeded = true;
        }
        catch(Error $exception) {
            $import_error_message = $exception->getMessage()
                . " at: " . $exception->getFile()
                . " (line " . $exception->getLine() . ") <br/>";
        }
    }
}

?>
<?php include_once("header.php");?>
    <head>
        <title>
            Actor Data Import
        </title>
    </head>
    <body>
<h1> Actor Data Import </h1>
<p1> Upload a CSV file with the new actor data to add the data to the Actor Table or update existing records.</p1>
<br>
<br>
<?php
if($import_attempted){
    if($import_succeeded) {?>
        <h1><span style="color: green;">Import Succeeded!</span></h1>
    <?php } else { ?>
        <h1><span style="color: red;">Import Failed!</span></h1>
        <span style="color: red;"><?php echo $import_error_message ?></span>
    <?php }
}
?>
<form method="post" enctype="multipart/form-data">
    <div class="input-group mb-3">
        <span class="input-group-text">File: </span>
        <input type="file" name="importFile"/>
    </div>
    <br/><br/>
    <img src="https://static1.colliderimages.com/wordpress/wp-content/uploads/2022/03/11-Great-Movies-About-Being-an-Actor.jpg" style="width: 800px; height: 350px" alt="MovieImage">
    <br/><br/>
    <input type="submit" value="Upload Data"/>
</form>
<?php include_once("footer.php");?>