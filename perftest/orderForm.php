<?php
$errors = array();
$product = isset($_POST['product']) ? $_POST['product'] : '';
$qty = isset($_POST['qty']) ? $_POST['qty'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate product
    if (!in_array($product, ['usb','ssd','mouse'])) {
        $errors[] = 'Invalid product selected.';
    }
    // Validate quantity
    if (!ctype_digit($qty) || (int)$qty <= 0) {
        $errors[] = 'Quantity must be a positive integer.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Order Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
        <div class="container">
    <h1>Product Order Form</h1>
    <h2>Place Your Order</h2>
    <form method="POST" action="orderSummary.php">
        <label for="product">Select Product:</label><br>
        <p>USB = PHP 250</p>
        <p>SSD = PHP 2000</p>
        <p>Mouse = PHP 350</p>
        <select name="product">
            <option value="usb" <?php if ($product == 'usb') echo 'selected'; ?>>USB</option>
            <option value="ssd" <?php if ($product == 'ssd') echo 'selected'; ?>>SSD</option>
            <option value="mouse" <?php if ($product == 'mouse') echo 'selected'; ?>>Mouse</option>
        </select>
        <input type="text" name="qty" value="<?php echo htmlspecialchars($qty); ?>">
        <button type="submit">Order</button>
    </form>
    <?php
    // Only show errors if the form was submitted by pressing Order (not on select change)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)) {
        echo '<p style="color:red;">';
        foreach ($errors as $err) {
            echo 'Error: '. htmlspecialchars($err);
        }
        echo '</p>';
    }
    ?>
    
</div>
</body>
</html>