<?php
include("../connect.php");

$name=$_POST['name'];
$country=$_POST['country'];
$date=date('Y-m-d');

$sql = "INSERT INTO make (name, country, up_date) VALUES ('$name', '$country', '$date')";

if ($db->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
//  echo "Error: " . $sql . "<br>" . $db->error;
}
//$db->close();

header("location:../make");
 ?>
