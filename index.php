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

include('functions.php');

echo "<div><h2> ".get_time_str()." </h2></div>";

?>

<div>
    <h5>Kurze Anleitung:</h5>
    <p>Auf der Seite "Tabellen" findet man eine ordentliche Ansicht der Daten, und hat die Möglichkeit Daten zu bearbeiten, löschen und hinzuzufügen.</p>
    <p>Auf der Seite "Rohe Datenbank Tabellen" findet man eine Ansicht jeder Tabelle der Datenbank, aber roh und ohne Verbindung bzw Funktion</p>
    <p>Auf der Seite "Vermietung" kann man Autos verleihen, und verliehene Autos zurückgeben</p>
</div>

</body>
</html>
