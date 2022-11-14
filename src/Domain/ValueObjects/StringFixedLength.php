<?php

namespace App\Domain\ValueObjects;

class StringFixedLength
{
    private $value;

    public function __construct($value, string $message, int $minLength = 0, ?int $maxLength = null)
    {
        $this->assertValue($value, $message, $minLength, $maxLength);
        $this->value = $value;
    }

    private function assertValue($value, string $message, int $minLength, ?int $maxLength)
    {
        is_int($maxLength)
            ? $success = is_string($value) && (mb_strlen($value, 'UTF-8') >= $minLength) && (mb_strlen($value, 'UTF-8') <= $maxLength)
            : $success = is_string($value) && (mb_strlen($value, 'UTF-8') >= $minLength)
        ;

        if (!$success) {
            throw new \InvalidArgumentException($message);
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
