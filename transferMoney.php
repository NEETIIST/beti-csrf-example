<?php
/**
 * Created by PhpStorm.
 * User: Filipe
 * Date: 27/06/17
 * Time: 19:08
 */

$host        = "host = 127.0.0.1";
$port        = "port = 5432";
$dbname      = "dbname = beti_db";
$credentials = "user = beti password=password";

$db = pg_connect( "$host $port $dbname $credentials"  );
if(!$db) {
    echo "Error : Unable to open database\n";
} else {
    echo "Opened database successfully\n";
}

#TODO GET those values from html
echo "<br />";
$user1=$_COOKIE["user"];
$user=$_REQUEST['user'];
$value=$_REQUEST['value'];
$sql =<<<EOF
      START TRANSACTION;
      
        INSERT INTO TRANSFERS (ACCOUNT1,ACCOUNT2,VALUE)
        VALUES ('$user1', '$user', $value);
      
        UPDATE ACCOUNTS set BALANCE = BALANCE + $value where NAME='$user';
        UPDATE ACCOUNTS set BALANCE = BALANCE - $value where NAME='$user1';
        
      COMMIT;
EOF;
$ret = pg_query($db, $sql);
if(!$ret) {
    echo pg_last_error($db);
    exit;
} else {
    echo "Record updated successfully\n";
}

$sql =<<<EOF
      SELECT * from ACCOUNTS;
EOF;

$ret = pg_query($db, $sql);
if(!$ret) {
    echo pg_last_error($db);
    exit;
}
while($row = pg_fetch_row($ret)) {
    echo "ID = ". $row[0] . "\n";
    echo "NAME = ". $row[1] ."\n";
    echo "BALANCE = ". $row[2] ."\n";
}
echo "Operation done successfully\n";
pg_close($db);
?>