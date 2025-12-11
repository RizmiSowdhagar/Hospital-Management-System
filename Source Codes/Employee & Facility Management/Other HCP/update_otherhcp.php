<?php
// Retrieve form data
$empid = $_POST['empid'];

// Connect to Oracle Database (replace these values with your actual database credentials)
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve original values from the database
    $stmt = $conn->prepare("SELECT empid, job_title FROM OTHER_HCP WHERE EMPID=:empid");
    $stmt->bindParam(':empid', $empid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if new values are provided and update each field individually
        if (!empty($_POST["job_title"])) {
            $job_title = $_POST["job_title"];
        } else {
            $job_title = $row['JOB_TITLE'];
        }
    }    

        // Prepare and execute update statements for each field
        $stmt = $conn->prepare("UPDATE OTHER_HCP SET JOB_TITLE = :job_title WHERE EMPID = :empid");
        $stmt->bindParam(':job_title', $job_title);
        $stmt->bindParam(':empid', $empid);
        $stmt->execute();

    echo "Form updated successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
