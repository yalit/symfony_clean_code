<?php

namespace App\Domain\Score\Service\Factory;

use App\Domain\Score\Model\ScoreFile;
use App\Domain\Shared\Service\Factory\UniqIDFactory;

class ScoreFileFactory
{
    public static function create(
        string $filePath,
    ): ScoreFile {

        if (!file_exists($filePath)) {
            throw new \Exception("File not found");
        }

        $name = pathinfo($filePath, PATHINFO_FILENAME);
        $mimeType = mime_content_type($filePath);
        $size = filesize($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return new ScoreFile(
            id: UniqIDFactory::create("file_"),
            name: $name,
            path: $filePath,
            mimeType: $mimeType,
            size: $size,
            extension: $extension,
        );
    }
}
