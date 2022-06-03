<!DOCTYPE html>
<html>
<head>
  <meta charset = "utf-8">
  <link href="./dist/main.css" type="text/css" rel="stylesheet">
</head>
<body>
<header>
    <a class="logo" href="/"><img src="insignio weiß.png" alt="logo"></a>
    <nav>
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="table.php">Tabellen</a></li>
            <li><a href="tableRaw.php">Rohe Datenbank Tabellen</a></li>
            <li><a href="renting.php">Vermietung</a></li>
            <li><a href="csv.php">CSV</a></li>
        </ul>
    </nav>
</header>
<br>

    <?php
        error_reporting(E_ERROR | E_PARSE);
        include('dbHandler.php');
        include('functions.php');
        $con = mysqli_connect("", "root", "", 'lernfeld_8_3') or die("verbindung fehlgeschlagen");
        $table_names = get_table_names($con);
    
        echo "
            <div>
            <button onclick=\"location.href='table.php?table_name=joined_cars&sort=brand&direction=DESC'\" type='button' class='button button-2' id='buttonM'> Fahrzeuge </button>
            <button onclick=\"location.href='table.php?table_name=joined_customers&sort=title&direction=DESC'\" type='button' class='button button-2' id='buttonM'> Kunden </button>
            <button onclick=\"location.href='table.php?table_name=joined_rental&sort=status&direction=DESC'\" type='button' class='button button-2' id='buttonM'> Verleih </button>";

        if(isset($_GET['table_name'])){
            if($_GET['table_name'] == "joined_cars" || $_GET['table_name'] == 'only_available' || $_GET['table_name'] == 'only_rented') {
                echo "
                <button onclick=\"location.href='table.php?table_name=only_available&sort=brand&direction=DESC'\" type='button' class='button button-2' id='buttonM'> Nur Verfügbare Fahrzeuge </button>
                <button onclick=\"location.href='table.php?table_name=only_rented&sort=brand&direction=DESC'\" type='button' class='button button-2' id='buttonM'> Nur Ausgeliehene Fahrzeuge </button>
                </div>";
            }
        }    
    
    ?>

<div>
    <?php
        if (isset($_GET['table_name'])) {
            $dbHandler = new DbHandler($_GET['table_name'], 'lernfeld_8_3');
            $dbHandler->showTable($_GET['table_name'],"table.php");
        }
    ?>
</div>

</body>
</html>
