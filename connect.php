<?php
$con = mysqli_connect("localhost", "root", "root", "grocerydb");

if (!$con) {
    die("cannot connect to server");
}

/**
 * Id: int(10)
 * Item_name: varchar(30)
 * Item_Quantity: int(100)
 * Item_status: varchar(20)
 * Date: date
 */