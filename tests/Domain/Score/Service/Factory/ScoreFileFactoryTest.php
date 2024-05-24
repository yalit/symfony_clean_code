<?php

namespace App\Tests\Domain\Score\Service\Factory;

use App\Domain\Score\Model\ScoreFile;
use App\Domain\Score\Service\Factory\ScoreFileFactory;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class ScoreFileFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $scoreFile = ScoreFileFactory::create(__DIR__ . '/../../../Shared/File/testFile.txt');

        $this->assertInstanceOf(ScoreFile::class, $scoreFile);
        self::assertEquals('testFile', $scoreFile->getName());
        self::assertEquals('text/plain', $scoreFile->getMimeType());
        self::assertEquals(__DIR__ . '/../../../Shared/File/testFile.txt', $scoreFile->getPath());
        self::assertEquals('txt', $scoreFile->getExtension());
    }

    public function testCreateFileNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File not found');
        ScoreFileFactory::create(__DIR__ . '/../../../Shared/File/notFound.txt');
    }
}
