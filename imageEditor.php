<?php

class imageEditor
{
    var $url;

    /**
     * imageEditor constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param int $x
     * @param int $y
     * @param $width
     * @param $height
     * @return string
     * Image crop function
     */
    function imageCrop($x = 0, $y = 0, $width, $height)
    {
        $action = 'crop' . '(' . $x . ',' . $y . ')' . $width . 'x' . $height;
        if ($this->checkCache($action)) {
            return 'cache/' . $this->url . $action;
        } else {
            $image_type = getimagesize("https://codex-images.s3.amazonaws.com/" . $this->url)[2];
            if ($image_type == IMAGETYPE_JPEG) {
                $im = imagecreatefromjpeg("https://codex-images.s3.amazonaws.com/" . $this->url);
            } else {
                $im = imagecreatefrompng("https://codex-images.s3.amazonaws.com/" . $this->url);
            }
            $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
            $this->doCache($im2, 'crop' . '(' . $x . ',' . $y . ')' . $width . 'x' . $height);
            return 'cache/' . $this->url . 'crop' . '(' . $x . ',' . $y . ')' . $width . 'x' . $height;
        }
    }

    /**
     * @param $width
     * @param $height
     * @return string
     * Image resize function
     */
    function imageResize($width, $height)
    {
        $action = 'resize' . $width . 'x' . $height;
        if ($this->checkCache($action)) {
            return 'cache/' . $this->url . $action;
        } else {
            $image_type = getimagesize("https://codex-images.s3.amazonaws.com/" . $this->url)[2];
            if ($image_type == IMAGETYPE_JPEG) {
                $im = imagecreatefromjpeg("https://codex-images.s3.amazonaws.com/" . $this->url);
            } else {
                $im = imagecreatefrompng("https://codex-images.s3.amazonaws.com/" . $this->url);
            }
            $im2 = imagecreatetruecolor($width, $height);
            imagecopyresized($im2, $im, 0, 0, 0, 0, $width, $height, imagesx($im), imagesy($im));
            $this->doCache($im2, $action);
            return 'cache/' . $this->url . $action;
        }

    }

    /**
     * @return string
     * Returns full image
     */
    function imageFull()
    {
        if ($this->checkCache('full')) {
        } else {
            file_put_contents('cache/' . $this->url . 'full', file_get_contents("https://codex-images.s3.amazonaws.com/" . $this->url));

        }
        return 'cache/' . $this->url . 'full';
    }

    /**
     * @param $im
     * @param $action
     * Function caching image
     */
    function doCache($im, $action)
    {
        imagepng($im, 'cache/' . $this->url . $action);
    }

    /**
     * @param $action
     * @return bool
     * function checking if image already cached
     */
    function checkCache($action)
    {
        if (file_exists('cache/' . $this->url . $action)) {
            return true;
        } else {
            return false;
        }
    }
}