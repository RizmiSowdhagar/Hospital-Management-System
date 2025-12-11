<?php
// Database connection parameters
$tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = prophet.njit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=course)))";
$username = 'sa2923';
$password = 'Loveyourself@136';

// Establishing connection to the database
try {
    $pdo = new PDO("oci:dbname=" . $tns, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to retrieve appointments for a given date and physician
function getAppointments($date, $physician, $pdo) {
    try {
        // SQL query to retrieve appointments
        $sql = "SELECT m.P_ID, p.FNAME, p.LNAME, TO_CHAR(m.DATE_TIME, 'YYYY-MM-DD HH24:MI:SS') AS appointment_datetime
        FROM MAKES_APPT m
        JOIN PATIENT p ON p.P_ID = m.P_ID
        JOIN DOCTOR d ON d.EMPID = m.EMPID
        WHERE TRUNC(m.DATE_TIME) = TO_DATE(:selectedDate, 'YYYY-MM-DD')
        AND d.SPECIALITY = :selectedPhysician
        ORDER BY m.DATE_TIME";

        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':selectedDate', $date);
        $stmt->bindParam(':selectedPhysician', $physician);
        $stmt->execute();
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return appointments as JSON
        return json_encode($appointments);

    } catch (PDOException $e) {
        return json_encode(['error' => 'Error executing query: ' . $e->getMessage()]);
    }
}

// Retrieve date and physician from request parameters
$date = $_POST['date'] ?? ''; // Assuming this value is coming from a form
$physician = $_POST['physician'] ?? ''; // Assuming this value is coming from a form

// Example usage: Get appointments for a selected date and physician
if (!empty($date) && !empty($physician)) {
    echo getAppointments($date, $physician, $pdo);
}

// Close the connection
$pdo = null;
?>
