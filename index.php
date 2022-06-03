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
            <li><a href="csv.php">CSV</a></li>
        </ul>
    </nav>
</header>
<br>

<?php

include('functions.php');

echo "<h2> ".get_time_str()." </h2>";

?>
#sachen verlinken und begrüßung

</body>
</html>
