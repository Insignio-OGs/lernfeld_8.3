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

<div style = "    margin-left: 15px; margin-top: 50px;">
    <h3>Kurze Anleitung:<br><br></h3>

    <p>Auf der Seite <b>"Tabellen"</b> findet man eine ordentliche Ansicht der Daten. <br> 
        Wenn man sich die Tabelle Fahrzeuge anschaut, bekommt man zusätzlich die Option sich nur die verliehenen Fahrzeuge und nur die verfügbaren Fahrzeuge anzeigen zu lassen.<br><br></p>

    <p>Auf der Seite <b>"Rohe Datenbank Tabellen"</b> findet man eine Ansicht jeder Tabelle der Datenbank, aber roh und ohne Verbindung bzw Funktion. 
        Hier kann man die grundlegende Datenbank Struktur nachvollziehen und die reinen Daten sehen.<br>
        Man hat hier auch die Möglichkeit Daten zu bearbeiten, löschen und hinzuzufügen.<br><br></p>

    <p>Auf der Seite <b>"Vermietung"</b> kann man Autos verleihen, und verliehene Autos zurückgeben.<br>
       Bei der Rückgabe werden alle verliehenen Fahrzeuge nach Kunde gruppiert angezeigt mit der möglichkeit auf "Zurückgeben" zu klicken, und das Fahrzeug wird wieder verfügbar und nicht mehr dem Kunden zugeordnet.<br>
       Bei dem Verleih kann man aus einem Dropdown mit allen verfügbaren Autos ein Auto wählen, und aus einem Dropdown mit allen Kunden einen Kunden wählen. Danach füllt man die anderen Daten aus und kann mit einem klick auf Verleihen das Auto an den Kunden verleihen.<br><br></p>
</div>

</body>
</html>
