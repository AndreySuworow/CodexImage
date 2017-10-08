<?php
require_once "images.class.php";

$source = $_POST['image_source'];
$image = new images();
if ($source == 1) {
    $image->file_url_upload($_POST['image_url']);
} else {
    $image->file_upload($_FILES);
}

