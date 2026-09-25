<?php
declare(strict_types=1);

$sourceDir = 'C:\\xampp\\htdocs\\parham-academy';
$desktopZip = 'C:\\Users\\sina\\Desktop\\parham-academy.zip';

if (file_exists($desktopZip)) {
    unlink($desktopZip);
}

$zip = new ZipArchive();
if ($zip->open($desktopZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($sourceDir) + 1);
            
            if (str_starts_with($relativePath, '.git') || $relativePath === 'parham-academy.zip' || str_starts_with($relativePath, 'tools')) {
                continue;
            }
            
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    echo "SUCCESS: ZIP created at " . $desktopZip;
} else {
    echo "ERROR: Could not create ZIP";
}
