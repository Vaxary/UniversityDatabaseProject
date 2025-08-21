<?php require "functions.php";
not_logged_in_redirect();
user_redirect();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pizzéria</title>
</head>
<body>
    <?php load_navbar("admin");
    print_profit_by_users();
    print_most_expensive_order_by_users();
    print_pizzas_by_state();?>
</body>
</html>