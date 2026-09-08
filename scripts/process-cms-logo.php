<?php
declare(strict_types=1);

$src = dirname(__DIR__) . '/public/images/logo-cms-icon.png';
$out = dirname(__DIR__) . '/public/images/logo-cms-icon.png';

if (!is_file($src)) {
    fwrite(STDERR, "Source not found: {$src}\n");
    exit(1);
}

$bytes = file_get_contents($src, false, null, 0, 4);
$isJpeg = str_starts_with($bytes, "\xFF\xD8\xFF");
$isPng = str_starts_with($bytes, "\x89PNG");

if ($isJpeg) {
    $img = imagecreatefromjpeg($src);
} elseif ($isPng) {
    $img = imagecreatefrompng($src);
} else {
    fwrite(STDERR, "Unsupported image format\n");
    exit(1);
}

if (!$img) {
    fwrite(STDERR, "Failed to load image\n");
    exit(1);
}

$w = imagesx($img);
$h = imagesy($img);

$outImg = imagecreatetruecolor($w, $h);
imagealphablending($outImg, false);
imagesavealpha($outImg, true);

$trans = imagecolorallocatealpha($outImg, 0, 0, 0, 127);
imagefill($outImg, 0, 0, $trans);

$isBackground = static function (int $r, int $g, int $b): bool {
    if ($r > 232 && $g > 232 && $b > 232) {
        return true;
    }
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    return $max - $min < 22 && $min > 200;
};

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgba = imagecolorat($img, $x, $y);
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        if ($isBackground($r, $g, $b)) {
            continue;
        }

        $c = imagecolorallocatealpha($outImg, $r, $g, $b, 0);
        imagesetpixel($outImg, $x, $y, $c);
    }
}

imagepng($outImg, $out, 9);
imagedestroy($img);
imagedestroy($outImg);

echo "Saved transparent PNG: {$out}\n";
