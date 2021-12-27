<?php

declare(strict_types=1);

namespace Money\Currencies;

use ArrayIterator;
use Money\Currencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Traversable;

use function array_keys;
use function array_map;

/**
 * A list of custom currencies.
 */
final class CurrencyList implements Currencies
{
    /**
     * Map of currencies and their sub-units indexed by code.
     *
     * @psalm-var array<non-empty-string, int>
     */
    private array $currencies;

    /** @psalm-param array<non-empty-string, positive-int|0> $currencies */
    public function __construct(array $currencies)
    {
        $this->currencies = $currencies;
    }

    public function contains(Currency $currency): bool
    {
        return isset($this->currencies[$currency->getCurrencyCode()]);
    }

    public function subunitFor(Currency $currency): int
    {
        if (! $this->contains($currency)) {
            throw new UnknownCurrencyException('Cannot find currency ' . $currency->getCurrencyCode());
        }

        return $this->currencies[$currency->getCurrencyCode()];
    }

    /** {@inheritDoc} */
    public function getIterator(): Traversable
    {
        return new ArrayIterator(
            array_map(
                static function ($code) {
                    return new Currency($code);
                },
                array_keys($this->currencies)
            )
        );
    }
}
