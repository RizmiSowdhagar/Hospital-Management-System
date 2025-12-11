<?php

$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM OTHER_HCP");
    $stmt->execute();

    // Fetch all rows as an associative array
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>EMPID</th><th>JOB_TITLE</th></tr>";

    // Output table rows
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>".$row['EMPID']."</td>";
        echo "<td>".$row['JOB_TITLE']."</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "View OTHER HCP record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
