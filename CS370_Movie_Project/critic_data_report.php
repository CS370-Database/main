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
        <title>Critic Reviews</title>
    </head>
    <style>
        .criticDataHeaderRow td {padding-right: 20px;}
        .criticDataRow td {padding-left: 10px;}
        .criticDataDetailsCell {padding-left: 30px; !important;}
    </style>
    <body>
    <h1>Critic Reviews</h1>
<?php if( $connection_error ){
    output_error("Database connection failure!", $connection_error_message);
} else {
    function output_table_open() {
        echo "<table class='table table-striped'>\n";
        echo "<thead><tr class='criticDataHeaderRow'>\n";
        echo "    <td>Name</td>\n";
        echo "    <td>Age</td>\n";
        echo "    <td>Gender</td>\n";
        echo "</tr></thead>\n";
    }
    function output_table_close() {
        echo "</table>\n";
    }

    function output_person_row($fname, $lname, $gender) {
        echo "<tr class='criticDataRow'>\n";
        echo "    <td>" . $fname . "</td>\n";
        echo "    <td>" . $lname . "</td>\n";
        echo "    <td>" . $gender . "</td>\n";
        echo "</tr>\n";
    }

    function output_persons_detail_row($movies, $comments, $ratings) {
        $totalstr = "";
        if(sizeof($movies)!=0){
        foreach($movies as $key => $value) {
            $totalstr .= "Movie: " . $value . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rating: " . $ratings[$key]. "<br/>Review: ".$comments[$key]."<br/><br/>\n";
        }} else {
            $totalstr = "This critic has not posted any reviews yet!<br/>";
        }
        echo "<tr>\n";
        echo "    <td colspan='3' class='criticDataDetailsCell'>\n";
        echo "        $totalstr";
        echo "    </td>\n";
        echo "</tr>\n";
    }

    $query =  " SELECT t0.Fname, t0.Lname, t0.Gender, t1.Rating, t1.Comments, t2.Name as Title"
        .     " FROM critic t0"
        .     " LEFT OUTER JOIN critic_reviews t1 ON t0.Critic_ID=t1.Critic_Critic_ID"
        .     " LEFT OUTER JOIN movie t2 ON t1.Movie_Movie_ID=t2.Movie_ID"
        .     " ORDER BY t0.Fname, Title" ;

    $result = mysqli_query($con, $query);
    if(!$result){
        if(mysqli_errno($con)) {
            output_error("Data retrieval failure!", mysqli_error($con));
        } else {
            echo "No Critic Data Found!\n";
        }
    } else {
        output_table_open();
        $last_fname = null;
        $last_lname = null;
        $movies = array();
        $comments = array();
        $ratings = array();
        while($row = $result->fetch_array()) {
            if($last_fname != $row["Fname"] && $last_lname != $row["Lname"]) {
                if($last_fname != null && $last_lname != null) {
                    output_persons_detail_row($movies, $comments, $ratings);
                }
                output_person_row($row["Fname"], $row["Lname"], $row["Gender"]);
                $movies = array();
                $comments = array();
                $ratings = array();
            }
            if(!in_array($row["Title"], $movies) && !is_null($row["Title"]))
                $movies[] = $row["Title"];
            if(!in_array($row["Rating"], $ratings) && !is_null($row["Rating"]))
                $ratings[] = $row["Rating"];
            if(!in_array($row["Comments"], $comments) && !is_null($row["Comments"]))
                $comments[] = $row["Comments"];
            $last_fname = $row[ "Fname" ];
            $last_lname = $row["Lname"];
        }
        if($last_lname != null && $last_fname != null) {
            output_persons_detail_row($movies, $comments, $ratings);
        }
        output_table_close();
    }
}
?>
<?php include_once("footer.php");?>