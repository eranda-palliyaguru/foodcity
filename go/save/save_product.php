<?php
include("../connect.php");

$name=$_POST['name'];
$size=$_POST['size'];
$sell=$_POST['sell'];
$cost=$_POST['cost'];
$id=$_POST['brand'];
$about=$_POST['about'];
$date=date('Y-m-d');

$about= (float) strtr($about, ['-' => '',]);
$about= (float) strtr($about, [',' => '',]);

$stmt = $db->query("SELECT * FROM brand WHERE id='$id'");
while ($row = $stmt->fetch())
{ $brand_name=$row['name']; }

$sql = "INSERT INTO product (name, size, brand_id, brand, sell_price, cost_price, up_date, about) VALUES ('$name', '$size', '$id','$brand_name', '$sell', '$cost', '$date', '$about')";
if ($db->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
//  echo "Error: " . $sql . "<br>" . $db->error;
}
//$db->close();

$stmt = $db->query("SELECT * FROM product WHERE action='0' ORDER by id DESC limit 0,1");
while ($row = $stmt->fetch())
{ $product_id=$row['id']; }


$file_name = $_FILES['image']['name'];
$file_type = $_FILES['image']['type'];
$file_size = $_FILES['image']['size'];
$temp_name = $_FILES['image']['tmp_name'];

$temp = explode(".", $file_name);
$file_name = "m_".date('ymdhis') . '.' . end($temp);

$upload_to = '../../../images/product/';

// checking file size
if ($file_size > 50000000) {
  $errors[] = 'File size should be less than 500kb.';
}

if (!$file_size) {
  $errors[] = 'File size should be less than 500kb.';
}

if (empty($errors)) {
  $file_uploaded = move_uploaded_file($temp_name, $upload_to . $file_name);

  $sql = "UPDATE product
          SET img1=?
  		WHERE id=?";
  $q = $db->prepare($sql);
  $q->execute(array($file_name,$product_id));

}

//----------- img 2 ---------------//

$file_name2 = $_FILES['image2']['name'];
$file_type2 = $_FILES['image2']['type'];
$file_size2 = $_FILES['image2']['size'];
$temp_name2 = $_FILES['image2']['tmp_name'];

$temp = explode(".", $file_name2);
$file_name2 = "ot1_".date('ymdhis') . '.' . end($temp);
// checking file size
if ($file_size2 > 50000000) {
  $errors[] = 'File size should be less than 500kb.';
}

if (!$file_size2) {
  $errors[] = 'File size should be less than 500kb.';
}

if (empty($errors)) {
  $file_uploaded = move_uploaded_file($temp_name2, $upload_to . $file_name2);

  $sql = "INSERT INTO img_hub (name,product_id,product_name) VALUES ('$file_name2','$product_id','$name')";
  if ($db->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
  //  echo "Error: " . $sql . "<br>" . $db->error;
  }
}

//-------------- img 3 ------------//
$file_name3 = $_FILES['image3']['name'];
$file_type3 = $_FILES['image3']['type'];
$file_size3 = $_FILES['image3']['size'];
$temp_name3 = $_FILES['image3']['tmp_name'];

$temp = explode(".", $file_name3);
$file_name3 = "ot2_".date('ymdhis') . '.' . end($temp);
// checking file size
if ($file_size3 > 50000000) {
  $errors[] = 'File size should be less than 500kb.';
}

if (!$file_size3) {
  $errors[] = 'File size should be less than 500kb.';
}

if (empty($errors)) {
  $file_uploaded = move_uploaded_file($temp_name3, $upload_to . $file_name3);

  $sql = "INSERT INTO img_hub (name,product_id,product_name) VALUES ('$file_name3','$product_id','$name')";
  if ($db->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
  //  echo "Error: " . $sql . "<br>" . $db->error;
  }
}



  header("location:../product");


 ?>
