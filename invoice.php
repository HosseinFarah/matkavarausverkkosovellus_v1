<?php
// Start output buffering
ob_start();

include 'asetukset.php'; // Include settings file
include 'db.php'; // Include database connection file

// Include necessary files
require_once 'lang.php'; // Ensure this file has no output
require_once 'fpdf.php'; // Ensure this file has no output

// Set the content type to UTF-8
header('Content-Type: text/html; charset=utf-8');

// Get parameters safely
$reservation_id = isset($_GET['id']) ? $_GET['id'] : 0;
$tour_id = isset($_GET['tour_id']) ? $_GET['tour_id'] : 0;
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

// Prepare SQL query
$sql = "SELECT * FROM reservations 
        LEFT JOIN tours ON reservations.tour_id = tours.id 
        LEFT JOIN users ON reservations.user_id = users.id 
        WHERE reservations.reservation_id = '$reservation_id' 
        AND reservations.user_id = $user_id 
        AND reservations.tour_id = $tour_id";

// Execute the query
$result = my_query($sql);
$row = $result->fetch_assoc();

if ($row) {
    // Data extraction
    $tour_name = $row['name'];
    $tour_price = $row['price'];
    $user_name = $row['firstname'] . ' ' . $row['lastname'];
    $user_email = $row['email'];
    $user_phone = $row['mobilenumber'];
    $reservation_date = $row['created'];
    $reservation_code = $row['reservation_id'];
    $tour_image = 'profiilikuvat/tours/' . $row['tourImage']; // Corrected path

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set margins
    $pdf->SetMargins(10, 10, 10); // Set left, top, and right margins

    // Set Jura font for the title
    $pdf->AddFont('Jura', '', 'Jura-Regular.php'); // Add Jura font
    $pdf->SetFont('Jura', '', 20); // Bold font for the title
    $pdf->Cell(0, 10, 'Tour Reservation Details', 0, 1, 'C'); // Centered title
    $pdf->Ln(10); // Add some space after the title

    // Set font for the content
    $pdf->SetFont('Jura', '', 14); // Regular font for content
    $pdf->SetTextColor(0, 102, 204); // Set text color (blue)

    // Use MultiCell for wrapping text
    $pdf->Cell(0, 10, 'Tour Name: ' . $tour_name, 0, 1);
    $pdf->Cell(0, 10, 'Tour Price: ' . number_format($tour_price, 2) . '', 0, 1); // Format price
    $pdf->MultiCell(0, 10, 'User Name: ' . $user_name, 0, 1);
    $pdf->Cell(0, 30, 'User Email: ' . $user_email, 0, 1);
    $pdf->Cell(0, 10, 'User Phone: ' . $user_phone, 0, 1);
    $pdf->Cell(0, 10, 'Reservation Date: ' . date('d-m-Y', strtotime($reservation_date)), 0, 1); // Format date
    $pdf->Cell(0, 10, 'Reservation Code: ' . $reservation_code, 0, 1);
    $pdf->Ln(10); // Add space before image

    // Display image with a border and better position
    $pdf->Image($tour_image, 150, 30, 50, 50);

    // Add a footer
    $pdf->SetY(-90); // Adjust this to give enough space for text and logo
    $pdf->SetFont('Jura', '', 12); // Use Jura font for footer
    $pdf->SetTextColor(128); // Set text color (grey)
    $pdf->Cell(0, 10, 'Thank you for your reservation!', 0, 1, 'C'); // Centered footer
    $pdf->Ln(2); // Add a bit of space before the next lines

    // Add company details
    $pdf->Cell(0, 10, 'Huvimatkat Oy', 0, 1, 'C'); // Company name centered
    $pdf->Cell(0, 10, 'asiakaspalvelu@huvimatka.fi', 0, 1, 'C'); // Email centered
    $pdf->Cell(0, 10, 'Rautatienkatu 21, 33100 Tampere', 0, 1, 'C'); // Address centered

    // Display the logo at the bottom of the footer
    $pdf->Image('HuviMatka.png', 85, 250, 40, 40); // Adjust position and size as needed

    // Output PDF
    $pdf->Output();
    ob_end_flush(); // End output buffering and send output to browser
    exit; // Stop further script execution
} else {
    // Clean output buffer and show no data found message
    ob_end_clean(); // Clear any buffered output
    echo 'No data found';
}

// Optionally include footer only if necessary and it doesn't produce output
// include "footer.php"; 
?>
