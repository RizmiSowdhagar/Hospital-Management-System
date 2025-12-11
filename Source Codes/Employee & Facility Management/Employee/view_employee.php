<?php

$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = "sa2923";
$password = "Loveyourself@136";

try {
    $conn = new PDO("oci:dbname=".$tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM EMP");
    $stmt->execute();

    // Fetch all rows as an associative array
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>EMPID</th><th>SSN</th><th>FNAME</th><th>MINIT</th><th>LNAME</th><th>STREET</th><th>CITY</th><th>STATE</th><th>ZIP_CODE</th><th>SALARY</th><th>HIRE_DATE</th><th>JOB_CLASS</th><th>FACID</th></tr>";

    // Output table rows
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>".$row['EMPID']."</td>";
        echo "<td>".$row['SSN']."</td>";
        echo "<td>".$row['FNAME']."</td>";
        echo "<td>".$row['MINIT']."</td>";
        echo "<td>".$row['LNAME']."</td>";
        echo "<td>".$row['STREET']."</td>";
        echo "<td>".$row['CITY']."</td>";
        echo "<td>".$row['STATE']."</td>";
        echo "<td>".$row['ZIP_CODE']."</td>";
        echo "<td>".$row['SALARY']."</td>";
        echo "<td>".$row['HIRE_DATE']."</td>";
        echo "<td>".$row['JOB_CLASS']."</td>";
        echo "<td>".$row['FACID']."</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "View employee record created successfully";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close database connection
$conn = null;
?>
