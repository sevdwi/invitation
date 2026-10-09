<?php
// FILE: app/Services/GoogleDriveAlbum.php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

// Membaca daftar foto dari folder Google Drive yang dibagikan "Siapa saja yang memiliki link".
// Fotonya tetap tersimpan di Drive; aplikasi hanya menampilkan thumbnail-nya.
class GoogleDriveAlbum
{
    // Pola link folder Drive yang diterima (dipakai juga oleh validasi form)
    public const URL_PATTERN = '~^https://drive\.google\.com/.*(?:folders/|[?&]id=)([\w-]{10,})~';

    private const API_URL       = 'https://www.googleapis.com/drive/v3/files';
    private const CACHE_MINUTES = 10;
    private const MAX_PAGES     = 5; // 5 × 200 = maksimal 1000 foto

    public static function folderId(?string $url): ?string
    {
        return preg_match(self::URL_PATTERN, (string) $url, $m) ? $m[1] : null;
    }

    public static function thumbnailUrl(string $fileId, int $width): string
    {
        return "https://drive.google.com/thumbnail?id={$fileId}&sz=w{$width}";
    }

    public static function embedUrl(string $folderId): string
    {
        return "https://drive.google.com/embeddedfolderview?id={$folderId}#grid";
    }

    /**
     * Daftar foto [['id' => ..., 'name' => ...], ...] di dalam folder.
     * Mengembalikan null bila daftar tidak bisa diambil (API key belum diisi, folder belum
     * dibagikan, atau Google tidak merespons) — pemanggil lalu memakai tampilan embed Drive.
     */
    public function photos(string $folderId): ?array
    {
        $key = config('services.google.drive_api_key');

        if (! $key) {
            return null;
        }

        $cacheKey = "drive-album:{$folderId}";

        if (is_array($cached = Cache::get($cacheKey))) {
            return $cached;
        }

        $photos    = [];
        $pageToken = null;

        try {
            for ($page = 0; $page < self::MAX_PAGES; $page++) {
                $response = Http::timeout(8)->get(self::API_URL, array_filter([
                    'key'       => $key,
                    'q'         => "'{$folderId}' in parents and mimeType contains 'image/' and trashed = false",
                    'fields'    => 'nextPageToken,files(id,name)',
                    'orderBy'   => 'name',
                    'pageSize'  => 200,
                    'pageToken' => $pageToken,
                    'supportsAllDrives'         => 'true',
                    'includeItemsFromAllDrives' => 'true',
                ]));

                if ($response->failed()) {
                    return null;
                }

                array_push($photos, ...$response->json('files', []));

                if (! $pageToken = $response->json('nextPageToken')) {
                    break;
                }
            }
        } catch (ConnectionException) {
            return null;
        }

        // Kegagalan tidak disimpan, supaya langsung pulih begitu folder dibagikan dengan benar
        Cache::put($cacheKey, $photos, now()->addMinutes(self::CACHE_MINUTES));

        return $photos;
    }
}
