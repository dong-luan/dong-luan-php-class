<?php
 $string="xin chao cac bo tre";
 $encrypt=base64_encode($string);
 echo $string;
 echo $encrypt;
 echo base64_decode($encrypt);
?>