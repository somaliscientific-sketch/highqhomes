<?php
$in  = dirname(__DIR__) . '/public/images/logo-header-source.png';
$out = dirname(__DIR__) . '/public/images/logo-header-icon.png';

if (!is_file($in)) {
    fwrite(STDERR, "Source not found: $in\n");
    exit(1);
}

$img = imagecreatefrompng($in);
if (!$img) {
    fwrite(STDERR, "Failed to load source image\n");
    exit(1);
}

imagesavealpha($img, true);
$w = imagesx($img);
$h = imagesy($img);

$outImg = imagecreatetruecolor($w, $h);
imagealphablending($outImg, false);
imagesavealpha($outImg, true);

$trans = imagecolorallocatealpha($outImg, 0, 0, 0, 127);
imagefill($outImg, 0, 0, $trans);

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgba = imagecolorat($img, $x, $y);
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        if ($r < 35 && $g < 35 && $b < 35) {
            continue;
        }

        $a = ($rgba & 0x7F000000) >> 24;
        $c = imagecolorallocatealpha($outImg, $r, $g, $b, $a);
        imagesetpixel($outImg, $x, $y, $c);
    }
}

imagepng($outImg, $out, 9);
imagedestroy($img);
imagedestroy($outImg);

echo "Saved: $out\n";
