<html> 
  <head> 
  <title> 
    Patient Dashboard
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
    $query = "SELECT date, time, DocID, Specialization, Address, Reason, Status ".
         "FROM PatientDashboard ". //view that was created
         "WHERE patID = '". $_SESSION["ID"]."'";
    $query_prescriptions = "SELECT medname, dosage ".
         "FROM PRESCRIBES ".
         "WHERE PatID = '". $_SESSION["ID"]."'";

$prescription_results = mysqli_query($link, $query_prescriptions);
if (!$prescription_results) { // Check query
error(mysqli_error($link), "Error executing prescription query.");
}

  $results = mysqli_query($link, $query);
  if ( !$results ) {//check query 
    error(mysqli_connect_error(),"Error executing query.");
  }
 ?>
 <h3> Appointments:</h3>
<table>
   <tr>
     <th>Date</th>
     <th>Time</th>
     <th>Doctor ID</th>
     <th>Specialization</th>
     <th>Address</th>
     <th>Reason</th>
     <th>Status</th>
   </tr>
 <?php
  while($row = mysqli_fetch_assoc($results)) { //while loop that goes through the query results and prints as table on the page
    echo "<tr>";
    echo "<td>" .$row['date']. "</td>";
    echo "<td>" .$row['time']. "</td>";
    echo "<td>" .$row['DocID']. "</td>";
    echo "<td>" .$row['Specialization']. "</td>";
    echo "<td>" .$row['Address']. "</td>";
    echo "<td>" .$row['Reason']. "</td>";
    echo "<td>" .$row['Status']. "</td>";
    echo "</tr>";
  }//end of while loop  
 ?>
 </table>

 <h3>Prescriptions: </h3>
 <table> 
    <tr> 
        <th>Medication Name</th>
        <th>Dosage</th>
    </tr>
 <?php 
 if (mysqli_num_rows($prescription_results) > 0) {//checks to see if any row was returned, if not then no prescription is printed 
 while ($prescription = mysqli_fetch_assoc($prescription_results)) {
    echo "<tr>";
    echo "<td>" . $prescription['medname'] . "</td>";
    echo "<td>" . $prescription['dosage'] . "</td>";
    echo "</tr>";
 }
} else { 
    echo "<p>You don't have any prescriptions yet!</p>";
}
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