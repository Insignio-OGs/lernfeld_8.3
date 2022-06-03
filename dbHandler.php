<?php

    class DbHandler {
        private $csv_location = "../csv";

        /**
         *  Konstruktor der Klasse Table
         * @param string $tablename
         * @param string $dbName
         */
        function __construct($tablename, $dbName) {
            $this->con = mysqli_connect("", "root", "", $dbName);
            $this->tablename = $tablename;
        }


        /**
         * Liefert die Spaltennamen in einem Array zurück
         * @return array 
         */
        function get_column_names() {
            $res = mysqli_query($this->con, "SHOW COLUMNS FROM " . $this->tablename);
            while($cRow = mysqli_fetch_assoc($res)){
                $column_name[] = $cRow["Field"];
            }
            return $column_name;
        }

        /**
         * Setzt den wert in der Angegeben Tabelle an der angegebenen Stelle
         * @param mixed $id
         * @param mixed $value
         * @param string $column_name
         * @param string $primary_key
         * 
         * @return bool
         */
        function set_value($id, $value, $column_name, $primary_key) {
            $sql = "UPDATE " . $this->tablename . " SET " . $column_name . "=" . "'" . $value . "'";
            echo "<script>alert('" . $sql . "')</script>";
            if($res = mysqli_query($this->con, $sql)) {
                return true;
            }
            return false;
        }

        /**
         * Fügt einen Neuen Datensatz ein
         * WICHTIG: Erste Zeile muss eine Kopfzeile mit den Spaltennamen der Datenbank sein, zwelcks überprüfung!
         * @param array $values
         * @return bool
         */
        function insert_new($values) {
            $i = 0;
            $column_names = $this->get_column_names();
            $sql = "INSERT INTO " . $this->tablename . " (";
                $sql_values = "( '";
                foreach($column_names as $name) {
                    $sql .= $name . ", ";
                    if($this->is_ai() && $this->get_primarykey() == $name) {
                        $sql_values .= "', '";
                    }
                    else {
                        $sql_values .= $values[$i] . "', '";
                    }
                    $i++; 
                }
                $sql .= ") VALUES";
                $sql_values .= ")";
                // Alles nach dem letzten Kommas des SQL Befehls in die Temp Varriable schreiben
                $temp_string = strrchr($sql, ",");
                $temp_string_values = strrchr($sql_values, ",");

                // Zuerst das "," im Tmp String mit "" austauschen
                // dann den alten Tmp String mit dem Komma gegen den ohne Komma austauschen
                $sql = str_replace($temp_string, str_replace(",", "", $temp_string), $sql);
                $sql_values = str_replace($temp_string_values, str_replace(",", "", $temp_string_values), $sql_values);

                $temp_string_values = strrchr($sql_values, "'");
                $sql_values = str_replace($temp_string_values, str_replace("'", "", $temp_string_values), $sql_values);
                $sql .= " " . $sql_values;
                if($res = mysqli_query($this->con, $sql)) {
                    return true;
                }
                return false;
        }

        /**
         * Gibt einen gesuchten Wert der Tabelle zurück
         * @param mixed $id
         * @param string $column_name
         * @param string $primary_key
         * 
         * @return string
         */
        function get_value($id, $column_name, $primary_key) {
            $sql = "SELECT " . $column_name . " FROM " . $this->tablename . " WHERE " . $primary_key . "=" . "'" . $id . "'";
            echo $sql;
            $res = mysqli_query($this->con, $sql);
            while($record = mysqli_fetch_assoc($res)) {
                return $record[$column_name];
            }
        }
        
        /**
        * Gibt den Namen des Primarykeys als string aus
        * @return string
        */
        function get_primarykey() {
            $res = mysqli_query($this->con, "SHOW INDEX FROM " . $this->tablename . " WHERE Key_name = 'PRIMARY'");
            $cRow = mysqli_fetch_assoc($res);
            $column_name = $cRow["Column_name"];
            return $column_name;
        }

        /**
         * Gibt die Tabelle aus
         * Datensätze können:
         *  - Bearbeitet werden
         *  - Gelöscht werden
         *  - Hinzugefügt werden
         *  - Sortierung nach Spalten
         * @param string $file
         */
        function showTable($extra = "", $file = "#") {
            $column_names = $this->get_column_names();
            if($_GET['table_name'] != 'joined_cars' && $_GET['table_name'] != 'only_available' && $_GET['table_name'] != 'only_rented' && $_GET['table_name'] != 'joined_customers' && $_GET['table_name'] != 'joined_rental') {
                if(isset($_GET["insert"])) {
                    $sql = "INSERT INTO " . $this->tablename . " (";
                    $sql_values = "( '";
                    foreach($column_names as $name) {
                        $sql .= $name . ", ";
                        if($_GET["ai"] && $this->get_primarykey() == $name) {
                            $sql_values .= "', '";
                        }
                        else {
                            $sql_values .= $_GET[$name] . "', '";
                        }
                    }
                    $sql .= ") VALUES";
                    $sql_values .= ")";
                    // Alles nach dem letzten Kommas des SQL Befehls in die Temp Varriable schreiben
                    $temp_string = strrchr($sql, ",");
                    $temp_string_values = strrchr($sql_values, ",");
    
                    // Zuerst das "," im Tmp String mit "" austauschen
                    // dann den alten Tmp String mit dem Komma gegen den ohne Komma austauschen
                    $sql = str_replace($temp_string, str_replace(",", "", $temp_string), $sql);
                    $sql_values = str_replace($temp_string_values, str_replace(",", "", $temp_string_values), $sql_values);
    
                    $temp_string_values = strrchr($sql_values, "'");
                    $sql_values = str_replace($temp_string_values, str_replace("'", "", $temp_string_values), $sql_values);
                    $sql .= " " . $sql_values;
                    if($res = mysqli_query($this->con, $sql)) {
                        echo "<script>alert('Eintrag wurde hinzugefügt')</script>";
                    }
                }
    
                if(isset($_GET["deleted"])) {
                    if($_GET["deleted"] == "LÖSCHEN") {
                        echo "<script>
                                var confirm = confirm('Datensatz wirklich löschen?');
                                if(confirm) {
                                    window.location.href = '" . $file . "?table_name=" . $this->tablename . "&key=" . $_GET["key"] . "&deleted=deleted';
                                    
                                }
                                else {
                                    alert('Datensatz wurde NICHT gelöscht!');
                                    window.location.href = '" . $file . "?table_name=" . $this->tablename . "';
                                }
                            </script>";
                    }
                    else {
                        // SQL String zusammen bauen
                        $sql = "DELETE FROM " . $this->tablename . " WHERE " . $this->get_primarykey() . "=" . $_GET["key"];
                        echo $sql;
                        // SQL result
                        if($res = mysqli_query($this->con, $sql)) {
                            echo "<script>alert('Datensatz wurde gelöscht!');</script>";
                        }
                        else {
                            echo "<script>alert('Datensatz wurde NICHT gelöscht!');</script>";
                        }
                        echo "<script>window.location.href = '" . $file . "?table_name=" . $this->tablename . "'</script>";
                    }
                }
    
                if(isset($_GET["updated"])) {
                    // SQL String zusammen bauen
                    $sql = "UPDATE " . $this->tablename . " SET ";
                    foreach ($column_names as $name) {
                        if($name == "password") {
                            $sql .= $name . "='" . password_hash($_GET[$name], PASSWORD_DEFAULT) . "', ";
                        }
                        else {
                            $sql .= $name . "='" . $_GET[$name] . "', ";
                        }
                        
                    }
                    $sql .= " WHERE " . $primary_key . "='" . $_GET[$primary_key] . "'";
    
                    // Alles nach dem letzten Kommas des SQL Befehls in die Temp Varriable schreiben
                    $temp_string = strrchr($sql, ",");
    
                    // Zuerst das "," im Tmp String mit "" austauschen
                    // dann den alten Tmp String mit dem Komma gegen den ohne Komma austauschen
                    $sql = str_replace($temp_string, str_replace(",", "", $temp_string), $sql);
                    echo $sql;
                    // SQL result
                    if($res = mysqli_query($this->con, $sql)) {
                        echo "<script>alert('Eintrag wurde geändert')</script>";
                    }
                    else {
                        echo "<script>alert('Eintrag wurde NICHT geändert!')</script>";
                    }
                    echo "<script>window.location.href = '" . $file . "?table_name=" . $this->tablename . "'</script>";
                }
                $sort = " ORDER BY " . $this->get_primarykey() . " ASC";
            }

            echo "<style>
                    table, tr, td {
                    }
                </style>";
            echo "<table class='content-table'><thead><tr>";
            foreach($column_names as $name){
                $arr_getsort = $this->get_sort();
                if($name == $this->get_primarykey()) {
                    $string = "test";
                    $arr_getsort = $this->get_sort();
                    echo "<td style='cursor: help;' title='Primary Key'><b>" . $arr_getsort["arrow"] . "</b>&nbsp;&nbsp;<a href='" . $file . "?table_name=" . $_GET['table_name'] . "&sort=" . $name . "&direction=" . $arr_getsort["direction"] . "'><b>" . $name . "</b></a> &#x1F511;</td>";
                }
                else {
                    echo "<td><b>" . $arr_getsort["arrow"] . "</b>&nbsp;&nbsp;<a href='" . $file . "?table_name=" . $_GET['table_name'] . "&sort=" . $name . "&direction=" . $arr_getsort["direction"] . "'<b>" . $name . "</b></a></td>";
                }
            }
            if($_GET['table_name'] != 'joined_cars' && $_GET['table_name'] != 'only_available' && $_GET['table_name'] != 'only_rented' && $_GET['table_name'] != 'joined_customers' && $_GET['table_name'] != 'joined_rental') {
                echo "<td class='max' colspan='2'><b>Optionen</b></td>";
            }
            echo "</thead></tr><tr>";
            if($_GET['table_name'] != 'joined_cars' && $_GET['table_name'] != 'only_available' && $_GET['table_name'] != 'only_rented' && $_GET['table_name'] != 'joined_customers' && $_GET['table_name'] != 'joined_rental') {
                echo "<form action='" . $file . "' method='GET'>";
            foreach($column_names as $name){
                if($name == $this->get_primarykey()) {
                    if($this->is_ai()){
                        echo "<td><input type='" . $this->get_inputtype($name) . "' name='" . $name . "' required readonly value='' placeholder='auto_increment' title='Wird automatisch ausgefüllt' style='cursor: help;'></td>";
                    }
                    else {
                        echo "<td><input type='" . $this->get_inputtype($name) . "' name='" . $name . "' required></td>";
                    }
                }
                else {
                    echo "<td><input type='" . $this->get_inputtype($name) . "' name='" . $name . "' required></td>";
                }
                
            }
            echo "<td colspan='2'><input type='submit' name='insert' value='Eintrag hinzufügen' class='button button-2'></td>";
            }
            if($this->is_ai()) {
                echo "<input type='hidden' name='ai' value='true'>";
            }
            else {
                echo "<input type='hidden' name='ai' value='true'>";
            }
            echo "<input type='hidden' name='table_name' value='" . $this->tablename . "'>";
            echo "</form>";
            echo "</tr>";
            if(isset($_GET["sort"]) && isset($_GET["direction"])) {
                $sort = " ORDER BY " . $_GET["sort"] . " " . $_GET["direction"];
            }
            $sql = "SELECT * FROM " . $this->tablename . $sort;
            $res = mysqli_query($this->con, $sql);
            while($record = mysqli_fetch_assoc($res)){
                echo "<tr>";
                foreach($column_names as $name){
                    if($name == "password") {
                        echo "<td>*********</td>";
                    }
                    else {
                        echo "<td>" . $record[$name] . "</td>";
                    }
                }
                if($_GET['table_name'] != 'joined_cars' && $_GET['table_name'] != 'only_available' && $_GET['table_name'] != 'only_rented' && $_GET['table_name'] != 'joined_customers' && $_GET['table_name'] != 'joined_rental') {
                    echo "<td><a href='" . $file . "?update=true&key=" . $record[$this->get_primarykey()] . "&table_name=" . $this->tablename . "'>Bearbeiten</a></td>";
                    echo "<td><a href='" . $file . "?delete=true&key=" . $record[$this->get_primarykey()] . "&table_name=" . $this->tablename . "'>Löschen</a></td>";
                }
                echo "</tr>";
            }
            echo "</table>";

            if(isset($_GET["update"])) {
                $sql = "SELECT * FROM " . $this->tablename . " WHERE " . $this->get_primarykey() . "='" . $_GET["key"] . "'";
                $res = mysqli_query($this->con, $sql);
                $record = mysqli_fetch_assoc($res);
                echo "<hr>";
                echo "<h2>Datensatz bearbeiten</h2>";
                echo "<form action='" . $file . "' method=''GET>";
                foreach($column_names as $name) {
                    if($name == "password") {
                        echo "<input class='inputss' type='" . $this->get_inputtype($name) . "' name='" . $name . "' value'' placeholder='*********'>";
                        echo "<label><b> " . $name . "</b></label><br><br>";
                    }
                    else {
                        echo "<input class='inputss' type='" . $this->get_inputtype($name) . "' name='" . $name . "' value='" . $record[$name] . "'>";
                        echo "<label><b> " . $name . "</b></label><br><br>";
                    }
                }
                echo "<input type='submit' name='updated' value='Speichern' class='button button-2'>";
                echo "<input class='inputss' type='hidden' name='key' value='" . $_GET["key"] . "'>";
                echo "<input class='inputss' type='hidden' name='table_name' value='" . $_GET["table_name"] . "'>";
                echo "&nbsp;&nbsp;<a href='" . $file . "?table_name=" . $_GET["table_name"] . "'' class='button button-2'>Abbrechen</a>&nbsp;&nbsp;";
                echo "</form>";
                echo "</div>";
            }
            
            if(isset($_GET["delete"])) {
                $sql = "SELECT * FROM " . $this->tablename . " WHERE " . $this->get_primarykey() . "='" . $_GET["key"] . "'";
                $res = mysqli_query($this->con, $sql);
                $record = mysqli_fetch_assoc($res);
                echo "<hr>";
                echo "<h2>Datensatz löschen</h2>";
                echo "<form action='" . $file . "' method=''GET>";
                foreach($column_names as $name) {
                    echo "<input class='inputss' type='" . $this->get_inputtype($name) . "' name='" . $name . "' value='" . $record[$name] . "' readonly>";
                    echo "<label><b> " . $name . "</b></label><br><br>";
                }
                echo "<input class='inputss' type='submit' name='deleted' value='LÖSCHEN'>";
                echo "<input class='inputss' type='hidden' name='key' value='" . $_GET["key"] . "'>";
                echo "<input class='inputss' type='hidden' name='table_name' value='" . $_GET["table_name"] . "'>";
                echo "</form>";
                echo "&nbsp;&nbsp;<a href='" . $file . "?table_name=" . $_GET["table_name"] . "''>Abbrechen</a>&nbsp;&nbsp;";
            }

            if(isset($_GET["insert"]) && $_GET["insert"] != "Einfügen") {
                echo "<hr>";
                echo "<h2>Eingegebene Werte:</h2>";
                echo "<form action='" . $file . "' method=''GET>";
                foreach($column_names as $name) {
                    if($_GET["ai"] && $name == $this->get_primarykey()) {
                        echo "<input class='inputss' type='" . $this->get_inputtype($name) . "' name='" . $name . "' value='' readonly placeholder='auto_increment'>";
                    }
                    else {
                        echo "<input class='inputss' type='" . $this->get_inputtype($name) . "' name='" . $name . "' value='" . $_GET[$name] . "' readonly>";
                    }
                    
                    echo "<label><b> " . $name . "</b></label><br><br>";
                }
                echo "<input class='inputss' type='submit' name='insert' value='Einfügen'>";
                echo "<input class='inputss' type='hidden' name='table_name' value='" . $_GET["table_name"] . "'>";
                echo "<input class='inputss' type='hidden' name='ai' value='" . $_GET["ai"] . "'>";
                echo "</form>";
                echo "&nbsp;&nbsp;<a href='" . $file . "?table_name=" . $_GET["table_name"] . "''>Abbrechen</a>&nbsp;&nbsp;";
            }
        }

        /**
        * Gibt den input Typ entsprechend dem MySql Datentyp zurück
        * Parameter:
        *  - $column_name  = Spalten Name
        * @param string $column_name
        * @return string
        */
        function get_inputtype($column_name) {
            $table_name = $this->tablename;
            $con = $this->con;
            $res = mysqli_query($con, "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $table_name . "' AND COLUMN_NAME = '" . $column_name . "'");
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
         * Gibt zurück ob der Primary Key ein Auto Increment ist
         * @return bool
         */
        function is_ai() {
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='" . $this->tablename . "' AND COLUMN_NAME = '" . $this->get_primarykey() . "'";
            $res = mysqli_query($this->con, $sql);
            $info = mysqli_fetch_assoc($res);
            if($info["EXTRA"] == "auto_increment") {
                return true;
            }
            return false;
        }

        /**
         * Tauscht ASC/DESC
         * @return array
         */ 
        function get_sort() {
            $result["arrow"] = "&#x21e9;";
            if(isset($_GET["direction"])){
                if($_GET["direction"]  == "ASC"){
                    $result["direction"] = "DESC";
                    $result["arrow"] = "&#x21e7;";
                }
                else{
                    $result["direction"] = "ASC ";
                    $result["arrow"] = "&#x21e9;";
                }
            }
            else{
                $result["direction"] = "ASC";
                $result["arrow"] = "&#x21e9;";
            }
            return $result;
        }

        /**
         * Exportiert die Tabelle in eine CSV Datei
         * 
         */
        function csv_export() {
            
        }
    }

?>