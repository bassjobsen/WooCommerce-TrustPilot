<?php
$filenaam= "video.mp4";
		$filenaam2 = substr($filenaam, 0, strrpos($filenaam, '.'));
		$extension = substr($filenaam, strrpos($filenaam, '.')+1);
echo $extension;
