<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration - UPDATED TO MATCH YOUR phpMyAdmin SCREENSHOT
$host = "localhost";
$username = "distinct_Karl"; 
$password = "tadiwanashe2"; // Use actual password in production
$dbname = "distinct_LIFSA"; // 

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => "Database connection failed: " . $conn->connect_error,
        'details' => [
            'host' => $host,
            'user' => $username,
            'dbname' => $dbname
        ]
    ]));
}

// Verify tables exist
$tablesExist = true;
$requiredTables = ['individual_registrations', 'group_registrations'];
foreach ($requiredTables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows == 0) {
        $tablesExist = false;
        break;
    }
}

if (!$tablesExist) {
    die(json_encode([
        'success' => false,
        'message' => "Required database tables are missing",
        'tables' => $requiredTables
    ]));
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['registration_type'])) {
            throw new Exception("Registration type not specified");
        }

        // Log received data for debugging
        error_log("Received registration data: " . print_r($_POST, true));

        if ($_POST['registration_type'] === 'individual') {
            // Process individual registration
            $stmt = $conn->prepare("INSERT INTO individual_registrations 
                (training_id, first_name, last_name, email, phone, organization, city, country) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $bound = $stmt->bind_param("isssssss", 
                $_POST['training_id'],
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['organization'],
                $_POST['city'],
                $_POST['country']
            );
            
            if (!$bound) {
                throw new Exception("Bind failed: " . $stmt->error);
            }
            
            $executed = $stmt->execute();
            
            if ($executed) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Individual registration successful! Your training details have been saved.',
                    'insert_id' => $stmt->insert_id
                ]);
            } else {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
        } elseif ($_POST['registration_type'] === 'group') {
            // Process group registration
            $stmt = $conn->prepare("INSERT INTO group_registrations 
                (first_name, last_name, email, phone, organization, city, country, participants_count, locations_involved) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $bound = $stmt->bind_param("sssssssii", 
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['organization'],
                $_POST['city'],
                $_POST['country'],
                $_POST['participants_count'],
                $_POST['locations_involved']
            );
            
            if (!$bound) {
                throw new Exception("Bind failed: " . $stmt->error);
            }
            
            $executed = $stmt->execute();
            
            if ($executed) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Group registration successful! We will contact you shortly with more details.',
                    'insert_id' => $stmt->insert_id
                ]);
            } else {
                throw new Exception("Execute failed: " . $stmt->error);
            }
        }// Add this to the existing POST handling section
elseif ($_POST['registration_type'] === 'online') {
    // Process online course registration
    $stmt = $conn->prepare("INSERT INTO online_course_registrations 
        (course_date, course_fee, first_name, last_name, email, phone, organization, city, country) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $bound = $stmt->bind_param("sssssssss", 
        $_POST['course_date'],
        $_POST['course_fee'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['organization'],
        $_POST['city'],
        $_POST['country']
    );
    
    if (!$bound) {
        throw new Exception("Bind failed: " . $stmt->error);
    }
    
    $executed = $stmt->execute();
    
    if ($executed) {
        echo json_encode([
            'success' => true,
            'message' => 'Online course booking confirmed! You will receive joining instructions via email.',
            'insert_id' => $stmt->insert_id
        ]);
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }
}
    } catch (Exception $e) {
        error_log("Registration Error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'error_details' => $conn->error ?? null
        ]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Please use POST.'
    ]);
}
?>