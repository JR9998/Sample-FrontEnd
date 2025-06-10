<html> 
  <head> 
  <title> 
    Doctor Dashboard
  </title>
  <style> 
        td { 
            border: 2px solid black; 
            padding: 5px; 
        }
        body { 
            background-color:lightcyan;
        }
    </style>
  </head>
  <body>
  <h1> 
    Dashboard
  </h1>

  <?php 
  include "connect.php"; //to use vars in other file
  session_start(); //uses the session that was set in the other pages 
  function error ( $errlog, $errmsg ) {
    error_log("[".$_SERVER["PHP_SELF"]."] error: (".$errmsg.") ".
              $errlog);
    throw new Exception($errmsg);
  }

  try { 
  $link = mysqli_connect($HOST, $USER, $PASS, $DATABASE); //establishing connection to database
  if ( !$link ) {//check the connection
    error(mysqli_connect_error(),"Couldn't connect to database.");
  }
    $query = "SELECT pat_id, pat_fname, pat_lname, date, time, duration, reason, status ".
         "FROM doctor_schedule ". //view that was created
         "WHERE ID = '". $_SESSION["ID"]."'";

  $results = mysqli_query($link, $query);
  if ( !$results ) {//check query 
    error(mysqli_connect_error(),"Error executing query.");
  }
 ?>
 <h3> Schedule:</h3>
<table>
   <tr>
     <th>Patient First Name </th>
     <th>Patient Last Name</th>
     <th>Date </th>
     <th>Time</th>
     <th>Duration</th>
     <th>Reason</th>
     <th>Status</th>
   </tr>
 <?php
  while($row = mysqli_fetch_assoc($results)) { //while loop that goes through the query results and prints as table on the page
    echo "<tr>";
    echo "<td>" .$row['pat_fname']. "</td>";
    echo "<td>" .$row['pat_lname']. "</td>";
    echo "<td>" .$row['date']. "</td>";
    echo "<td>" .$row['time']. "</td>";
    echo "<td>" .$row['duration']. "</td>";
    echo "<td>" .$row['reason']. "</td>";
    echo "<td>" .$row['status']. "</td>";
    echo "</tr>";
  }//end of while loop  
 ?>
 </table>


 <?php
  } catch(Exception $e) { 
  ?> 
  <blockquote> 
    <p> 
      <?php 
      echo $e->getMessage();
      ?>
    </p>
  </blockquote>
  <?php
  } //end of catch statement

  if ($results) { //free results
    mysqli_free_result($results);
  }
  if ($link) { //close connection
    mysqli_close($link);
  }
  ?>

  </body>
</html>