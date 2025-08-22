<?php
// Start output buffering as the very first thing to prevent header errors.
ob_start();

// These lines force all PHP errors to be displayed in the browser.
// This is essential for debugging and should be removed in a live production environment.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// end of displaying errors in the browser

// Check if all required POST values exist
if (
    isset($_POST["name"], $_POST["phone"], $_POST["date"], $_POST["visitors"], $_POST["destination"], $_POST["textarea"])
) {
    // Database connection variables
    $servername = "127.0.0.1";
    $username   = "Tigru";
    $password   = "parola1234";
    $dbname     = "turism_form";

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

   
    // Explicitly format the date and visitors count to prevent the `Incorrect datetime value` error.
    // This is the most reliable way to ensure the data types are correct before the SQL query.
    // Debug: Let's see what date value we're actually receiving
    echo "DEBUG - Received date value: '" . $_POST["date"] . "'<br>";

    // Validate and format the date properly
    $date_input = trim($_POST["date"]);
    if (empty($date_input)) {
        die("Date field is empty.");
    }

    // Try to create DateTime object and handle all possible errors
    try {
        $date = new DateTime($date_input);
        $formatted_date = $date->format('Y-m-d');
        echo "DEBUG - Formatted date: '" . $formatted_date . "'<br>";
    } catch (Exception $e) {
        die("Invalid date format submitted. Received: '" . $date_input . "' Error: " . $e->getMessage());
    }

// Explicitly cast the visitors value to an integer
$visitors = intval($_POST["visitors"]);
echo "DEBUG - Visitors: " . $visitors . "<br>";

    
    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("
        INSERT INTO `tabela_turism` (name, phone, date, visitors, destination, textarea)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    // Check if the prepared statement was successful
    if (!$stmt) {
        die("SQL Preparation Failed: " . $conn->error);
    }

    // Bind parameters with correct data types
    // ssisss stands for: string, string, integer, string, string, string.
    $stmt->bind_param(
        "sssiss",  
        $_POST["name"],
        $_POST["phone"],
        $formatted_date,    
        $visitors,          
        $_POST["destination"],
        $_POST["textarea"]
    );

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Close the statement and connection
        $stmt->close();
        $conn->close();

        // Redirect to the success page
        header("Location: form-validation.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection in case of an error
    $stmt->close();
    $conn->close();
}
// Flush the output buffer and send to the browser
ob_end_flush();
?>