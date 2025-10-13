<?php
$errors = array();
$product = isset($_POST['product']) ? $_POST['product'] : '';
$qty = isset($_POST['qty']) ? $_POST['qty'] : '';
$summary = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate product
    if (!in_array($product, ['usb','ssd','mouse'])) {
        $errors[] = 'Invalid product selected.';
    }
    // Validate quantity
    if (!ctype_digit($qty) || (int)$qty <= 0) {
        $errors[] = 'Quantity must be a positive integer.';
    }
    // Calculate and show summary
    if (empty($errors)) {
        if ($product == 'usb') {
            $productName = 'USB';
            $productPrice = 250;
        } elseif ($product == 'ssd') {
            $productName = 'SSD';
            $productPrice = 2000;
        } elseif ($product == 'mouse') {
            $productName = 'Mouse';
            $productPrice = 350;
        }
        $qtyInt = (int)$qty;
        $total = $productPrice * $qtyInt;
        $summary = "<h3>Order Summary</h3>" .
            "Product: " . htmlspecialchars($productName) . "<br>" .
            "Quantity: $qtyInt<br>" .
            "Total: P" . number_format($total, 2);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
        <div class="container">
    <?php
    if (!empty($errors)) {
        echo '<p style="color:red;">';
        if (isset($errors[0])) echo 'Error: ' . htmlspecialchars($errors[0]) . '<br>';
        if (isset($errors[1])) echo 'Error: ' . htmlspecialchars($errors[1]) . '<br>';
        if (isset($errors[2])) echo 'Error: ' . htmlspecialchars($errors[2]) . '<br>';
        echo '</p>';
    }
    if ($summary) {
        echo '<div style="color:green;">' . $summary . '</div>';
    }
    ?>
    <form method="POST" action="orderForm.php">
        <input type="hidden" name="product" value="<?php echo htmlspecialchars($product); ?>">
        <input type="hidden" name="qty" value="<?php echo htmlspecialchars($qty); ?>">
        <button type="submit">Back</button>
    </form>
    </div>
</body>

</html>