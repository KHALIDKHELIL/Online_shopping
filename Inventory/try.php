<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Calculation</title>
    <script>
        function calculateTotal() {
            // Get the unit price and quantity values
            let unitPrice = document.getElementById('unitPrice').value;
            let quantity = document.getElementById('quantity').value;

            // Calculate total price
            let totalPrice = unitPrice * quantity;

            // Set the total price input value
            document.getElementById('totalPrice').value = totalPrice.toFixed(2);
        }
    </script>
</head>
<body>
    <h2>Price Calculation Form</h2>
    <form action="purchase.php" method="post">
        <label for="unitPrice">Unit Price:</label>
        <input type="number" id="unitPrice" name="unitPrice" step="0.01" onchange="calculateTotal()" required>
        <br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" onchange="calculateTotal()" required>
        <br><br>

        <label for="totalPrice">Total Price:</label>
        <input type="text" id="totalPrice" name="totalPrice" readonly>
        <br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
