<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Shared\Adapters;

use InvalidArgumentException;

class StdinInputReader
{
    /**
     * @param null|resource $stream
     * @return array
     */
    public static function read($stream = null): array
    {
        $ownsStream = false;

        if ($stream === null) {
            $stream = fopen('php://stdin', 'r');
            $ownsStream = true;
        }

        $raw = stream_get_contents($stream);

        if ($ownsStream) {
            fclose($stream);
        }

        $lines = explode(PHP_EOL, $raw);
        $result = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $decoded = json_decode($line, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                throw new InvalidArgumentException("Invalid JSON on line " . ($index + 1));
            }

            $result[] = $decoded;
        }

        return $result;
    }
}