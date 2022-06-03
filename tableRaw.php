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
        include('dbHandler.php');
        include('functions.php');
        $con = mysqli_connect("", "root", "", 'lernfeld_8_3') or die("verbindung fehlgeschlagen");
        $table_names = get_table_names($con);
    
    
        echo "<div>";
        foreach($table_names as $name => $count){
            echo "
            <button onclick=\"location.href='tableRaw.php?table_name=".$name."'\" type='button' class='button button-2' id='buttonM'> $name </button>
            ";
        }
        echo "</div>";
    
    
    ?>

<div>
    <?php
        if (isset($_GET['table_name'])) {
            $dbHandler = new DbHandler($_GET['table_name'], 'lernfeld_8_3');
            $dbHandler->showTable($_GET['table_name'],"tableRaw.php");
            
        }
    ?>
</div>

</body>
</html>
