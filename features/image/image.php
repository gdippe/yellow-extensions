<?php
// Image extension, https://github.com/datenstrom/yellow-extensions/tree/master/features/image
// Copyright (c) 2013-2020 Datenstrom, https://datenstrom.se
// This file may be used and distributed under the terms of the public license.

class YellowImage {
    const VERSION = "0.8.7";
    const TYPE = "feature";
    public $yellow;             //access to API

    // Handle initialisation
    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault("imageAlt", "Image");
        $this->yellow->system->setDefault("imageUploadWidthMax", "1280");
        $this->yellow->system->setDefault("imageUploadHeightMax", "1280");
        $this->yellow->system->setDefault("imageUploadJpgQuality", "80");
        $this->yellow->system->setDefault("imageThumbnailLocation", "/media/thumbnails/");
        $this->yellow->system->setDefault("imageThumbnailDir", "media/thumbnails/");
        $this->yellow->system->setDefault("imageThumbnailJpgQuality", "80");
    }

    // Handle page content of shortcut
    public function onParseContentShortcut($page, $name, $text, $type) {
        $output = null;
        if ($name=="image" && $type=="inline") {
            list($name, $alt, $style, $width, $height) = $this->yellow->toolbox->getTextArgs($text);
            if (!preg_match("/^\w+:/", $name)) {
                if (empty($alt)) $alt = $this->yellow->system->get("imageAlt");
                if (empty($width)) $width = "100%";
                if (empty($height)) $height = $width;
                list($src, $width, $height) = $this->getImageInformation($this->yellow->system->get("coreImageDir").$name, $width, $height);
            } else {
                if (empty($alt)) $alt = $this->yellow->system->get("imageAlt");
                $src = $this->yellow->lookup->normaliseUrl("", "", "", $name);
                $width = $height = 0;
            }
            $output = "<img src=\"".htmlspecialchars($src)."\"";
            if ($width && $height) $output .= " width=\"".htmlspecialchars($width)."\" height=\"".htmlspecialchars($height)."\"";
            if (!empty($alt)) $output .= " alt=\"".htmlspecialchars($alt)."\" title=\"".htmlspecialchars($alt)."\"";
            if (!empty($style)) $output .= " class=\"".htmlspecialchars($style)."\"";
            $output .= " />";
        }
        return $output;
    }
    
    // Handle media file changes
    public function onEditMediaFile($file, $action) {
        if ($action=="upload") {
            $fileName = $file->fileName;
            list($widthInput, $heightInput, $type) = $this->yellow->toolbox->detectImageInformation($fileName, $file->get("type"));
            $widthMax = $this->yellow->system->get("imageUploadWidthMax");
            $heightMax = $this->yellow->system->get("imageUploadHeightMax");
            if (($widthInput>$widthMax || $heightInput>$heightMax) && ($type=="gif" || $type=="jpg" || $type=="png")) {
                list($widthOutput, $heightOutput) = $this->getImageDimensionsFit($widthInput, $heightInput, $widthMax, $heightMax);
                $image = $this->loadImage($fileName, $type);
                $image = $this->resizeImage($image, $widthInput, $heightInput, $widthOutput, $heightOutput);
                $image = $this->orientImage($image, $fileName, $type);
                if (!$this->saveImage($image, $fileName, $type, $this->yellow->system->get("imageUploadJpgQuality"))) {
                    $file->error(500, "Can't write file '$fileName'!");
                }
            }
        }
    }
    
    // Handle command
    public function onCommand($args) {
        list($command) = $args;
        switch ($command) {
            case "clean":   $statusCode = $this->processCommandClean($args); break;
            default:        $statusCode = 0;
        }
        return $statusCode;
    }

    // Process command to clean thumbnails
    public function processCommandClean($args) {
        $statusCode = 0;
        list($command, $path) = $args;
        if ($path=="all") {
            $path = $this->yellow->system->get("imageThumbnailDir");
            foreach ($this->yellow->toolbox->getDirectoryEntries($path, "/.*/", false, false) as $entry) {
                if (!$this->yellow->toolbox->deleteFile($entry)) $statusCode = 500;
            }
            if ($statusCode==500) echo "ERROR cleaning thumbnails: Can't delete files in directory '$path'!\n";
        }
        return $statusCode;
    }

    // Return image info, create thumbnail on demand
    public function getImageInformation($fileName, $widthOutput, $heightOutput) {
        $fileNameShort = substru($fileName, strlenu($this->yellow->system->get("coreImageDir")));
        list($widthInput, $heightInput, $type) = $this->yellow->toolbox->detectImageInformation($fileName);
        $widthOutput = $this->convertValueAndUnit($widthOutput, $widthInput);
        $heightOutput = $this->convertValueAndUnit($heightOutput, $heightInput);
        if (($widthInput==$widthOutput && $heightInput==$heightOutput) || $type=="svg") {
            $src = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("coreImageLocation").$fileNameShort;
            $width = $widthOutput;
            $height = $heightOutput;
        } else {
            $fileNameThumb = ltrim(str_replace(array("/", "\\", "."), "-", dirname($fileNameShort)."/".pathinfo($fileName, PATHINFO_FILENAME)), "-");
            $fileNameThumb .= "-".$widthOutput."x".$heightOutput;
            $fileNameThumb .= ".".pathinfo($fileName, PATHINFO_EXTENSION);
            $fileNameOutput = $this->yellow->system->get("imageThumbnailDir").$fileNameThumb;
            if ($this->isFileNotUpdated($fileName, $fileNameOutput)) {
                $image = $this->loadImage($fileName, $type);
                $image = $this->resizeImage($image, $widthInput, $heightInput, $widthOutput, $heightOutput);
                $image = $this->orientImage($image, $fileName, $type);
                if (is_file($fileNameOutput)) $this->yellow->toolbox->deleteFile($fileNameOutput);
                if (!$this->saveImage($image, $fileNameOutput, $type, $this->yellow->system->get("imageThumbnailJpgQuality")) ||
                    !$this->yellow->toolbox->modifyFile($fileNameOutput, $this->yellow->toolbox->getFileModified($fileName))) {
                    $this->yellow->page->error(500, "Can't write file '$fileNameOutput'!");
                }
            }
            $src = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("imageThumbnailLocation").$fileNameThumb;
            list($width, $height) = $this->yellow->toolbox->detectImageInformation($fileNameOutput);
        }
        return array($src, $width, $height);
    }
    
    // Return image dimensions that fit, scale proportional
    public function getImageDimensionsFit($widthInput, $heightInput, $widthMax, $heightMax) {
        $widthOutput = $widthMax;
        $heightOutput = $widthMax * ($heightInput / $widthInput);
        if ($heightOutput>$heightMax) {
            $widthOutput = $widthOutput * ($heightMax / $heightOutput);
            $heightOutput = $heightOutput * ($heightMax / $heightOutput);
        }
        return array(intval($widthOutput), intval($heightOutput));
    }

    // Load image from file
    public function loadImage($fileName, $type) {
        $image = false;
        switch ($type) {
            case "gif": $image = @imagecreatefromgif($fileName); break;
            case "jpg": $image = @imagecreatefromjpeg($fileName); break;
            case "png": $image = @imagecreatefrompng($fileName); break;
        }
        return $image;
    }
    
    // Save image to file
    public function saveImage($image, $fileName, $type, $quality) {
        $ok = false;
        switch ($type) {
            case "gif": $ok = @imagegif($image, $fileName); break;
            case "jpg": $ok = @imagejpeg($image, $fileName, $quality); break;
            case "png": $ok = @imagepng($image, $fileName); break;
        }
        return $ok;
    }

    // Create image from scratch
    public function createImage($width, $height) {
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        return $image;
    }

    // Resize image
    public function resizeImage($image, $widthInput, $heightInput, $widthOutput, $heightOutput) {
        $widthFit = $widthInput * ($heightOutput / $heightInput);
        $heightFit = $heightInput * ($widthOutput / $widthInput);
        $widthDiff = abs($widthOutput - $widthFit);
        $heightDiff = abs($heightOutput - $heightFit);
        $imageOutput = $this->createImage($widthOutput, $heightOutput);
        if ($heightFit>$heightOutput) {
            imagecopyresampled($imageOutput, $image, 0, $heightDiff/-2, 0, 0, $widthOutput, $heightFit, $widthInput, $heightInput);
        } else {
            imagecopyresampled($imageOutput, $image, $widthDiff/-2, 0, 0, 0, $widthFit, $heightOutput, $widthInput, $heightInput);
        }
        return $imageOutput;
    }
    
    // Orient image automatically
    public function orientImage($image, $fileName, $type) {
        if ($type=="jpg") {
            $exif = @exif_read_data($fileName);
            if ($exif && isset($exif["Orientation"])) {
                switch ($exif["Orientation"]) {
                    case 2: imageflip($image, IMG_FLIP_HORIZONTAL); break;
                    case 3: $image = imagerotate($image, 180, 0); break;
                    case 4: imageflip($image, IMG_FLIP_VERTICAL); break;
                    case 5: $image = imagerotate($image, 90, 0); imageflip($image, IMG_FLIP_VERTICAL); break;
                    case 6: $image = imagerotate($image, -90, 0); break;
                    case 7: $image = imagerotate($image, 90, 0); imageflip($image, IMG_FLIP_HORIZONTAL); break;
                    case 8: $image = imagerotate($image, 90, 0); break;
                }
            }
        }
        return $image;
    }
    
    // Return value according to unit
    public function convertValueAndUnit($text, $valueBase) {
        $value = $unit = "";
        if (preg_match("/([\d\.]+)(\S*)/", $text, $matches)) {
            $value = $matches[1];
            $unit = $matches[2];
            if ($unit=="%") $value = $valueBase * $value / 100;
        }
        return intval($value);
    }

    // Check if file needs to be updated
    public function isFileNotUpdated($fileNameInput, $fileNameOutput) {
        return $this->yellow->toolbox->getFileModified($fileNameInput)!=$this->yellow->toolbox->getFileModified($fileNameOutput);
    }
}
