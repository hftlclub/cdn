<?php
$config = json_decode(file_get_contents('./upload.config.json'), TRUE);

// check for access secret
if(!in_array($_GET['secret'], $config['secrets'])) {
    header('HTTP/1.1 401 Unauthorized');
    die('Unauthorized');
}

// check for HTTP method (only POST allowed)
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit();
}



$file = $_FILES['file'];
$mimeType = mime_content_type($file['tmp_name']);

// check for allowed MIME type
if(!in_array($mimeType, $config['allowedMimeTypes'])) {
    header('HTTP/1.1 400 Bad Request');
    die('File type not allowed');
}


$hash = hash_file('crc32', $file['tmp_name']);
$destDir = $hash . '/';
mkdir($config['uploadDir'] . $destDir);

$uploadFile = $destDir . basename($file['name']);
move_uploaded_file($file['tmp_name'], $config['uploadDir'] . $uploadFile);

$fullUrl = $config['uploadDirUrl'] . $uploadFile;
$filename = basename($file['name']);



header('Content-Type: application/json');
echo '{
   "fullUrl": "'.$fullUrl.'",
   "filename": "'.$filename.'",
   "size": '.$file['size'].',
   "mimeType": "'.$mimeType.'"
}';
exit();
?>