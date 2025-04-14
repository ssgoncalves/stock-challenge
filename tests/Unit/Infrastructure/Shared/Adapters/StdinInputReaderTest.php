<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Shared\Adapters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Stock\Infrastructure\Shared\Adapters\StdinInputReader;

class StdinInputReaderTest extends TestCase
{
    public function testShouldReadMultipleJsonLinesFromStream(): void
    {
        // Set
        $stream = $this->getFixtureStream('multiple-valid-lines.txt');
        $result = StdinInputReader::read($stream);

        // Assertions
        $this->assertCount(2, $result);
        $this->assertSame('buy', $result[0][0]['operation']);
        $this->assertSame('buy', $result[1][0]['operation']);
        $this->assertSame(100, $result[0][0]['quantity']);
        $this->assertSame(10000, $result[1][0]['quantity']);
    }

    public function testShouldReadJsonWithEmptyLinesFromStream(): void
    {
        // Set
        $stream = $this->getFixtureStream('with-empty-lines.txt');
        $result = StdinInputReader::read($stream);

        // Assertions
        $this->assertCount(3, $result);
        $this->assertSame('buy', $result[0][0]['operation']);
        $this->assertSame('buy', $result[1][0]['operation']);
        $this->assertSame('buy', $result[2][0]['operation']);
        $this->assertSame(100, $result[0][0]['quantity']);
        $this->assertSame(10000, $result[1][0]['quantity']);
        $this->assertSame(9000, $result[2][0]['quantity']);
    }

    public function testShouldReadInvalidJsonFromStream(): void
    {
        // Expectation
        $this->expectException(InvalidArgumentException::class);

        // Set
        $stream = $this->getFixtureStream('invalid-json.txt');
        StdinInputReader::read($stream);
    }

    private function getFixtureStream(string $filename): mixed
    {
        $path = __DIR__ . '/../../../../Fixtures/Stdin/' . $filename;
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, file_get_contents($path));
        rewind($stream);
        return $stream;
    }
}