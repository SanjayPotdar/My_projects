<?php
require('fpdf.php'); // Include the FPDF library

// Create a PDF object
$pdf = new FPDF();
$pdf->AddPage();
date_default_timezone_set('Asia/Kolkata');

// Set font for the receipt
$pdf->SetFont('Arial', '', 12);

// Output the app name at the top with a different and attractive color and style
$pdf->SetTextColor(0, 102, 204); // Attractive blue color
$pdf->SetFont('Arial', 'B', 20); // Larger and bold
$pdf->Cell(0, 10, 'BookMyPlate', 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0); // Reset text color (black)
$pdf->SetFont('Arial', '', 12); // Reset font

// Output other order details
// Output the order ID to the PDF
$pdf->Cell(60, 10, 'Order ID: ', 0);
$pdf->Cell(0, 10, $lastInsertId, 0, 1);
$pdf->Cell(60, 10, 'Customer Name: ', 0);
$pdf->Cell(0, 10, $customerName, 0, 1);
$pdf->Cell(60, 10, 'Hotel Name: ', 0);
$pdf->Cell(0, 10, $hotelName, 0, 1);
$pdf->Cell(60, 10, 'Location: ', 0);
$pdf->Cell(0, 10, $location, 0, 1);
$pdf->Cell(60, 10, 'Number of Persons: ', 0);
$pdf->Cell(0, 10, $numPersons, 0, 1);
$pdf->Cell(60, 10, 'Date: ', 0);
$pdf->Cell(0, 10, $orderDate, 0, 1);
$pdf->Cell(60, 10, 'Time: ', 0);
$pdf->Cell(0, 10, $orderTime, 0, 1);

// Output menu items on the left and their prices on the right
$pdf->Ln(10); // Add some spacing
$pdf->Cell(0, 10, 'Menu Items', 0, 1);

// Insert order details into the details table
foreach ($selectedItemData as $key => $item) {
    $itemName = $item['item_name'];
    $itemPrice = $item['item_price'];
    $quantity = $item['quantity'];
    $itemType = isset($itemTypes[$key]) ? $itemTypes[$key] : "Not available";
    $itemTotal = $itemPrice * $quantity;

    // Output line before menu item
    $pdf->SetLineWidth(0.2);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

    // Output menu item on the left along with price, quantity, and multiplied value
    $pdf->Cell(100, 10, "- $itemName (Price: Rs. " . number_format($itemPrice, 2) . ") x$quantity", 0, 0);

    // Output item total (multiplied value) on the right
    $pdf->Cell(0, 10, "Rs. " . number_format($itemTotal, 2), 0, 1, 'R');

    // Output line after menu item
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

    // Insert menu item, quantity, item type, and d_id into the orders table
    $sql = "INSERT INTO orders (`dish_name`, `quantity`, `d_id`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $itemName, $quantity, $lastInsertId); // Use the stored lastInsertId

    if ($stmt->execute() !== TRUE) {
        echo "Error: " . $stmt->error;
    }
}

// Output a line after the menu items
$pdf->Ln(10); // Add some spacing

// Output the total price aligned with the individual item prices
$pdf->Cell(140, 10, 'Total Price:', 0);
$pdf->Cell(0, 10, "Rs. " . number_format($totalPrice, 2), 0, 1, 'R');
$pdf->SetLineWidth(0.2);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

// Output the date and time in separate lines
$pdf->Cell(0, 10, 'Order Date: ' . date("Y-m-d"), 0, 1);
$pdf->Cell(0, 10, 'Order Time: ' . date("h:i A"), 0, 1);

// Define the file name for the PDF
$pdfFileName = 'order_receipt' . date('Ymd_His') . '.pdf';

// Output the PDF as a file for download
$pdf->Output($pdfFileName, 'F'); // Use 'F' to save the PDF to a file

// Return the generated PDF file name
echo $pdfFileName;
?>
