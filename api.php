<?php
require_once 'imageEditor.php';

header('Content-Type: image/png');

$action = $_GET['action'];
$file = $_GET['image_id'];
$width = $_GET['width'];
$height = $_GET['height'];

$editor = new imageEditor($file);

switch ($action) {
    case "crop":
        $x = $_GET['x'];
        $y = $_GET['y'];
        $image = $editor->imageCrop($x, $y, $width, $height);
        break;
    case "resize":
        $image = $editor->imageResize($width, $height);
        break;
    case "full":
        $image = $editor->imageFull();
        break;
}
echo file_get_contents($image);

