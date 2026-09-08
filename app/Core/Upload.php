<?php
declare(strict_types=1);

class Upload
{
    private static array $imageTypes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
    ];

    private static array $videoTypes = [
        'video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo',
    ];

    private static array $documentTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
    ];

    public static function image(array $file, string $folder = 'general'): string
    {
        return self::store($file, $folder, self::$imageTypes);
    }

    public static function file(array $file, string $folder = 'media'): string
    {
        $allowed = array_merge(self::$imageTypes, self::$videoTypes, self::$documentTypes);
        return self::store($file, $folder, $allowed);
    }

    public static function mime(array $file): string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($file['tmp_name']) ?: 'application/octet-stream';
    }

    public static function categoryFromMime(string $mime): string
    {
        if (str_starts_with($mime, 'image/')) {
            return 'image';
        }
        if (str_starts_with($mime, 'video/')) {
            return 'video';
        }
        return 'document';
    }

    private static function store(array $file, string $folder, array $allowedTypes): string
    {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('No file uploaded or upload error: ' . ($file['error'] ?? 'unknown'));
        }

        if ($file['size'] > UPLOAD_MAX_SIZE) {
            throw new \RuntimeException('File size exceeds limit of ' . (UPLOAD_MAX_SIZE / 1048576) . 'MB');
        }

        $mime = self::mime($file);
        if (!in_array($mime, $allowedTypes, true)) {
            throw new \RuntimeException("Invalid file type: {$mime}");
        }

        $ext = self::mimeToExt($mime, $file['name'] ?? '');
        $filename = uniqid('file_', true) . '.' . $ext;
        $folder = Model::slugify($folder) ?: 'media';
        $dir = UPLOAD_DIR . '/' . $folder;

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $dest = $dir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new \RuntimeException('Failed to move uploaded file');
        }

        self::writeHtaccess();

        return 'uploads/' . $folder . '/' . $filename;
    }

    public static function delete(string $url): void
    {
        if (!$url) {
            return;
        }
        if (str_starts_with($url, 'http')) {
            $rel = str_replace(UPLOAD_URL, '', $url);
            $path = UPLOAD_DIR . $rel;
        } else {
            $path = ROOT_PATH . '/' . ltrim($url, '/');
        }
        if (file_exists($path)) {
            unlink($path);
        }
    }

    private static function writeHtaccess(): void
    {
        $htaccess = UPLOAD_DIR . '/.htaccess';
        if (file_exists($htaccess)) {
            return;
        }
        file_put_contents(
            $htaccess,
            "Options -Indexes\n" .
            "<Files ~ \"\.(php|php3|php4|php5|phtml|shtml|pl|py|rb|cgi|sh)$\">\n" .
            "  Order Deny,Allow\n  Deny from all\n</Files>\n"
        );
    }

    private static function mimeToExt(string $mime, string $originalName = ''): string
    {
        $ext = match ($mime) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png'               => 'png',
            'image/gif'               => 'gif',
            'image/webp'              => 'webp',
            'image/svg+xml'           => 'svg',
            'video/mp4'               => 'mp4',
            'video/webm'              => 'webm',
            'video/quicktime'         => 'mov',
            'video/x-msvideo'         => 'avi',
            'application/pdf'         => 'pdf',
            'application/msword'      => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'text/plain'              => 'txt',
            default                   => pathinfo($originalName, PATHINFO_EXTENSION) ?: 'bin',
        };
        return preg_replace('/[^a-z0-9]/i', '', $ext) ?: 'bin';
    }
}
