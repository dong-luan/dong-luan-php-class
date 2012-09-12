<?php
include ('image_magic.php');
$img = new Image_magic("demo.jpg");
$img -> rotation(45);
$img -> resize("auto", 100);
//$img -> crop(0, 0, 100, 200);
//$img->add_watermark("daulau.jpg", 50, 50);

//$img->insert_text('Nguyen Trang Dong',30,'red','28 Days Later',10,10,0,'black',3);


$img -> show();

?>