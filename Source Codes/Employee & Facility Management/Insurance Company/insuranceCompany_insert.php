<?php
// Retrieve form data
$ins_id = $_POST['ins_id'];
$name = $_POST['name'];
$street = $_POST['street'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip_code = $_POST['zip_code'];


// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement to insert form data into a database table 
    $stmt = $conn->prepare("INSERT INTO INSURANCECOMPANY (ins_id, name, street, city, state, zip_code)
    VALUES (:ins_id, :name, :street, :city, :state, :zip_code)");

    // Bind parameters
    $stmt->bindParam(':ins_id', $ins_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':street', $street);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':zip_code', $zip_code);


    $stmt->execute();

    
    echo "Insurance Company record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
