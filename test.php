<?php
//include ('image_magic.php');

include ('easy_image.php');
$img = new easy_image("daulau.jpg");

//$img -> resize("auto", 100);
//$img -> crop(0, 0, 100, 200);
//$img->add_watermark("daulau.jpg", 50, 50);

// 1 tham so : $text
//$img -> add_text("Nguyen trang dong");

// 2 tham so : $text, $font_size
// $img->add_text("Nguyen trang dong",60);

// 3 tham so : $text, $font_size, $font
// $img->add_text("Nguyen trang dong",60,'28 days later');

// 3 tham so : $text, $color, $font_size
// $img->add_text("Nguyen trang dong",'red',60);

// 4 tham so : $text, $font_size, $font
// $img->add_text("Nguyen trang dong",60,'28 days later');

//--------------------------------------------------------------------------------------
// 5 tham so : $text, $font_size, $color, $x, $y
// $img->add_text("Nguyen trang dong",40,'red','right','bottom');

// 7 tham so : $text, $font_size, $color, $x, $margin_x, $y, $margin_y
$img -> add_text("Nguyen trang dong", 30, 'white', 'right', 60, 'bottom', 40);

// 6 tham so : $text, $font_size, $color, $x, $y, $font
// $img->add_text("Nguyen trang dong",40,'red','right','bottom','28 days later');

// 8 tham so : $text, $font_size, $color, $x, $margin_x, $y, $margin_y, $font
//$img -> add_text("Nguyen trang dong", 30, 'white', 'right', 0, 'top', 50, '28 days later');

//--------------------------------------------------------------------------------------
// 6 tham so : $text, $color, $font_size, $x, $y, $angle
// $img->add_text("Nguyen trang dong",'red',40,'center','middle',30);

// 8 tham so : $text, $color, $font_size, $x, $margin_x, $y, $margin_y, $angle
// $img -> add_text("Nguyen trang dong", 'white', 20, 'left', 10, 'top', 40, 10);

// 7 tham so : $text, $color, $font_size, $x, $y, $angle, $font
// $img->add_text("Nguyen trang dong",'red',40,'center','middle',30,'28 days later');

// 9 tham so : $text, $color, $font_size, $x, $margin_x, $y, $margin_y, $angle, $font
// $img -> add_text("Nguyen trang dong", 'white', 20, 'left', 10, 'top', 40, 10, '28 days later');

//--------------------------------------------------------------------------------------

// 8 tham so : $text, $color, $font_size, $x, $y, $angle, $shadow_color, $shadow_width
// $img->add_text("Nguyen trang dong",'red',40,'center','middle',10,'blue',2);

// 10 tham so : $text, $color, $font_size, $x, $margin_x, $y, $margin_y, $angle, $shadow_color, $shadow_width
// $img -> add_text("Nguyen trang dong", 'red', 40, 'left', 10, 'top', 10, 0, 'blue', 2, '28 days later');

// 9 tham so : $text, $color, $font_size, $x, $y, $angle, $shadow_color, $shadow_width, $font
// $img->add_text("Nguyen trang dong",'red',40,'center','middle',10,'blue',2,'28 days later');

// 11 tham so : $text, $color, $font_size, $x, $margin_x, $y, $margin_y, $angle, $shadow_color, $shadow_width, $font
// $img -> add_text("Nguyen trang dong", 'red', 40, 'left', 10, 'top', 10, 0, 'blue', 2, '28 days later');
//------------------------------------------------------------------------------------------------

$img -> show();
?>