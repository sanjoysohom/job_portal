<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
#################################################################################
#		Class GD2 																#
#		Development By Mustafa Yontar											#
#		GPL Licanse																#
#		http://www.e4z.net  - ra@e4z.net										#
#################################################################################

class MyGD2 {

    var $bgcolor_red = 255;
    var $bgcolor_green = 255;
    var $bgcolor_blue = 255;
    var $quality = 100;

    function CheckPHPVersion() {
        if (version_compare("5.0.0", phpversion(), "<") == 1) {
            return true;
        } else {
            return false;
        }
    }

    function IfSupportedImageTypes($file) {

        if (is_file($file)) {
            $format = $this->GetFormat($file);

            switch ($format) {
                case "gif":
                    $image_type = true;
                    break;
                case "jpeg":
                    $image_type = true;
                    break;
                case "png":
                    $image_type = true;
                    break;
                default:
                    $image_type = false;
                    break;
            }
            return $image_type;
        } else {
            $this->CreateErrorImage("File not exist");
        }
    }

    function CreateImageFromX($file) {
        $extension = $this->GetFormat($file);
        switch ($extension) {
            case "gif":
                $image = imagecreatefromgif($file);
                break;

            case "jpg":
            case "jpeg":
                $image = imagecreatefromjpeg($file);
                break;

            case "png":
                $image = imagecreatefrompng($file);
                break;
        }

        return $image;
    }

    function GetFormat($file) {
        $size = getimagesize($file);
        $type = $size['mime'];
        switch ($type) {
            case "image/gif":
                $format = 'gif';
                break;
            case "image/jpeg":
                $format = 'jpeg';
                break;
            case "image/png":
                $format = 'png';
                break;
        }
        return $format;
    }

    function CreateImageX($file, $im, $saveas = '', $savefile = '') {


        if ($saveas == '') {
            $type = $this->GetFormat($file);
        } else {
            $type = $saveas;
        }
        switch ($type) {
            case "gif":

                if ($savefile == '') {
                    header('Content-type: image/gif');
                    imagegif($im);
                } else {
                    imagegif($im, $savefile);
                }
                break;

            case "jpg":
            case "jpeg":
                if ($savefile == '') {
                    header('Content-type: image/jpeg');
                    imagejpeg($im);
                } else {
                    imagejpeg($im, $savefile, $this->quality);
                }
                break;

            case "png":
                if ($savefile == '') {
                    header('Content-type: image/png');
                    imagepng($im);
                } else {
                    imagepng($im, $savefile);
                }
                break;
        }

        return true;
    }

    function getHeightWidth($filename) {
        list($width, $height) = getimagesize($filename);
        return array($width, $height);
    }

    function ResizeImage($filename, $new_width, $new_height, $x, $y, $size = 0) {
        list($width, $height) = getimagesize($filename);
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = $this->CreateImageFromX($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        if ($size == 0) {
            return $image_p;
        } else {

            $image2 = imagecreatetruecolor($size, $size);
            $bg = imagecolorallocate($image2, $this->bgcolor_red, $this->bgcolor_green, $this->bgcolor_blue);
            imagefill($image2, 0, 0, $bg);
            imagecopymerge($image2, $image_p, $x, $y, 0, 0, $size, $size, 100);
            imagefill($image2, $x, $y, $bg);
            return $image2;
        }
    }

    function CropXImage($filename, $width, $height, $x, $y) {

        $image = $this->CreateImageFromX($filename);

        $image2 = imagecreatetruecolor($width, $height);
        imagecopymerge($image2, $image, 0, 0, $x, $y, $width, $height, 100);

        return $image2;
    }

    function CropImage($file, $width, $height, $x, $y, $savefile = '') {


        if ($this->IfSupportedImageTypes($file)) {

            if ($savefile != '') {
                $this->CreateImageX($file, $this->CropXImage($file, $width, $height, $x, $y), '', $savefile);
            } else {
                $this->CreateImageX($file, $this->CropXImage($file, $width, $height, $x, $y));
            }
        } else {
            $this->CreateErrorImage("Unsupported Image File");
        }
    }

    function OneSizeThumbnail($file, $size, $savefile = '') {

        if ($this->IfSupportedImageTypes($file)) {
            list($width, $height) = getimagesize($file);


            if ($width > $height) {
                $size_percent = (int) ($size / ($width / 100));
                $new_height = (int) ($size_percent * ($height / 100));
                $new_width = $size;
                $y = ((int) $size - (int) $new_height) / 2;
                $x = 0;
            } else {
                $size_percent = (int) ($size / ($height / 100));
                $new_width = (int) ($size_percent * ($width / 100));
                $new_height = $size;
                $x = ($size - $new_width) / 2;
                $y = 0;
            }

            if ($savefile != '') {

                $this->CreateErrorImage("Unsupported Image File - " . $savefile);
                $this->CreateImageX($file, $this->ResizeImage($file, $new_width, $new_height, $x, $y, $size), '', $savefile);
            } else {
                $this->CreateImageX($file, $this->ResizeImage($file, $new_width, $new_height, $x, $y, $size), '', '');
            }
        } else {
            $this->CreateErrorImage("Unsupported Image File");
        }
    }

    function MaxSizeThumbnail($file, $size, $savefile = '') {

        if ($this->IfSupportedImageTypes($file)) {

            list($width, $height) = getimagesize($file);


            if ($width > $height) {
                $size_percent = (int) ($size / ($width / 100));
                $new_height = (int) ($size_percent * ($height / 100));
                $new_width = $size;
                $y = ($size - $new_height) / 2;
                $x = 0;
                //echo $x;
            } else {
                $size_percent = (int) ($size / ($height / 100));
                $new_width = (int) ($size_percent * ($width / 100));
                $new_height = $size;
                $x = ($size - $new_width) / 2;
                $y = 0;
            }

            if ($savefile != '') {

                $this->CreateImageX($file, $this->ResizeImage($file, $new_width, $new_height, $x, $y), '', $savefile);
            } else {
                $this->CreateImageX($file, $this->ResizeImage($file, $new_width, $new_height, $x, $y));
            }
        } else {
            $this->CreateErrorImage("Unsupported Image File");
        }
    }

    function EffectNegate($file, $savefile = '') {
        if ($this->CheckPHPVersion()) {
            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_NEGATE);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectGrayscale($file, $savefile = '') {
        if ($this->CheckPHPVersion()) {
            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_GRAYSCALE);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectEdgeDetect($file, $savefile = '') {
        if ($this->CheckPHPVersion()) {
            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_EDGEDETECT);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectSelectiveBlur($file, $savefile = '') {
        if ($this->CheckPHPVersion()) {

            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_SELECTIVE_BLUR);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectContrast($file, $val, $savefile = '') {
        if ($this->CheckPHPVersion()) {

            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_CONTRAST, $val);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectBrightness($file, $val, $savefile = '') {
        if ($this->CheckPHPVersion()) {
            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_BRIGHTNESS, $val);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectGusianBlur($file, $val, $savefile = '') {
        if ($this->CheckPHPVersion()) {
            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR, $val);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectSmooth($file, $val, $savefile = '') {
        if ($this->CheckPHPVersion()) {

            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_SMOOTH, $val);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectEmboss($file, $savefile = '') {
        if ($this->CheckPHPVersion()) {
            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_EMBOSS);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function EffectMeanRemoval($file, $savefile = '') {
        if ($this->CheckPHPVersion()) {

            if ($this->IfSupportedImageTypes($file)) {
                $img = $this->CreateImageFromX($file);
                imagefilter($img, IMG_FILTER_MEAN_REMOVAL);
                if ($savefile != '') {
                    $this->CreateImageX($file, $img, '', $savefile);
                } else {
                    $this->CreateImageX($file, $img, '', $savefile);
                }
            }
        } else {
            $this->CreateErrorImage("Please Install PHP 5");
        }
    }

    function ImageRotate($file, $degrees, $savefile = '') {

        if ($this->IfSupportedImageTypes($file)) {
            $img = $this->CreateImageFromX($file);
            $rotate = imagerotate($img, $degrees, 0);
            if ($savefile != '') {
                $this->CreateImageX($file, $rotate, '', $savefile);
            } else {
                $this->CreateImageX($file, $rotate, '', $savefile);
            }
        }
    }

    function SetTransparent($file, $hexcolor, $savefile = '') {
        if ($this->IfSupportedImageTypes($file)) {
            $rgb = $this->Hex2Rgb($hexcolor);
            $img = $this->CreateImageFromX($file);
            $trans = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
            imagecolortransparent($img, $trans);
            if ($savefile != '') {
                $this->CreateImageX($file, $img, "gif", $savefile);
            } else {
                $this->CreateImageX($file, $img, "gif", $savefile);
            }
        }
    }

    function Hex2Rgb($hex) {
        if (0 === strpos($hex, '#')) {
            $hex = substr($hex, 1);
        } else if (0 === strpos($hex, '&H')) {
            $hex = substr($hex, 2);
        } else if (0 === strpos($hex, 'x')) {
            $hex = substr($hex, 2);
        }


        $cutpoint = ceil(strlen($hex) / 2) - 1;
        $rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

        $rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
        $rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
        $rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

        return $rgb;
    }

    function CreateErrorImage($text) {
        $im = imagecreate(200, 30);


        $bg = imagecolorallocate($im, 255, 255, 255);
        $textcolor = imagecolorallocate($im, 255, 0, 0);


        imagestring($im, 2, 0, 0, $text, $textcolor);

        header("Content-type: image/png");
        imagepng($im);
        die();
    }

    function resize_image($source_img, $new_img, $new_width, $new_height) {
        $wh = $this->getHeightWidth($source_img);
        $passwidth = $wh[0] < $new_width ? $wh[0] : $new_width;
        $passheight = $wh[1] < $new_height ? $wh[1] : $new_height;

        if ($wh[0] > $wh[1]) {
            $size_percent = ($passwidth / ($wh[0] / 100));
            $new_height = ($size_percent * ($wh[1] / 100));
            if ($new_height > $passheight) {
                $passwidth = (($passwidth * $passheight) / $new_height);
            }
        } else {
            $size_percent = ($passheight / ($wh[1] / 100));
            $new_width = ($size_percent * ($wh[0] / 100));
            if ($new_width > $passwidth) {
                $passheight = (($passwidth * $passheight) / $new_width);
            }
        }

        if ($wh[0] > $wh[1]) {
            $maxhw = $passwidth;
        } else {
            $maxhw = $passheight;
        }

        $this->MaxSizeThumbnail($source_img, $maxhw, $new_img);
    }

}

#example
#resize_image($org_file3,PRODUCT_THUMB_PHISICAL_PATH . "T186_129-" . $_img_guid3 .".jpg",186,129);
##jit
//$gd = new GD2;
//$gd->ImageRotate("DSC_0096.jpg",-90);
//$gd->EffectMeanRemoval("DSC_0096.jpg");
//$gd->EffectGusianBlur("DSC_0096.jpg",20);
//$gd->EffectEmboss("DSC_0096.jpg");
//$gd->EffectSmooth("DSC_0096.jpg",20);
//$gd->EffectBrightness("DSC_0096.jpg",20);
//$gd->EffectContrast("DSC_0096.jpg",20);
//$gd->EffectSelectiveBlur("DSC_0096.jpg");
//$gd->EffectEdgeDetect("DSC_0096.jpg");
//$gd->EffectGrayscale("DSC_0096.jpg");
//$gd->EffectNegate("DSC_0096.jpg");
//$gd->OneSizeThumbnail("DSC_0096.jpg",250);
//$gd->CropImage("DSC_0096.jpg",250,250,100,70);
//$gd->SetTransparent("DSC_0096.jpg","#000000");
//$gd->CreateImageX("DSC_0096.jpg",$gd->ResizeImage("DSC_0096.jpg",200,50,0,0,$size=0));
?>
