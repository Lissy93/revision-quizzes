<?php

class Db {

    function connect(){
        $username = ""; // Put your MySQL Username here
        $password = ""; // Put your MySQL Password here
        $hostname = "localhost"; // The address to your MySQL server
        $database = "asykes_quiz"; // The name of the database to use

        $dbhandle = mysql_connect($hostname, $username, $password)
        or die("Unable to connect to MySQL");

        $selected = mysql_select_db($database,$dbhandle)
        or die("Could not select examples");

    }


    function query($query){
        $result = mysql_query($query);

        if (!$result) {
            $message  = 'Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;
            die($message);
        }

        $returnResult = array();

        while( $row = mysql_fetch_assoc( $result)){
            $returnResult[] = $row; // Inside while loop
        }
        return $returnResult;
    }

}