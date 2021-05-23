<?php
include("../connect.php");

$name=$_POST['name'];
$id=$_POST['make'];
$date=date('Y-m-d');

$stmt = $db->query("SELECT * FROM make WHERE id='$id'");
while ($row = $stmt->fetch())
{ $make_name=$row['name']; }

$sql = "INSERT INTO brand (name, make_id,make_name, up_date) VALUES ('$name', '$id','$make_name', '$date')";

if ($db->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
//  echo "Error: " . $sql . "<br>" . $db->error;
}
//$db->close();
$stmt = $db->query("SELECT * FROM brand WHERE action='0' ORDER by id DESC limit 0,1");
while ($row = $stmt->fetch())
{ $product_id=$row['id']; }

$file_name = $_FILES['image']['name'];
$file_type = $_FILES['image']['type'];
$file_size = $_FILES['image']['size'];
$temp_name = $_FILES['image']['tmp_name'];

$temp = explode(".", $file_name);
$file_name = "B_".date('ymdhis') . '.' . end($temp);

$upload_to = '../../../images/brand/';

// checking file size
if ($file_size > 50000000) {
  $errors[] = 'File size should be less than 500kb.';
}

if (empty($errors)) {
  $file_uploaded = move_uploaded_file($temp_name, $upload_to . $file_name);

  $sql = "UPDATE brand
          SET img=?
  		WHERE id=?";
  $q = $db->prepare($sql);
  $q->execute(array($file_name,$product_id));

}


header("location:../brand");
 ?>
