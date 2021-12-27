<?php

declare(strict_types=1);

namespace Money;

use JsonSerializable;

use Money\Currencies\ISOCurrencies;
use function strtoupper;

/**
 * Currency Value Object.
 *
 * Holds Currency specific data.
 *
 * @psalm-immutable
 */
final class Currency implements JsonSerializable
{
    /**
     * Currency code.
     *
     * @psalm-var non-empty-string
     */
    private string $code;

    /** @psalm-param non-empty-string $code */
    public function __construct(string $code)
    {
        /** @psalm-var non-empty-string $this->code */
        $this->code = strtoupper($code);
    }

    /**
     * @param string $code
     *
     * @return static
     */
    public static function of(string $code): self
    {
        return new self($code);
    }

    /**
     * Returns the currency code.
     *
     * @psalm-return non-empty-string
     */
    public function getCurrencyCode(): string
    {
        return $this->code;
    }

    /**
     * Checks whether this currency is the same as an other.
     */
    public function is(Currency $other): bool
    {
        return $this->code === $other->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function jsonSerialize(): string
    {
        return $this->code;
    }

    public function getDefaultFractionDigits() : int
    {
        return (new ISOCurrencies())->subunitFor($this);
    }
}
