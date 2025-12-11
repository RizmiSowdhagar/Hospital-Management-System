<?php

$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM ADMIN");
    $stmt->execute();

    // Fetch all rows as an associative array
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>EMPID</th><th>JOBTITLE</th></tr>";

    // Output table rows
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>".$row['EMPID']."</td>";
        echo "<td>".$row['JOBTITLE']."</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "View ADMIN record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
