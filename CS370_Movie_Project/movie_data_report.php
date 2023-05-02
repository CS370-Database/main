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
    <title>Movie Catalog</title>
</head>
<style>
    .movieDataHeaderRow td {padding-right: 20px;}
    .movieDataRow td {padding-left: 10px;}
    .movieDataDetailsCell {padding-left: 30px; !important;}
</style>
<body>
<h1>Movie Catalog</h1>
<?php if( $connection_error ){
    output_error("Database connection failure!", $connection_error_message);
} else {
    function output_table_open() {
        echo "<table class='table table-striped'>\n";
        echo "<thead><tr class='movieDataHeaderRow'>\n";
        echo "    <td>Title</td>\n";
        echo "    <td>Rating</td>\n";
        echo "    <td>Studio</td>\n";
        echo "</tr></thead>\n";
    }
    function output_table_close() {
        echo "</table>\n";
    }

    function output_movie_row($title, $rating, $studio) {
        echo "<tr class='movieDataRow'>\n";
        echo "    <td>" . $title . "</td>\n";
        echo "    <td>" . $rating . "</td>\n";
        echo "    <td>" . $studio . "</td>\n";
        echo "</tr>\n";
    }

    function output_movie_detail_row($directors, $genres) {
        $directors_str = "None";
        if( sizeof($directors) > 0) {
            $directors_str = implode(", ", $directors);
        }
        $genres_str = "None";
        if(sizeof($genres) > 0) {
            $genres_str = implode(", ", $genres);
        }
        echo "<tr>\n";
        echo "    <td colspan='3' class='movieDataDetailsCell'>\n";
        echo "        Directors: " . $directors_str . "<br/>\n" ;
        echo "        Genres: " . $genres_str . "<br/>\n";
        echo "    </td>\n";
        echo "</tr>\n";
    }

    $query =  " SELECT t0.name, t0.rating, t1.name as studio, t3.Fname, t3.Lname, t5.Name as genre"
        .     " FROM movie t0"
        .     " LEFT OUTER JOIN studio t1 on t0.Studio_Studio_ID=t1.Studio_ID"
        .     " LEFT OUTER JOIN movie_directors t2 on t0.Movie_ID=t2.Movie_Movie_ID"
        .     " LEFT OUTER JOIN director t3 on t2.Director_Director_ID=t3.Director_ID"
        .     " LEFT OUTER JOIN movie_genre t4 on t0.Movie_ID=t4.Movie_Movie_ID"
        .     " LEFT OUTER JOIN genre t5 on t4.Genre_Genre_ID=t5.Genre_ID"
        .     " ORDER BY t0.name, t3.Fname" ;

  $result = mysqli_query($con, $query);
  if(!$result){
      if(mysqli_errno($con)) {
          output_error("Data retrieval failure!", mysqli_error($con));
      } else {
          echo "No Movie Data Found!\n";
      }
  } else {
      output_table_open();
      $last_name = null;
      $directors = array();
      $genres = array();
      while($row = $result->fetch_array()) {
          if($last_name != $row["name"]) {
              if($last_name != null) {
                  output_movie_detail_row($directors, $genres);
              }
              output_movie_row($row["name"], $row["rating"], $row["studio"]);
              $directors = array();
              $genres = array();
          }
          $fullname = $row["Fname"] . " " . $row["Lname"];
          if(!in_array($fullname, $directors))
              $directors[] = $fullname;
          if(!in_array($row["genre"], $genres))
              $genres[] = $row["genre"];
          $last_name = $row[ "name" ];
      }
      if($last_name != null) {
          output_movie_detail_row($directors, $genres);
      }
      output_table_close();
  }
}
?>
<?php include_once("footer.php");?>