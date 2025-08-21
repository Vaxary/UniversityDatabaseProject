<?php require "functions.php";
user_redirect();
admin_redirect();?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pizzéria</title>
</head>
<body>
    <?php 
    load_navbar("login");
    login_user();
    ?>
</body>
</html>