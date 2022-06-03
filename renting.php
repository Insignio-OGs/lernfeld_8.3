<!DOCTYPE html>
<html>
<head>
  <meta charset = "utf-8">
  <link href="./dist/main.css" type="text/css" rel="stylesheet">
</head>
<body>
<header>
    <a class="logo" href="/"><img src="insignio_weiß.png" alt="logo"></a>
    <nav>
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="table.php">Tabellen</a></li>
            <li><a href="tableRaw.php">Rohe Datenbank Tabellen</a></li>
            <li><a href="renting.php">Vermietung</a></li>
        </ul>
    </nav>
</header>
<br>
<?php
    echo "
        <div><button onclick=\"location.href='renting.php?action=return'\" type='button' class='button button-2'> Fahrzeug Rückgabe</button>
        <button onclick=\"location.href='renting.php?action=rent'\" type='button' class='button button-2'> Fahrzeug Verleih</button></div><div>
    ";
    if(isset($_GET['return'])) {
        $con = mysqli_connect("", "root", "", 'lernfeld_8_3') or die("verbindung fehlgeschlagen");
        $sql = 'UPDATE cars SET customer_id = NULL WHERE id = ' . $_GET['return'];
        $res = mysqli_query($con, $sql);
        $sql = 'DELETE FROM rental WHERE car_id = ' . $_GET['return'];
        $res = mysqli_query($con, $sql);
        echo "<script>alert('Fahrzeug wurde zurückgegeben.');</script>";
        echo "<script>window.location.href = 'renting.php?action=return'</script>";
    }
    if(isset($_GET['action'])) {
        if($_GET['action'] == 'return') {
            $con = mysqli_connect("", "root", "", 'lernfeld_8_3') or die("verbindung fehlgeschlagen");
            $sql = 'SELECT id, first_name, last_name FROM customer';
            $res = mysqli_query($con, $sql);
            while($record = mysqli_fetch_assoc($res)){
                $sql = 'SELECT brand.brand, model.model, cars.license_plate, cars.id, rental.start_date, rental.end_date
                FROM cars
                LEFT JOIN model ON cars.model_id=model.id
                LEFT JOIN brand ON model.brand_id=brand.id
                LEFT JOIN rental ON cars.id=rental.car_id
                WHERE cars.customer_id = ' . $record['id'];
                $res2 = mysqli_query($con, $sql);
                echo '<h4>' . $record['first_name'] . ' ' . $record['last_name'] . '</h4><br>';
                echo '<table>
                    <tr><td>Marke</td><td>Modell</td><td>Kennzeichen</td><td>Start</td><td>Ende</td><td>Optionen</td></tr>';
                while($record2 = mysqli_fetch_assoc($res2)) {
                    echo '<tr><td>' . $record2['brand'] . '</td><td>' . $record2['model'] . '</td><td>' . $record2['license_plate'] . '</td><td>' . $record2['start_date'] . '</td><td>' . $record2['end_date'] . '</td><td><a href="renting.php?action=return&return=' . $record2['id'] . '">Zurückgeben</a></td></tr>';
                }
                echo '</table>';
            }
        } elseif ($_GET['action'] == 'rent') {
            echo '<form action="renting.php" method="get">';
            echo '<input type="hidden" name="action" value="rent">';
            echo '<h4>Kunden auswählen</h4>';
            echo '<select name="customer" required>';
            echo '<option disabled selected>bitte auswählen</option>';
            $con = mysqli_connect("", "root", "", 'lernfeld_8_3') or die("verbindung fehlgeschlagen");
            $sql = 'SELECT id, first_name, last_name FROM customer';
            $res = mysqli_query($con, $sql);
            while($record = mysqli_fetch_assoc($res)){
                echo '<option value="' . $record['id'] . '">' . $record['first_name'] . ' ' . $record['last_name'] . '</option>';
            }
            echo '</select><br>';
            echo '<h4>Fahrzeug auswählen</h4>';
            echo '<select name="car" required>';
            echo '<option disabled selected>bitte auswählen</option>';
            $sql = 'SELECT brand.brand, model.model, cars.license_plate, cars.id
                FROM cars
                LEFT JOIN model ON cars.model_id=model.id
                LEFT JOIN brand ON model.brand_id=brand.id
                WHERE cars.customer_id IS NULL';
                echo $sql;
            $res = mysqli_query($con, $sql);
            while($record = mysqli_fetch_assoc($res)){
                echo '<option value="' . $record['id'] . '">' . $record['brand'] . ' ' . $record['model'] . ' | ' . $record['license_plate'] . '</option>';
            }
            echo '</select><br>';
            echo '<h4>Start Datum</h4>';
            echo '<input type="date" name="start_date" required>';
            echo '<h4>End Datum</h4>';
            echo '<input type="date" name="end_date" required><br>';
            echo '<h4>Start Kilometer</h4>';
            echo '<input type="number" name="start_km" required min="0"><br>';
            echo '<h4>End Kilometer</h4>';
            echo '<input type="number" name="end_km" required min="1"><br>';
            echo '<input type="submit" value="Speichern"></form>';

            if(isset($_GET['customer']) && isset($_GET['car']) && isset($_GET['start_date']) && isset($_GET['end_date']) && isset($_GET['start_km']) && isset($_GET['end_km'])) {
                $sql = 'UPDATE cars SET customer_id = ' . $_GET['customer'] . ' WHERE id = ' . $_GET['car'];
                $res = mysqli_query($con, $sql);
                $sql = 'INSERT INTO rental (customer_id, car_id, start_mileage, end_mileage, status, start_date, end_date) VALUES (' . $_GET['customer'] . ', ' . $_GET['car'] . ', ' . $_GET['start_km'] . ', ' . $_GET['end_km'] . ', 1, "' . $_GET['start_date'] . '", "' . $_GET['end_date'] . '")';
                $res = mysqli_query($con, $sql);
                echo "<script>alert('Eintrag hinzugefügt');</script>";
            }
        }
    }
    echo '</div>';
?>
</body>
</html>
