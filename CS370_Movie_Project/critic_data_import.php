<?php
$import_attempted = false;
$import_succeeded = false;
$import_error_message = "";
if( $_SERVER[ "REQUEST_METHOD"] == "POST") {
    $import_attempted = true;
    $con = @mysqli_connect("localhost", "critic_username", "critic_password", "critic_db");
    if(mysqli_connect_errno()){
        $import_error_message = "Failed to connect to MySQL: "
            . mysqli_connect_error();
    } else {
        try {
            $contents = file_get_contents($_FILES["importFile"]["tmp_name"]);
            $lines = explode("/n", $contents);
            foreach($lines as $line) {
                $parsed_csv_line = str_getcsv($line);
                //TODO: do something with the parsed data
                //Check if columns in table, then check if certain columns in other tables.
                // For full credit, track how many rows were inserted vs updated in each entity
                echo implode("; ", $parsed_csv_line) . "<br/>";
            }
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
            Critic Data Import
        </title>
    </head>
    <body>
<h1> Critic Data Import </h1>
<p1> Upload a CSV file with the new critic data to add the data to the Critic Table</p1>
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
    <input type="submit" value="Upload Data"/>
</form>
<?php include_once("footer.php");?>