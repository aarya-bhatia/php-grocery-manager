<?php

include 'config.php';

global $hostame, $db_user, $db_pass, $db_name;

$con = mysqli_connect($hostame, $db_user, $db_pass, $db_name);

if(!$con)
{
    die("cannot connect to server");
}

function insert(string $name, int $quantity, int $status, string $date)
{
    global $con;

    $sql = "INSERT INTO grocerytb (Item_name,Item_Quantity,Item_status,Date) VALUES ('$name',$quantity,$status,'$date')";

    $ver = mysqli_query($con, $sql);
    if(!$ver)
    {
        echo mysqli_error($con);
        die();
    }
}

for($i = 0; $i < 20; $i++)
{
    insert('Test #'.$i,rand(1,3),0,'2020-04-'.rand(1,28));
}

mysqli_close($con);
header("location:index.php");