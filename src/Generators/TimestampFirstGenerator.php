<?php

namespace Mitchdav\StripeIds\Generators;

use InvalidArgumentException;

class TimestampFirstGenerator implements GeneratorInterface
{
    public const TIMESTAMP_BYTES = 6;

    /**
     * @param  int  $length
     * @return string
     * @throws \Exception
     *
     * @link https://github.com/ramsey/uuid
     */
    public function generate(int $length): string
    {
        if ($length < self::TIMESTAMP_BYTES || $length < 0) {
            throw new InvalidArgumentException(
                'Length must be a positive integer greater than or equal to '.self::TIMESTAMP_BYTES
            );
        }

        $time = str_pad(
            base_convert($this->timestamp(), 10, 16),
            self::TIMESTAMP_BYTES * 2,
            '0',
            STR_PAD_LEFT
        );

        $hash = '';

        if (self::TIMESTAMP_BYTES > 0 && $length > self::TIMESTAMP_BYTES) {
            $hash = random_bytes($length - self::TIMESTAMP_BYTES);
        }

        return (string) hex2bin(
            $time.str_pad(
                bin2hex((string) $hash),
                $length - self::TIMESTAMP_BYTES,
                '0'
            )
        );
    }

    /**
     * Returns current timestamp a string integer, precise to 0.00001 seconds
     *
     * @link https://github.com/ramsey/uuid
     */
    private function timestamp(): string
    {
        $time = explode(' ', microtime(false));

        return $time[1].substr($time[0], 2, 5);
    }
}