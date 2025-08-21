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
    <?php load_navbar("cart");
    put_item_into_cart();
    delete_item_from_cart();
    submit_order(null);
    print_cart_items();
    ?>
</body>
</html>