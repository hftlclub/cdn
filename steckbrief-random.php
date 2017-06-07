<?php
$imgDir = "./sync/steckbriefe";
$allowedIpRanges = array('10.26.180.0/24', '31.6.0.0/16', '195.145.75.0/24'); 
$defaultImg = "default.jpg";

/****************************/

$imgFormat = $_GET['format'];
if(!$imgFormat) $imgFormat = 'jpg';



$images = array();

$dir = opendir($imgDir);
$excluded = array(".", "..", $defaultImg);
while($img = readdir($dir)){
	if(in_array($img, $excluded)) continue;
	array_push($images, $img);
}
closedir($dir);


// default image when there are no other images
if(!count($images)) {
	sendImage($defaultImg, $imgFormat, $imgDir);
	exit();
}

// default image if IP is not in allowed ranges
if(!isInAnyRange($_SERVER['REMOTE_ADDR'], $allowedIpRanges)) {
	sendImage($defaultImg, $imgFormat, $imgDir);
	exit();
}




// randomly select one image
$randomImage = $images[rand(0, count($images) - 1)];
sendImage($randomImage, $imgFormat, $imgDir);
exit();




function sendImage($imgFilename, $imgFormat, $imgDir) {
	$image = imagecreatefromstring(file_get_contents($imgDir."/".$imgFilename));	


	// output image
	switch($imgFormat) {
		case 'jpg':
		case 'jpeg':
			header('Content-Type: image/jpeg');
        		imagejpeg($image);
			return;

		case 'png':
			header('Content-Type: image/png');
                        imagepng($image);
                        return;
		default:
			return;
	}
}


function isInRange($checkip, $range) {
	@list($ip, $len) = explode('/', $range);
	if (($min = ip2long($ip)) !== false && !is_null($len)) {
		$clong = ip2long($checkip);
		$max = ($min | (1<<(32-$len))-1);
		if ($clong > $min && $clong < $max) {
			return true;
		} else {
			return false;
		}
	}
}

function isInAnyRange($checkip, $ranges) {
	foreach($ranges as $range) {
		if(isInRange($checkip, $range)) return true;
	}
	return false;
}

?>
