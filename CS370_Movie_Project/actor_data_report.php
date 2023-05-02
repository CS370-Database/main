<?php
$connection_error = false;
$connection_error_message = "";

$con = @mysqli_connect("localhost", "root", "password", "movie_db");
if(mysqli_connect_errno()) {
    $connection_error = true;
    $connection_error_message = "Failed to connect to MySQL: " . mysqli_connect_error();
}

function output_error($title, $error) {
    echo "<span style='color: red;'>\n";
    echo "<h2>" . $title . "</h2>\n";
    echo "<h4>" . $error . "</h4>\n";
    echo "</span>";
}
?>

<?php include_once("header.php");?>
    <head>
        <title>Actors and Their Roles</title>
    </head>
    <style>
        .actorDataHeaderRow td {padding-right: 20px;}
        .actorDataRow td {padding-left: 10px;}
        .actorDataDetailsCell {padding-left: 30px; !important;}
    </style>
    <body>
    <h1>Actors and Their Roles</h1>
<?php if( $connection_error ){
    output_error("Database connection failure!", $connection_error_message);
} else {
    function output_table_open() {
        echo "<table class='table table-striped'>\n";
        echo "<thead><tr class='actorDataHeaderRow'>\n";
        echo "    <td>First Name</td>\n";
        echo "    <td>Last Name</td>\n";
        echo "    <td>Gender</td>\n";
        echo "</tr></thead>\n";
    }
    function output_table_close() {
        echo "</table>\n";
    }

    function output_person_row($fname, $lname, $gender) {
        echo "<tr class='actorDataRow'>\n";
        echo "    <td>" . $fname . "</td>\n";
        echo "    <td>" . $lname . "</td>\n";
        echo "    <td>" . $gender . "</td>\n";
        echo "</tr>\n";
    }

    function output_persons_detail_row($movies, $roles) {
        $totalstr = "";
        foreach($movies as $key => $value) {
            $totalstr .= "Movie: " . $value . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Role: " . $roles[$key]. "<br/>\n";
        }
        echo "<tr>\n";
        echo "    <td colspan='3' class='actorDataDetailsCell'>\n";
        echo "        $totalstr";
        echo "    </td>\n";
        echo "</tr>\n";
    }

    $query =  " SELECT t0.Fname, t0.Lname, t0.Gender, t2.Name as Title, t1.Role"
        .     " FROM actor t0"
        .     " LEFT OUTER JOIN movie_actors t1 on t0.Actor_ID=t1.Actor_Actor_ID"
        .     " LEFT OUTER JOIN movie t2 ON t1.Movie_Movie_ID=t2.Movie_ID"
        .     " ORDER BY t0.Fname, t2.Name" ;

    $result = mysqli_query($con, $query);
    if(!$result){
        if(mysqli_errno($con)) {
            output_error("Data retrieval failure!", mysqli_error($con));
        } else {
            echo "No Actor Data Found!\n";
        }
    } else {
        output_table_open();
        $last_fname = null;
        $last_lname = null;
        $movies = array();
        $roles = array();
        while($row = $result->fetch_array()) {
            if($last_fname != $row["Fname"] && $last_lname != $row["Lname"]) {
                if ($last_fname != null && $last_lname != null) {
                    output_persons_detail_row($movies, $roles);
                }
                output_person_row($row["Fname"], $row["Lname"], $row["Gender"]);
                $movies = array();
                $roles = array();
            }
            if(!in_array($row["Title"], $movies))
                $movies[] = $row["Title"];
            if(!in_array($row["Role"], $roles))
                $roles[] = $row["Role"];
            $last_lname=$row["Lname"];
            $last_fname=$row["Fname"];
        }
        if($last_fname != null && $last_lname != null) {
            output_persons_detail_row($movies, $roles);
        }
        output_table_close();
    }
}
?>
<?php include_once("footer.php");?>