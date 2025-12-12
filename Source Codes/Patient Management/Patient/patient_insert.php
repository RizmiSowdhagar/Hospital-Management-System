<?php
// Retrieve form data
$p_id = $_POST['p_id'];
$fname = $_POST['fname'];
$minit = $_POST['minit'];
$lname = $_POST['lname'];
$street = $_POST['street'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip_code = $_POST['zip_code'];
$empid = $_POST['empid'];
$ins_id = $_POST['ins_id'];

// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement to insert form data into a database table (replace "your_table_name" with your actual table name)
    $stmt = $conn->prepare("INSERT INTO PATIENT (p_id, fname, minit, lname, street, city, state, zip_code,empid,ins_id)
                            VALUES (:p_id, :fname, :minit, :lname, :street, :city, :state, :zip_code,:empid,:ins_id)");

    // Bind parameters
    $stmt->bindParam(':p_id', $p_id);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':minit', $minit);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':street', $street);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':zip_code', $zip_code);
    $stmt->bindParam(':empid', $empid);
    $stmt->bindParam(':ins_id', $ins_id);

    // Execute the statement
    $stmt->execute();

    echo "Form submitted successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
