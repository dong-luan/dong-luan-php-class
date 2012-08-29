<?php
include ('image_magic.php');
$img = new Image_magic("demo.jpg");
//$img -> rotation(45);
//$img -> resize("auto", 100);
//$img -> crop(0, 0, 100, 200);
//$img->add_watermark("daulau.jpg", 50, 50);

$text = "nguyen trang dong";

// mau nhap theo ten hoac theo ma hexa , vi du : #FFFFFF. 
// vi tri toa do x : left | center | right hoac numberic.
// vi tri toa do y : bottom | middle | top hoac numberic.

$img->insert_text($text,30,'blue','arial','left','top',0,'black',3);

//$img -> save('ka1.jpg');
  
$img -> show();

?>
