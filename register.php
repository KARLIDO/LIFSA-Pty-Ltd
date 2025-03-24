<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$organization = $_POST['organization'];
$city = $_POST['city'];
$country = $_POST['country'];
$registration_type = $_POST['registration_type'];

$event_date = isset($_POST['event_date']) ? $_POST['event_date'] : NULL;
$venue = isset($_POST['venue']) ? $_POST['venue'] : NULL;
$fees = isset($_POST['fees']) ? $_POST['fees'] : "Pricing based on participants and locations";

$sql = "INSERT INTO registrations (first_name, last_name, email, phone, organization, city, country, event_date, venue, fees, registration_type) 
        VALUES ('$first_name', '$last_name', '$email', '$phone', '$organization', '$city', '$country', '$event_date', '$venue', '$fees', '$registration_type')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
