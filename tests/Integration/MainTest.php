<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{

    private const string FIXTURE_PATH = __DIR__ . '/../Fixtures/Integration/Main/';

    #[DataProvider('getFixtures')]
    public function testShouldExecuteMain(string $inputFile, string $expectedFile): void
    {
        // Set
        $mainScript = realpath(__DIR__ . '/../../src/main.php');
        $expectedOutput = file($expectedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Actions
        $output = shell_exec("php {$mainScript} < {$inputFile}");
        $result = explode(PHP_EOL, trim($output));

        // Assertions
        foreach ($expectedOutput as $i => $expectedLine) {
            $this->assertJsonStringEqualsJsonString($expectedLine, $result[$i]);
        }
    }

    public static function getFixtures(): array
    {
        $inputDir = self::FIXTURE_PATH . 'input/';
        $expectedDir = self::FIXTURE_PATH . 'expected/';

        $inputFiles = glob($inputDir . '*.txt');
        $cases = [];

        foreach ($inputFiles as $inputFile) {
            $baseName = basename($inputFile, '.txt');
            $expectedFile = $expectedDir . $baseName . '.txt';
            $cases[$baseName] = [$inputFile, $expectedFile];
        }

        return $cases;
    }
}