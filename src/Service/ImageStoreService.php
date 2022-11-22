<?php

namespace App\Service;

use Exception;

class ImageStoreService
{
    private $dir;
    private $publicPath;

    public function __construct($dir, $publicPath)
    {
        $this->dir = $dir;
        $this->publicPath = $publicPath;
    }

    public function removeImage(string $image)
    {
        try {
            $file = $this->dir . $image;
            if (file_exists($file)) {
                if (!unlink($file)) {
                    throw new Exception('Unable to remove file');
                }
            }
        } catch (\Exception $e) {
            error_log($e->getMessage() . ': ' . $image);
        }
    }

    /**
     * Example: data:image/png;base64,iVBORw
     *
     * @param string $string
     * @return string|false
     */
    public function storeFromBase64String(string $string)
    {
        if (substr($string, 0, 11) !== 'data:image/') {
            return false;
        }

        $dataParts = explode(';base64,', $string);

        $dataType = $dataParts[0];
        $imageData = $dataParts[1] ?? null;
        if (!$imageData) {
            error_log('Unable to process file upload ' . $string);
            return false;
        }

        $mimeType = str_replace('data:', '', $dataType);
        $ext = $this->mime2ext($mimeType);

        if (!$ext) {
            error_log('Unable to process file upload ' . $string);
            return false;
        }

        $fileData = base64_decode($imageData);
        if (!$fileData) {
            error_log('Unable to process file upload ' . $string);
            return false;
        }
        $filename = uniqid('blog-') . '.' . $ext;
        file_put_contents($this->dir . '/' . $filename, $fileData);

        return $this->publicPath . '/' . $filename;
    }

    public function mime2ext($mime)
    {
        $map = [
            'image/gif' => 'gif',
            'image/jpeg' => 'jpeg',
            'image/pjpeg' => 'jpeg',
            'image/png' => 'png',
            'image/x-png' => 'png',
            'image/svg+xml' => 'svg',
            'image/webp' => 'webp',
        ];

        return $map[$mime] ?? false;
    }
}
