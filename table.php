<!DOCTYPE html>
<html>
<head>
  <meta charset = "utf-8">
</head>
<body>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="table.php">Tabellen</a></li>
            <li><a href="tableRaw.php">Rohe Datenbank Tabellen</a></li>
            <li><a href="renting.php">Vermietung</a></li>
            <li><a href="csv.php">CSV</a></li>
        </ul>
    </nav>

<div>
    <h1>TABLE</h1>
</div>

    <?php
        include('dbHandler.php');
        include('functions.php');
        $con = mysqli_connect("", "root", "", 'lernfeld_8_3') or die("verbindung fehlgeschlagen");
        $table_names = get_table_names($con);
    
        echo "
            <div>
            <button onclick=\"location.href='table.php?table_name=joined_cars'\" type='button' > Fahrzeuge </button>
            <button onclick=\"location.href='table.php?table_name=joined_customers'\" type='button' > Kunden </button>
            <button onclick=\"location.href='table.php?table_name=joined_rental'\" type='button' > Verleih </button>
            </div>";

        if(isset($_GET['table_name'])){
            if($_GET['table_name'] == "joined_cars" || $_GET['table_name'] == 'only_available' || $_GET['table_name'] == 'only_rented') {
                echo 
                "<div>
                <button onclick=\"location.href='table.php?table_name=only_available'\" type='button' > Nur Verfügbare Fahrzeuge </button>
                <button onclick=\"location.href='table.php?table_name=only_rented'\" type='button' > Nur Ausgeliehene Fahrzeuge </button>
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
