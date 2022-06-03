<!-- PHP Functionen -->
<?php
    /**
     * Gibt anhand der Uhrzeit die passende Begrüßung zurück
     * @return string
     */ 

    function get_time_str():string {
        $time = intval(substr(date("H:i:s", time()),0,2));
        if($time<12){
            $time_str = "Guten Morgen ";
        }
        elseif($time>=12 && $time<17){
            $time_str = "Guten Tag ";
        }
        else{
            $time_str = "Guten Abend ";
        }
        return $time_str;
    }

    /**
     * Tauscht ASC/DESC
     * @return string
     */ 
    function get_sort() {
        if(isset($_GET["direction"])){
            if($_GET["direction"]  == "ASC"){
                $direction = "DESC";
            }
            else{
                $direction = "ASC ";
            }
        }
        else{
            $direction = "ASC";
        }
        return $direction;
    }

    /**
    * Gibt alle Tabellen Namen und die anz der Datensätze in einer Tabelle zurück
    * Parameter:
    *  - $con = DB Verbindung
    *  - $file = Pfad zur Seite auf die verwiesen wird wenn eine Tabelle ausgewählt wird
    * @param mixed $con
    * @param string $file
    */
    function get_tables($con, $file) {
        $res = mysqli_query($con, "SHOW TABLES");
        echo "<style>
                    table, tr, td {
                        border: 1px solid black;
                    }
                </style>";
        while($cRow = mysqli_fetch_array($res)){
            $tab_name = $cRow[0];
            $record_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM " . $cRow[0]));
            if($tab_name != "user") {
                echo "<p>
                <table class='content-table' style='cursor: pointer;'>
                    <tr>
                        <td>
                            Tabelle <b>" . $tab_name . "</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Anzahl Datensätze: <b>" . $record_count . "</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <form method='GET' action='" . $file . "'>
                                <input type='submit' value='Tabelle anzeigen'>
                                <input type='hidden' value='" . $tab_name . "' name='tab_name'>
                                <input type='hidden' value='" . $record_count . "' name='record_count'>
                            </form>
                    </tr>
                </table>
                </p>";
            }
        }
    }

    /**
    * Gibt alle Tabellen Namen in einem Array zurück
    * Parameter:
    *  - $con = DB Verbindung
    * @param mixed $con
    * @param string $file
    * @return array
    */
    function get_table_names($con) {
        $res = mysqli_query($con, "SHOW TABLES");
        $table_names = array();
        while($cRow = mysqli_fetch_array($res)){
            $tab_name = $cRow[0];
            $record_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM " . $cRow[0]));
            if($tab_name != "user") {
               $table_names[$tab_name] = $record_count;
            }
        }
        return $table_names;
    }

    /**
    * Gibt alle Namen der Spalten in einer DB-Tabelle in einem Array zurück
    * Parameter:
    *  - $tab_name = Tabellen Name
    *  - $con      = DB Verbindung
    * @param string $tab_name
    * @param mixed $con
    * @return array 
    */
    function get_column_name($tab_name, $con) {
        $res = mysqli_query($con, "SHOW COLUMNS FROM " . $tab_name);
        while($cRow = mysqli_fetch_assoc($res)){
            $column_name[] = $cRow["Field"];
        }
        return $column_name;
    }
    /**
    * Gibt den Namen des Primarykeys als string aus
    * Parameter:
    *  - $con      = DB Verbindung
    *  - $tab_name = Tabellen Name
    * @param mixed $con 
    * @param string $tab_name
    * @return string
    */
    function get_primarykey($con, $tab_name) {
        $res = mysqli_query($con, "SHOW INDEX FROM " . $tab_name . " WHERE Key_name = 'PRIMARY'");
        $cRow = mysqli_fetch_assoc($res);
        $column_name = $cRow["Column_name"];
        return $column_name;
    }

    /**
    * Gibt den input Typ entsprechend dem MySql Datentyp zurück
    * Parameter:
    *  - $con          = DB Verbindung
    *  - $tab_name     = Tabellen Name
    *  - $column_name  = Spalten Name
    * @param mixed $con 
    * @param string $tab_name
    * @param string $column_name
    * @return string
    */
    function get_inputtype($con, $tab_name, $column_name) {
        $res = mysqli_query($con, "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $tab_name . "' AND COLUMN_NAME = '" . $column_name . "'");
        $cRow = mysqli_fetch_assoc($res);
        $type = $cRow["DATA_TYPE"];
        if($type == "varchar" || $type == "text" || $type == "timestamp" || $type == "char" || $type == "binarys"){
            $type = "text";
        }elseif($type == "int" || $type == "double" || $type == "tinyint" || $type == "smallint" || $type == "mediumint" || $type == "integer" || $type == "bigint" || $type == "float" || $type == "real" || $type == "decimal" || $type == "numeric" || $type == "year"){
            $type = "number";
        }elseif($type == "date"){
            $type = "date";
        }elseif($type == "datetime"){
            $type = "datetime-local";
        }elseif($type == "time"){
            $type = "time";
        }else{
            $type = "text";
        }
        return $type;
    }

    /**
     * Ließt die angegebene CSV Datei ein und gibt sie in einer Tabelle aus
     * @param string $csv_dir
     * @param string $file
     */
    function csv_show($csv_dir, $file) {
                $file = fopen($csv_dir . $file, "r");
                echo "<br>";
                echo "<style>
                        table, tr, td {
                            border: 1px solid black;
                        }
                    </style>";
                echo "<table>";
                while (($csv_array = fgetcsv($file, 1000, ';')) !== FALSE ) {
                    echo "<tr>";
                    foreach ($csv_array as $index) {
                        echo "<td>" . $index . "</td>";
                    }
                    echo "<tr>";
                }
                echo "</table>";
                fclose($file);
    }
    

    /**
     * Dateigröße in Bytes wird übergeben und sie gibt dann die passende endung zurück (MB, KB...)
     * @param int $size
     * @return string
     */
    function get_size($size) {
        if($size < 10){
            return "<b>" . ($size / 1000) . "</b> KB";
        }
        elseif($size < 100000){
            return "<b>" . $size . " Bytes</b>";
        }
        else{
            return "<b>" . ($size / 1000000) . " MB</b>";
        }
    }
?>
