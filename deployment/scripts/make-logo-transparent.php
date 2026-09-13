<?php
declare(strict_types=1);

$files = [
    __DIR__ . '/../public/images/logo-header-icon.png',
    __DIR__ . '/../public/images/logo-footer-icon.png',
];

foreach ($files as $src) {
    if (!is_file($src)) {
        echo "Skip missing: {$src}\n";
        continue;
    }

    $img = imagecreatefrompng($src);
    if (!$img) {
        echo "Failed to load: {$src}\n";
        continue;
    }

    $w = imagesx($img);
    $h = imagesy($img);
    $out = imagecreatetruecolor($w, $h);
    imagesavealpha($out, true);
    imagealphablending($out, false);
    $clear = imagecolorallocatealpha($out, 0, 0, 0, 127);
    imagefill($out, 0, 0, $clear);

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgba = imagecolorat($img, $x, $y);
            $r = ($rgba >> 16) & 0xFF;
            $g = ($rgba >> 8) & 0xFF;
            $b = $rgba & 0xFF;
            $a = ($rgba & 0x7F000000) >> 24;

            $isBg = ($r <= 45 && $g <= 45 && $b <= 45) || ($r + $g + $b <= 60);

            if ($isBg) {
                imagesetpixel($out, $x, $y, $clear);
                continue;
            }

            $color = imagecolorallocatealpha($out, $r, $g, $b, $a);
            imagesetpixel($out, $x, $y, $color);
        }
    }

    imagepng($out, $src);
    imagedestroy($img);
    imagedestroy($out);
    echo "Transparent: {$src}\n";
}
