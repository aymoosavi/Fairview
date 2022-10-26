<?php

namespace Fairview\lib\Classes;

use Fairview\lib\Exceptions\ImageUrlNotProvidedException;

class ImageUploader
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @throws ImageUrlNotProvidedException
     */
    public function upload(): string
    {
        if (!$this->url) {
            throw new ImageUrlNotProvidedException();
        }

        if (!$this->existsImage()) {
            return $this->uploadImage();
        }

        return $this->getImageShortPath();
    }

    private function hashUrl(): string
    {
        return md5($this->url);
    }

    private function getImageFullPath(): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'fairview' . $this->getImageShortPath();
    }

    private function getImageShortPath(): string
    {
        $pathInfo = pathinfo($this->url);
        return DIRECTORY_SEPARATOR . Config::$IMAGE_UPLOAD_PATH
            . DIRECTORY_SEPARATOR . $this->hashUrl() . '.' . $pathInfo['extension'];
    }

    private function existsImage(): bool
    {
        return file_exists($this->getImageFullPath());
    }

    private function uploadImage(): string
    {
        $imagePath = $this->getImageFullPath();
        $imageContents = file_get_contents($this->url);

        if (!$imageContents) {
            return '';
        }

        if (file_put_contents($imagePath, $imageContents)) {
            return $this->getImageShortPath();
        }

        return '';
    }
}