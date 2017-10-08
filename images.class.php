<?php

require_once 'aws/aws-autoloader.php';

class images
{

    /**
     * @param $file - image file
     * Upload image via file
     */
    public function file_upload($file)
    {
        $upload_dir = 'temp/';
        $upload_file = $upload_dir . basename($file['image']['name']);
        $image = getimagesize($file['image']['tmp_name']);
        if (in_array($image[2], array(IMAGETYPE_JPEG, IMAGETYPE_PNG))) {

            if (move_uploaded_file($file['image']['tmp_name'], $upload_file)) {
                $result = $this->s3_upload($upload_file);
                $image_id = explode('/', $result['ObjectURL'])[3];
                $json_result = ["success" => 1, "id" => $image_id, "width" => $image[0], "height" => $image[1], "format" => ($image[2] == IMAGETYPE_JPEG ? "jpg" : "png")];

                echo json_encode($json_result);
                unlink($upload_file);
            }
        } else {
            $json_result = ["success" => 0, "message" => "Wrong file type"];

            echo json_encode($json_result);
        }
    }

    /**
     * @param $file - image url
     * Upload image via URL
     */
    public function file_url_upload($file)
    {
        $image = getimagesize($file);
        if (in_array($image[2], array(IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
            $temp_name = $this->fileNameGenerate(32);
            file_put_contents("temp/" . $temp_name, file_get_contents($file));
            $result = $this->s3_upload("temp/" . $temp_name);
            $image_id = explode('/', $result['ObjectURL'])[3];
            $json_result = ["response" => "ok", "code" => 1, "image_id" => $image_id];
            echo json_encode($json_result);
            unlink("temp/" . $temp_name);
        } else {
            exit("Error! Wrong file type.");
        }
    }

    /**
     * @param $tmp_file
     * @return \Aws\Result
     * Main Amazon s3 upload function
     */
    public function s3_upload($tmp_file)
    {
        $bucket = 'codex-images';
        $keyname = $this->fileNameGenerate(32) . date("dmyHis");

        $filepath = $tmp_file;

        $s3Client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => [
                'key' => 'AKIAJJ6ONBBV4DVGOA4Q',
                'secret' => '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',
            ],
        ]);

        $result = $s3Client->putObject(array(
            'Bucket' => $bucket,
            'Key' => $keyname,
            'SourceFile' => $filepath,
            'ContentType' => 'image/jpeg',
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata' => array(
                'param1' => 'value 1',
                'param2' => 'value 2'
            )
        ));

        return $result;
    }

    /**
     * @param int $length
     * @return string
     * Random name generator
     */
    function fileNameGenerate($length = 32)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}