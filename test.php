<?php

 //$string="xin chao cac bo tre";
 //$encrypt=base64_encode($string);
 //echo $string;
 //echo $encrypt;
 //echo base64_decode($encrypt);


include ('image_magic.php');
$img = new Image_magic("demo.jpg");
//$img -> rotation(45);
//$img -> resize("auto", 100);
//$img -> crop(0, 0, 100, 200);
//$img->add_watermark("daulau.jpg", 50, 50);

//$img->insert_text('Nguyen Trang Dong',30,'red','28 Days Later',10,10,0,'black',3);


//$img -> save('ka1.jpg');

//$img->test_insert_text("lay tam cai anh hot girl",30);
$img->add_text();
$img -> show();
?>