<?php

$serverName = "localhost";
// $connectionInfo = array( "Database"=>"cpms", "UID"=>"ciputraqs", "PWD"=>"ciputraqs123");
$connectionInfo = array( "Database"=>"cpms");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}

?>