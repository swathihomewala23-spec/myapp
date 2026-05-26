<?php

namespace App\Support;

class MediaPath
{
    public static function url(?string $path, ?string $fallbackDirectory = null): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        $path = trim($path);

        if (preg_match('/^(?:https?:)?\/\//i', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($normalizedPath, 'storage/public/')) {
            return asset($normalizedPath);
        }

        if (str_starts_with($normalizedPath, 'storage/')) {
            return asset($normalizedPath);
        }

        if (str_starts_with($normalizedPath, 'public/storage/public/')) {
            return asset(substr($normalizedPath, 7));
        }

        if (str_starts_with($normalizedPath, 'public/')) {
            return asset(substr($normalizedPath, 7));
        }

        $candidates = [
            'storage/' . $normalizedPath => public_path('storage/' . $normalizedPath),
            'storage/public/' . $normalizedPath => public_path('storage/public/' . $normalizedPath),
            $normalizedPath => public_path($normalizedPath),
        ];

        foreach ($candidates as $assetPath => $diskPath) {
            if (is_file($diskPath)) {
                return asset($assetPath);
            }
        }

        if ($fallbackDirectory && ! str_contains($normalizedPath, '/')) {
            return asset(trim($fallbackDirectory, '/') . '/' . $normalizedPath);
        }

        return asset('storage/public/' . $normalizedPath);
    }

    public static function imageFolders(): array
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg'];
        $roots = [
            public_path('storage/public'),
            storage_path('app/public'),
        ];

        $folders = [];
        $seenFiles = [];

        foreach ($roots as $root) {
            if (! is_dir($root)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                if (! $item->isFile()) {
                    continue;
                }

                $extension = strtolower($item->getExtension());
                if (! in_array($extension, $allowedExtensions, true)) {
                    continue;
                }

                $realPath = $item->getRealPath();
                if (! $realPath || isset($seenFiles[$realPath])) {
                    continue;
                }
                $seenFiles[$realPath] = true;

                $relativePath = str_replace('\\', '/', ltrim(substr($item->getPathname(), strlen($root)), DIRECTORY_SEPARATOR));
                if ($relativePath === '') {
                    continue;
                }

                $segments = explode('/', $relativePath);
                $folderSlug = count($segments) > 1 ? $segments[0] : 'root';
                $folderName = $folderSlug === 'root' ? 'Root' : ucwords(str_replace(['_', '-'], ' ', $folderSlug));

                $folders[$folderSlug] ??= [
                    'name' => $folderName,
                    'slug' => $folderSlug,
                    'images' => [],
                ];

                $folders[$folderSlug]['images'][] = [
                    'filename' => basename($relativePath),
                    'path' => $relativePath,
                    'url' => self::url($relativePath),
                    'size' => round($item->getSize() / 1024, 1) . ' KB',
                ];
            }
        }

        foreach ($folders as &$folder) {
            usort($folder['images'], fn (array $left, array $right) => strcmp($left['filename'], $right['filename']));
        }
        unset($folder);

        uasort($folders, fn (array $left, array $right) => strcmp($left['name'], $right['name']));

        return array_values($folders);
    }
}
