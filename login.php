<html>
<?php 
include "connect.php"; //to use the vars 
session_start(); //starts a session for other pages to use 
$patient = "PATIENT";
$doctor = "DOCTOR";

function error($errlog, $errmsg) {
    error_log("[".$_SERVER["PHP_SELF"]."] error: (".$errmsg.") ".$errlog);
    throw new Exception($errmsg);
}

try {
    $link = mysqli_connect($HOST, $USER, $PASS, $DATABASE); 
    if (!$link) { //check the connection 
        error(mysqli_connect_error(), "Couldn't connect to database.");
    }
    
    $query = "SELECT username, password, Role, ID ". 
            "FROM USER ". 
            "WHERE username = ? AND password = SHA(?)"; 
    $stmt = mysqli_prepare($link, $query); //prepared statement to check the user input 
    if (mysqli_errno($link) || !$stmt) { //check query 
        error(mysqli_error($link), "Error preparing query."); 
    }

    mysqli_stmt_bind_param($stmt, 'ss', $_POST["username"], $_POST["password"]); // bind user input 
    if (mysqli_stmt_errno($stmt)) { //check query again after binding the user input
        error(mysqli_stmt_error($stmt), "Error binding parameters."); 
    }

    mysqli_stmt_execute($stmt); //execute the statement with user input 
    if (mysqli_stmt_errno($stmt)) { //check the query after executing it 
        error(mysqli_stmt_error($stmt), "Error executing query."); 
    } 

    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) { 
        // Bind result variables to fetch actual data
        mysqli_stmt_bind_result($stmt, $username, $password, $role, $id);
        mysqli_stmt_fetch($stmt);

        // Set session variables
        $_SESSION["ID"] = $id;

        if ($role == $patient) { 
            $host = $_SERVER["HTTP_HOST"]; // webserver current file is on
            $dir = rtrim(dirname($_SERVER["PHP_SELF"]), '/\\'); // directory current file is in
            $filename = "viewpatient.php"; // actual file to redirect to
            header("Location: http://".$host.$dir."/".$filename);
            exit();
        } else if($role == $doctor) { 
            $host = $_SERVER["HTTP_HOST"]; // webserver current file is on
            $dir = rtrim(dirname($_SERVER["PHP_SELF"]), '/\\'); // directory current file is in
            $filename = "viewdoctor.php"; // actual file to redirect to
            header("Location: http://".$host.$dir."/".$filename);
            exit();
        }
    } else { 
        echo "<p>Invalid username or password.</p>";
    }
} catch (Exception $e) { 
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

if ($stmt) { //close statement 
    mysqli_stmt_close($stmt);
}

if ($link) { //close connection 
    mysqli_close($link);
}
?> 
</html>