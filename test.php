<?php
include ('image_magic.php');
$img = new Image_magic("demo.jpg");
//$img -> rotation(45);
//$img -> resize("auto", 100);
//$img -> crop(0, 0, 100, 200);
//$img->add_watermark("daulau.jpg", 50, 50);

$img->insert_text('Nguyen Trang Dong',30,'light purple','28 Days Later',10,10,0,'#000000',3);


//$img -> save('ka1.jpg');

//$img->test_insert_text("lay tam cai anh hot girl ;))",30);

$img -> show();

?>