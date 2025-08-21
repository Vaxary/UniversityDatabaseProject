<?php require "functions.php";
not_logged_in_redirect();
user_redirect();
if (!isset($_POST["orderid"])) {
    header("Location: orderlist.php");
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pizzéria</title>
</head>
<body>
    <?php
    print_back_button("orderlist.php");
    print_order_by_id($_POST["orderid"]); 
    print_order_details($_POST["orderid"]);?>
</body>
</html>