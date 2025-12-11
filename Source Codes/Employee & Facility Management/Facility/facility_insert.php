<?php
// Retrieve form data
$facid = $_POST['facid'];
$fac_size = $_POST['fac_size'];
$fac_type = $_POST['fac_type'];
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
    $stmt = $conn->prepare("INSERT INTO FACILITY (facid, fac_size, fac_type, street, city, state, zip_code)
    VALUES (:facid, :fac_size, :fac_type, :street, :city, :state, :zip_code)");

    // Bind parameters
    $stmt->bindParam(':facid', $facid);
    $stmt->bindParam(':fac_size', $fac_size);
    $stmt->bindParam(':fac_type', $fac_type);
    $stmt->bindParam(':street', $street);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':zip_code', $zip_code);


    $stmt->execute();

    if ($fac_type === 'Medical Office') {
        // $facid = $_POST['facid'];
        $office_count = $_POST['office_count'];
        $stmt_medoffice = $conn->prepare("INSERT INTO OFFICE (facid, office_count) VALUES (:facid, :office_count)");
        $stmt_medoffice->bindParam(':facid', $facid);
        $stmt_medoffice->bindParam(':office_count', $office_count);
        $stmt_medoffice->execute();
    }
    elseif ($fac_type === 'Outpatient Surgery') {
        // $facid = $_POST['facid'];
        $room_count = $_POST['room_count'];
        $p_code = $_POST['p_code'];
        $descr = $_POST['descr'];
        $stmt_outpatient = $conn->prepare("INSERT INTO OUTPATIENT_SURGERY (facid, room_count, p_code, descr) VALUES (:facid, :room_count, :p_code, :descr)");
        $stmt_outpatient->bindParam(':facid', $facid);
        $stmt_outpatient->bindParam(':room_count', $room_count);
        $stmt_outpatient->bindParam(':p_code', $p_code);
        $stmt_outpatient->bindParam(':descr', $descr);
        $stmt_outpatient->execute();
    }


    echo "Facility and ", $fac_type, " record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
