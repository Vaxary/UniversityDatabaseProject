<?php require "functions.php";
not_logged_in_redirect();?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pizzéria</title>
</head>
<body>
    <?php load_navbar("myorders");
    delete_order_by_id('myorders.php');
    print_orders_by_user();
    ?>
</body>
</html>