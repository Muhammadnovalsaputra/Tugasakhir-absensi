<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PhotoService
{
    private const JPEG_QUALITY = 60;
    private const DISK         = 'public';

    public function store(string $base64Image, int $userId): string
    {
        $imageData = $this->decodeBase64($base64Image);
        $filePath  = $this->buildFilePath($userId);

        $this->compressAndSave($imageData, $filePath);

        return $filePath;
    }

    private function decodeBase64(string $base64Image): string
    {
        [, $imageData] = explode(';base64,', $base64Image);

        return base64_decode($imageData);
    }

    private function buildFilePath(int $userId): string
    {
        $subFolder = now()->format('Y-m');

        return "absensi/{$subFolder}/" . uniqid() . "_{$userId}.jpg";
    }

    private function compressAndSave(string $imageData, string $filePath): void
    {
        $source   = imagecreatefromstring($imageData);
        $tempPath = tempnam(sys_get_temp_dir(), 'absensi') . '.jpg';

        imagejpeg($source, $tempPath, self::JPEG_QUALITY);
        imagedestroy($source);

        Storage::disk(self::DISK)->put($filePath, file_get_contents($tempPath));

        unlink($tempPath);
    }
}