<?php
session_start();
const adminemail="admin@gmail.com";
const loginerror="Hibás email vagy jelszó";
const nameerror="Hibás név (min 3 karakter)";
const passwderror="Hibás jelszó (min 5 karakter)";
const emailerror="Helytelen formátumú email";
const existserror="Az email már foglalt";
const unexpectederror="Vártatlan hiba";

function connent_db(&$conn) {  //kapcsolodas az adatbazishoz
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "pizzéria";

    $conn = mysqli_connect($servername, $username,$password) or die("Hibás csatlakozás!"); #hibauzenet ha nem sikerult csatlakozni
    mysqli_set_charset($conn, 'utf8');
    mysqli_select_db($conn, $database);
    return $conn;
}

function disconnent_db(&$conn) { //adatbazis kapcsolat megszuntetese
    $conn=null;
}

function register_user() { #felhasznalo regisztralasa
    if (isset($_POST["register"])) {
        $name = $_POST["nev"];
        $password = $_POST["jelszo"];
        $email = $_POST["email"];
        connent_db($conn);

        $errors=[];
        $emailregex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        if (!preg_match($emailregex,$email)) {
            $errors["emailerror"] = emailerror;
        }
        $passwdregex= "/^[^\s]{5,}$/";
        if (!preg_match($passwdregex,$password)) {
            $errors["passwderror"] = passwderror;
        }
        $nameregex= "/^[^\s]{3,}$/";
        if (!preg_match($nameregex,$name)) {
            $errors["nameerror"] = nameerror;
        }
        $sql="SELECT COUNT(*) AS létezik FROM felhasználó WHERE email='$email'";
        $exists=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0]['létezik'];
        if ($exists) {
            $errors["existserror"] = existserror;
        }

        if (empty($errors)) {
            $password=password_hash($password, PASSWORD_DEFAULT);

            $sql="INSERT INTO felhasználó (név, jelszó, email, bejelentkezve) VALUES ('$name','$password','$email', 0)";
            $res=mysqli_query($conn, $sql);
            if ($res) {
                disconnent_db($conn);
                header("Location: login.php");
            } else {
                print_register(["unexpectederror"=>unexpectederror]);
            }
        } else {
            print_register($errors);
        }
        disconnent_db($conn);
    } else {
        print_register();
    }
}

function login_user() { #felhasznalo bejelentkeztetese
    if (isset($_POST["login"])) {
        connent_db($conn);
        $email=$_POST["email"];
        $password=$_POST["jelszo"];
        $sql="SELECT COUNT(*) AS létezik FROM felhasználó WHERE email='$email'";
        $exists=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0]['létezik'];
        if ($exists) {
            $sql="SELECT jelszó FROM felhasználó WHERE email='$email'";
            $storedpassword=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0]['jelszó'];
            if (password_verify($password, $storedpassword)) {
                if ($email == adminemail) {
                    $_SESSION["admin"]=true;
                } else {
                    $_SESSION["admin"]=false;
                }
                $_SESSION["email"]=$email;

                $date = date("Y-m-d");
                $sql="UPDATE felhasználó SET `bejelentkezve`=1,`utolsó belépés`='$date' WHERE `email`='$email'";
                mysqli_query($conn, $sql);
                
                if ($_SESSION["admin"]) {
                    disconnent_db($conn);
                    header("Location: orderlist.php");
                } else {
                    disconnent_db($conn);
                    header("Location: order.php");
                }
            } else {
                print_login(["login"=>loginerror]);
            }
        } else {
            print_login(["login"=>loginerror]);
        }
        disconnent_db($conn);
    } else {
        print_login();
    }
}

function print_login($error=[]) { #bejelentkezes oldal
    echo "<form id='loginform' method='post' action='login.php'>
        <h2>Bejelentkezés</h2>
        <input type='email' name='email' id='email' placeholder='emailcím' required>
        <input type='password' name='jelszo' id='jelszo' placeholder='jelszó' required>";
    echo "<input type='submit' name='login' value='Bejelentkezés'>
    </form>";
    if (!empty($error)) {
        echo "<p class=errormsg>".$error['login']."</p>";
    }
}

function print_register($error=[]) { #regisztracio oldal
    echo "<form id='registerform' method='post' action='register.php'>
        <h2>Regisztráció</h2>
        <input type='email' name='email' id='email' placeholder='emailcím' required>";
    if (isset($error["emailerror"])) {
        echo "<p class=errormsg>".$error['emailerror']."</p>";
    }
    echo "<input type='text' name='nev' id='nev' placeholder='név' required>";
    if (isset($error["nameerror"])) {
        echo "<p class=errormsg>".$error['nameerror']."</p>";
    }
    echo "<input type='password' name='jelszo' id='jelszo' placeholder='jelszó' required>";
    if (isset($error["passwderror"])) {
        echo "<p class=errormsg>".$error['passwderror']."</p>";
    }
    echo "<input type='submit' name='register' value='Regisztráció'>
    </form>";
    if (isset($error["existserror"])) {
        echo "<p class=errormsg>".$error['existserror']."</p>";
    }
    if (isset($error["unexpectederror"])) {
        echo "<p class=errormsg>".$error['unexpectederror']."</p>";
    }
}

function logout() { #kijelentkezes
    connent_db($conn);
    $email = $_SESSION["email"];
    $sql = "UPDATE felhasználó SET bejelentkezve = 0 WHERE email='$email'";
    (mysqli_query($conn, $sql));

    session_unset();
    session_destroy();
    disconnent_db($conn);
    header("Location: login.php");
}

function load_navbar($active) { #navigacios menu
    $pages = ["admin"=>false, "order"=>false, "register"=>false, "login"=>false, "orderlist"=>false, "logout"=>false, "cart"=>false, "myorders"=>false, "userlist"=>false];
    $pages[$active]=true;
    echo "<nav id=navbar>"; 
    if (isset($_SESSION["email"])) {
        if ($_SESSION["admin"]) {
            echo "<div><a href='admin.php'";if ($pages["admin"]) {echo " class='active'";} echo ">Admin</a></div>";
            echo "<div><a href='userlist.php'";if ($pages["userlist"]) {echo " class='active'";} echo ">Felhasználók</a></div>";
            echo "<div><a href='orderlist.php'";if ($pages["orderlist"]) {echo " class='active'";} echo ">Rendelések</a></div>";
        }
        echo "<div><a href='order.php'";if ($pages["order"]) {echo " class='active'";} echo">Rendelés</a></div>";
        echo "<div><a href='myorders.php'";if ($pages["myorders"]) {echo " class='active'";} echo">Rendeléseim</a></div>";
        echo "<div><a href='cart.php'";if ($pages["cart"]) {echo " class='active'";} echo">Kosár</a></div>";
        echo "<div><a href='logout.php'";if ($pages["logout"]) {echo " class='active'";} echo">Kijelentkezés</a></div>";
    } else {
        echo "<div><a href='login.php'";if ($pages["login"]) {echo " class='active'";} echo">Bejelentkezés</a></div>";
        echo "<div><a href='register.php'";if ($pages["register"]) {echo " class='active'";} echo">Regisztráció</a></div>";
    }
    echo "</nav>";  
}

function print_orders() { #megrendelesek kiirasa
    connent_db($conn);
    $sql="SELECT * FROM rendelés ORDER BY rendelés.időpont DESC";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
    if (empty($res)) {
        echo "<h2>Még nem rendeltek!</h2>";
    } else {
        echo "<table id=orders>
        <tr><th>Időpont</th><th>Asztalszám</th><th>Rendelő emailcíme</th><th>Végösszeg</th><th>Állapot</th><th>Megtekintés</th><th>Állapotváltoztatás</th><th>Törlés</th></tr>";
        foreach ($res as $row) {
            $state =$row['állapot'];
            $orderid=$row['rendelés azonosító'];
            echo "<tr><td>".$row['időpont']."</td><td>".$row['asztalszám']."</td><td>".$row['email']."</td><td>".$row['végösszeg']." Ft</td><td>".$state."</td>";
            echo "<td><form method='post' action='orderlistitem.php'><button type='submit' value='$orderid' name='orderid'>Megtekintés</button></form></td>";
            if ($state == "asztalnál" || $state=="készül"){
                echo "<td><form method='post' action='orderlist.php'><input type='text' value='$orderid' style='display:none' name='orderid'><button type='submit' value=";
                if ($state == "asztalnál") {echo "'fizetve' name='changestate'>Fizetve";}
                else {echo "'asztalnál' name='changestate'>Asztalnál";}
                echo "</button></form></td>";
                if ($state == "készül"){
                    echo "<td><form method='post' action='orderlist.php'><button type='submit' value='".$orderid."' name='deleteorder'>";
                    echo "Törlés</button></form></td>";
                } else {
                    echo "<td></td>";
                }
            } else {
                echo "<td></td><td></td>";
            }
            echo "</tr>";
        }
    echo "</table>";
    }

    
    disconnent_db($conn);
}

function print_orders_by_user() { #megrendelesek kiirasa adott felhasznalonak
    connent_db($conn);
    $sql="SELECT * FROM rendelés WHERE email='".$_SESSION['email']."' ORDER BY rendelés.időpont DESC";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
    if (!empty($res)) {
        echo "<table id=orders>
        <tr><th>Időpont</th><th>Asztalszám</th><th>Rendelő emailcíme</th><th>Végösszeg</th><th>Állapot</th><th>Megtekintés</th><th>Törlés</th></tr>";
        foreach ($res as $row) {
            $state=$row['állapot'];
            $orderid=$row['rendelés azonosító'];
            echo "<tr><td>".$row['időpont']."</td><td>".$row['asztalszám']."</td><td>".$row['email']."</td><td>".$row['végösszeg']." Ft</td><td>".$state."</td>";
            echo "<td><form method='post' action='myorderitem.php'><button type='submit' value='$orderid' name='orderid'>Megtekintés</button></form></td>";
            if ($state == "készül"){
                echo "<td><form method='post' action='myorders.php'><button type='submit' value='".$orderid."' name='deleteorder'>";
                echo "Törlés</button></form></td>";
            } else {
                echo "<td></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<h2>Még nem rendelt!</h2>";
    }
    disconnent_db($conn);
}

function delete_order_by_id($location) { #megrendeles torlese azonosito alapjan
    if (isset($_POST['deleteorder'])) {
        connent_db($conn);
        $sql="DELETE FROM rendelés WHERE `rendelés azonosító`='".$_POST['deleteorder']."'";
        mysqli_query($conn, $sql);
        disconnent_db($conn);
        header("Location: ".$location);
    }
}

function change_orederstate() { #megrendeles elorebb vitele
    if (isset($_POST["changestate"])) {
        connent_db($conn);
        $newstate =$_POST['changestate'];
        $orderid = $_POST['orderid'];
        $sql="UPDATE rendelés SET `állapot`='$newstate' WHERE `rendelés azonosító`='$orderid'";
        mysqli_query($conn, $sql); 
        disconnent_db($conn);
        header("Location: orderlist.php");
    }
}

function print_order_by_id($orderid) { #megrendeles listazasa azonosíto alapjan
    connent_db($conn);
    $sql="SELECT * FROM rendelés WHERE `rendelés azonosító`='$orderid'";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0];

    echo "<table id=orderinfo>
        <tr><th>Időpont</th><th>Asztalszám</th><th>Rendelő emailcíme</th><th>Végösszeg</th><th>Állapot</th></tr>";
        echo "<tr><td>".$res['időpont']."</td><td>".$res['asztalszám']."</td><td>".$res['email']."</td><td>".$res['végösszeg']." Ft</td><td>".$res['állapot']."</td></tr>";
    echo "</table>";
    disconnent_db($conn);
}

function print_profit_by_users() { #elso osszetett lekerdezes, osszes fizetett rendeles osszkoltsege felhasznalonkent
    connent_db($conn);
    $sql="SELECT felhasználó.email,felhasználó.név,SUM(rendelés.végösszeg) AS 'összköltés'
    FROM felhasználó INNER JOIN rendelés ON felhasználó.email = rendelés.email
    WHERE rendelés.állapot = 'fizetve'
    GROUP BY felhasználó.email,felhasználó.név
    ORDER BY SUM(rendelés.végösszeg) DESC;";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
    echo "<h2>Eddigi fizetett rendelések profitja tényleges vásárlónként</h2>";
    if (empty($res)) {
        echo "<h3>Még nem fizettek ki rendelést!</h3>";
    } else {
        echo "<table id=profitbyperson>
            <tr><th>Rendelő neve</th><th>Rendelő emailcíme</th><th>Profit</th></tr>";
            foreach ($res as $row) {
                echo "<tr><td>".$row['név']."</td><td>".$row['email']."</td><td>".$row['összköltés']." Ft</td></tr>";
            }
        echo "</table>";
    }
    disconnent_db($conn);
}

function print_most_expensive_order_by_users() { #harmadik osszetett lekerdezes, legdragabb fizetett rendeles felhasznalonkent
    connent_db($conn);
    $sql="SELECT felhasználó.név, felhasználó.email, rendelés.végösszeg, rendelés.`rendelés azonosító`
    FROM felhasználó INNER JOIN rendelés ON rendelés.email = felhasználó.email 
    WHERE rendelés.végösszeg IN (SELECT MAX(végösszeg) FROM rendelés GROUP BY rendelés.email) AND rendelés.állapot = 'fizetve'
    ORDER BY rendelés.végösszeg DESC;";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
    echo "<h2>Eddigi legdrágább fizetett rendelés tényleges vásárlónként</h2>";
    if (empty($res)) {
        echo "<h3>Még nem fizettek ki rendelést!</h3>";
    } else {
        echo "<table id=mostexpensiveorderbyperson>
        <tr><th>Rendelő neve</th><th>Rendelő emailcíme</th><th>Legdrágább rendelés</th><th>Megtekintés</th></tr>";
        foreach ($res as $row) {
            echo "<tr><td>".$row['név']."</td><td>".$row['email']."</td><td>".$row['végösszeg']." Ft</td>";
            echo "<td><form method='post' action='maxorder.php'><button type='submit' value='".$row['rendelés azonosító']."' name='orderid'>Megtekintés</button></form></td></tr>";
        }
        echo "</table>";
    }
    disconnent_db($conn);
}

function print_pizzas_by_state() { #masodik osszetett lekerdezes, rendeles allapotonkent pizzak szama
    connent_db($conn);
    $sql="SELECT rendelés.állapot, SUM(rendeléstétel.`darabszám`) AS `pizzák száma`
    FROM `rendelés` INNER JOIN rendeléstétel ON rendelés.`rendelés azonosító` = rendeléstétel.`rendelés azonosító`
    WHERE rendeléstétel.`pizza név` IS NOT NULL
    GROUP BY rendelés.állapot;";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
    echo "<h2>Eddigi rendelt pizzák száma állapotonként</h2>";
    $seged = ['készül'=>0,'asztalnál'=>0,'fizetve'=>0];
    echo "<table id=pizzasbystate>
        <tr><th>Rendelés állapota</th><th>Pizzák száma</th></tr>";
        foreach ($res as $row) {
            $seged[$row['állapot']]=$row['pizzák száma'];
        }
        foreach ($seged as $key => $value) {
            echo "<tr><td>".$key."</td><td>".$seged[$key]."</td></tr>";
        }
    echo "</table>";
    disconnent_db($conn);
}

function print_back_button($page) { #vissza gomb kiiratasa
    echo "<div id='backbox'><a href='".$page."' id='back'>Vissza</a></div>";
}

function print_order_details($orderid) { #megrendeles reszleteinek listazasa azonosito alapjan
    connent_db($conn);
    $sql="SELECT * FROM rendeléstétel WHERE `rendelés azonosító`='$orderid'";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
    echo "<table id=singleorder>
        <tr><th>Pizza/Feltét</th><th>Méret</th><th>Sorszám</th><th>Darabszám</th><th>Ár/db</th><th>Összár</th></tr>";
        foreach ($res as $row) {
            if ($row["pizza név"] != null) {
                $sql="SELECT méret FROM pizza WHERE `pizza név`='".$row['pizza név']."'";
                $pizzasize=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0]['méret'];
                echo "<tr class=pizzarow><div ><td>".$row['pizza név']."</td><td>$pizzasize cm</td><td>".$row['sorszám']."</td><td>".$row['darabszám']."</td><td>".$row['összérték']/$row['darabszám']." Ft</td><td>".$row['összérték']." Ft</td></div></tr>";
            } else {
                echo "<tr class=toppingrow><td>".$row['feltét név']."</td><td></td><td>".$row['sorszám']."</td><td>".$row['darabszám']."</td><td>".$row['összérték']/$row['darabszám']." Ft</td><td>".$row['összérték']." Ft</td></tr>";
            }
        }
    echo "</table>";
    disconnent_db($conn);
}

function print_pizzas() { #rendelheto pizzak listazasa
    connent_db($conn);
    $sql="SELECT * FROM pizza";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);

    echo "<table id=singleorder>
        <tr><th>Pizza</th><th>Méret</th><th>Ár</th><th></th></tr>";
        foreach ($res as $row) {
            echo "<td>".$row['pizza név']."</td><td>".$row['méret']." cm</td><td>".$row['pizza ár']." Ft</td>";
            echo "<td><form method='get' action='item.php'><button type='submit' value=".remove_spaces($row['pizza név'])." name='itemname'>Megtekintés</button></form></td></tr>";
        }
    echo "</table>";
    disconnent_db($conn);
}

function remove_spaces($string, $decode=false) { #Posthoz szukseges space atalakitas _-ra
    if ($decode) {
        return str_replace("_"," ",$string);
    }
    return str_replace(" ","_",$string);
}

function print_toppings_by_pizza($pizzaname) { #adott pizzahoz valaszthato feltetek listazasa
    connent_db($conn);
    $sql="SELECT * FROM pizza WHERE `pizza név` = '".remove_spaces($pizzaname,true)."'";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0];

    echo "<form method='post' action='cart.php'><table id=singlepizza>
    <tr><th>Pizza</th><th>Méret</th><th>Ár</th><th>Darabszám</th></tr>";
    echo "<tr><td>".$res['pizza név']."</td><td>".$res['méret']." cm</td><td>".$res['pizza ár']." Ft</td><td><input type='number' name='number' placeholder='darabszám' min='1' step=1 value=1 required><td></tr></table>";
    $sql="SELECT feltét.`feltét név`, feltét.`feltét ár` FROM `feltét` INNER JOIN feltétopciója ON feltétopciója.`feltét név` = feltét.`feltét név` WHERE feltétopciója.`pizza név` = '".remove_spaces($pizzaname,true)."'";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
    echo "<table id=toppings><tr><th>Feltét</th><th>Ár</th></tr>";
        foreach ($res as $row) {
            echo "<tr><td>".$row['feltét név']."</td><td>".$row['feltét ár']." Ft</td><td><input value=".remove_spaces($row['feltét név'])." type=checkbox name=".remove_spaces($row['feltét név'])."></td></tr>";
        }
    echo "</table><button type=submit value=".$pizzaname." name='intocart'>Kosárba</button></form>";
    disconnent_db($conn);
}

function put_item_into_cart() { #termek kosarba helyezese
    
    if (isset($_POST['intocart'])) {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart']['items'])) {
        $_SESSION['cart']=[
            'lastcartid'=>0,
            'items'=>[]
        ];
        } else {
            $_SESSION['cart']['lastcartid']+=1;
        }

        $_SESSION['cart']['items'][$_SESSION['cart']['lastcartid']]=
        [
            'név'=>remove_spaces($_POST['intocart'],true),
            'darabszám'=>$_POST['number'],
            'feltétek'=>[]
        ];
        foreach ($_POST as $key => $value) { 
            if ($key != 'intocart' && $key !='number') {
                array_push($_SESSION['cart']['items'][$_SESSION['cart']['lastcartid']]['feltétek'],remove_spaces($key,true));
            }
        }
        header("Location: order.php");
    }
    
}

function print_cart_items() { #kosar termekek kiiratasa
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart']['items'])) {
        connent_db($conn);
        $numberinline=0;
        $total = 0;
        echo "<table id=cart>
        <tr><th>Pizza/Feltét</th><th>Sorszám</th><th>Darabszám</th><th>Ár/db</th><th>Összár</th></tr>";
        foreach ($_SESSION['cart']['items'] as $itemindex => $itemcontent) {
            $numberinline++;
            $sql="SELECT * FROM pizza WHERE `pizza név` = '".remove_spaces($itemcontent['név'],true)."'";
            $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0];
            echo "<tr class=pizzarow><td>".$itemcontent['név']." - Méret: ".$res['méret']." cm</td><td>".$numberinline."</td><td>".$itemcontent['darabszám']."</td><td>".$res['pizza ár']." Ft</td><td>".$res['pizza ár']*$itemcontent['darabszám']." Ft</td>
            <td><form method='post' action='cart.php'><button name='deleteitemfromcart' value='$itemindex' type=submit>Törlés</button><form></td></tr>";
            $total+=$res['pizza ár']*$itemcontent['darabszám'];
            foreach ($itemcontent['feltétek'] as $toppingindex => $toppingconent) {
                $numberinline++;
                $sql="SELECT * FROM feltét WHERE `feltét név` = '".remove_spaces($toppingconent,true)."'";
                $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0];
                echo "<tr class=toppingrow><td>".$toppingconent."</td><td>".$numberinline."</td><td>".$itemcontent['darabszám']."</td><td>".$res['feltét ár']." Ft</td><td>".$res['feltét ár']*$itemcontent['darabszám']." Ft</td></tr>";
                $total+=$res['feltét ár']*$itemcontent['darabszám'];
            }
        }
        echo "</table>";
        echo "<p>Összár: ".$total." Ft</p>";
            
        echo "<form action='cart.php' method='post'>";
        
        $freetables=[];
        for ($i=1; $i <= 10; $i++) { 
            $sql="SELECT asztalszám FROM `rendelés` WHERE (állapot='készül' OR állapot='asztalnál') AND asztalszám='$i';";
            $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
            if (empty($res)) {
                array_push($freetables,$i);
            }
        }
        if (!empty($freetables)) {
            echo "<select name='table' id='table'>";
            foreach ($freetables as $table) {
                echo "<option value='$table'>$table.asztal</option>";
            }
            echo "</select>";
        } else {
            echo "<p>Nincs szabad asztal!</p>";
        }
        if ($_SESSION['admin']) {
            $sql="SELECT email, név FROM `felhasználó`;";
            $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
            echo "<select name='user' id='user'>";
            foreach ($res as $user) {
                echo "<option value='".$user['email']."'>".$user['név']." - ".$user['email']."</option>";
            }
            echo "</select>";
        }

        
        
        echo "<button type=submit name='submitorder' value='submitorder'";if(empty($freetables)){echo "disabled";} echo ">Rendelés Leadása</button></form>";
        disconnent_db($conn);
    } else {
        echo "<h2>A kosár üres</h2>";
    }
    
}

function delete_item_from_cart() { #kosar termek kitorlese
    if (isset($_POST['deleteitemfromcart'])) {
        unset($_SESSION['cart']['items'][$_POST['deleteitemfromcart']]);
        header("Location: cart.php");
    }
}

function submit_order() { #rendeles leadasa
    $user = $_SESSION['email'];
    if (isset($_POST['user'])) {
        $user = $_POST['user'];
    }
    if (isset($_POST['submitorder'])) {
        connent_db($conn);
        $freetables=[];
        for ($i=0; $i < 10; $i++) { 
            $sql="SELECT asztalszám FROM `rendelés` WHERE (állapot='készül' OR állapot='asztalnál') AND asztalszám='$i';";
            $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
            if (empty($res)) {
                array_push($freetables,$i);
            }
        }
        $total=0;
        foreach ($_SESSION['cart']['items'] as $itemcontent) {
            $sql="SELECT * FROM pizza WHERE `pizza név` = '".remove_spaces($itemcontent['név'],true)."'";
            $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0];
            $total+=$res['pizza ár']*$itemcontent['darabszám'];
            foreach ($itemcontent['feltétek'] as $toppingconent) {
                $sql="SELECT * FROM feltét WHERE `feltét név` = '".remove_spaces($toppingconent,true)."'";
                $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0];
                $total+=$res['feltét ár']*$itemcontent['darabszám'];
            }
        }

        $sql="INSERT INTO rendelés (időpont, állapot, végösszeg, asztalszám, email) VALUES ('".date("Y-m-d H:i:s")."', 'készül', '$total','".$_POST['table']."','".$user."')";
        mysqli_query($conn, $sql);

        $sql="SELECT MAX(`rendelés azonosító`) AS orderid FROM rendelés";
        $orderid=(int)mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0]['orderid'];

        $numberinline=0;
        foreach ($_SESSION['cart']['items'] as $itemcontent) {
            $numberinline++;
            $sql="SELECT `pizza ár` FROM pizza WHERE `pizza név` = '".remove_spaces($itemcontent['név'],true)."'";
            $price=(int)(mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0]['pizza ár']);

            $sql="SELECT MAX(`tétel azonosító`) AS orderpieceid FROM rendeléstétel";
            print_r(mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC));
            $orderpieceid=(int)(mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0]['orderpieceid'])+1;
            if ($orderpieceid == null) {
                $orderpieceid=1;
            }
            
            $sql="INSERT INTO rendeléstétel (`tétel azonosító`,darabszám, sorszám, összérték, `rendelés azonosító`, `pizza név`, `melyikhez tétel azonosító`) VALUES ('$orderpieceid','".$itemcontent['darabszám']."','$numberinline','".$price*$itemcontent['darabszám']."','$orderid','".remove_spaces($itemcontent['név'],true)."','$orderpieceid')";
            mysqli_query($conn, $sql);
            $currentid=$orderpieceid;
            foreach ($itemcontent['feltétek'] as $toppingconent) {
                $numberinline++;
                $currentid++;
                $sql="SELECT `feltét ár` FROM feltét WHERE `feltét név` = '".remove_spaces($toppingconent,true)."'";
                $price=(int)mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC)[0]['feltét ár'];
                
                $sql="INSERT INTO rendeléstétel (`tétel azonosító`,darabszám, sorszám, összérték, `rendelés azonosító`, `feltét név`, `melyikhez tétel azonosító`) VALUES ('$currentid','".$itemcontent['darabszám']."','$numberinline','".$price*$itemcontent['darabszám']."','$orderid','".remove_spaces($toppingconent,true)."','$orderpieceid')";
                mysqli_query($conn, $sql);
            }
        }
        unset($_SESSION['cart']);
        disconnent_db($conn);
        header("Location: order.php");
        
        
    }
}

function print_userlist() { #felhasznalok kilistazasa
    connent_db($conn);
    $sql="SELECT * FROM felhasználó";
    $res=mysqli_fetch_all(mysqli_query($conn, $sql),MYSQLI_ASSOC);
    echo "<table id=userlist>
    <tr><th>Név</th><th>Email</th><th>Bejelentkezve</th><th>Utolsó bejelentkezés</th><th>Törlés</th></tr>";
    foreach ($res as $user) {
        $loggedin = "Nem";
        if ($user['bejelentkezve']) {
            $loggedin="Igen";
        }
        echo "<tr><td>".$user['név']."</td><td>".$user['email']."</td><td>".$loggedin."</td>";
        echo"<td>";
        if ($user['utolsó belépés'] == null) {
            echo "Soha";
        } else {
            echo $user['utolsó belépés'];
        }
        echo"</td>";
        if ($user['email']!=adminemail) {
            echo "<td><form action='userlist.php' method='post'><button type=submit value='".$user['email']."' name='deleteuser'>Törlés</button></form></td>";
        } else {
            echo "<td></td>";
        }
        echo "</tr>";
        
    }
    echo "</table>";

    disconnent_db($conn);
}



function delete_user() { #felhasznalo torlese
    if (isset($_POST['deleteuser'])) {
        connent_db($conn);
        $sql="DELETE FROM felhasználó WHERE email='".$_POST['deleteuser']."'";
        mysqli_query($conn, $sql);
        disconnent_db($conn);
        header("Location: userlist.php");
    }
    
}

function admin_redirect() { #admin atiranyitasa az oldalrol
    if (isset($_SESSION['email'])) {
        if ($_SESSION['admin']) {
            header("Location: orderlist.php");
        }
    }
}

function not_logged_in_redirect() { #vendeg atiranyitasa az oldalrol
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
    }
}

function user_redirect() {
    if (isset($_SESSION['email'])) { #felhasznalo atiranyitasa az oldalrol
        if (!$_SESSION['admin']) {
            header("Location: myorders.php");
        }
    } 
}
?>