<?php
include "includes/config.php";

$id = $_POST['id'];
$name = $_POST['name'];

mysqli_query($conn,"UPDATE students SET name='$name' WHERE id=$id");
header("Location: students.php");
