<?php

namespace App\Enums;

/**
 * Currency Enum
 *
 * Supported currencies with symbols and formatting
 *
 * @package App\Enums
 */
enum Currency: string
{
    case USD = 'usd';
    case AED = 'aed';
    case EURO = 'euro';
    case OTHERS = 'others';

    public function label(): string
    {
        return match($this) {
            self::USD => 'USD',
            self::AED => 'AED',
            self::EURO => 'EURO',
            self::OTHERS => 'Others',
        };
    }

    public function symbol(): string
    {
        return match($this) {
            self::USD => '$',
            self::AED => 'د.إ',
            self::EURO => '€',
            self::OTHERS => '',
        };
    }

    public function isoCode(): string
    {
        return match($this) {
            self::USD => 'USD',
            self::AED => 'AED',
            self::EURO => 'EUR',
            self::OTHERS => 'XXX',
        };
    }

    /**
     * Format amount with currency symbol
     */
    public function format(float $amount, bool $includeSymbol = true): string
    {
        $formatted = number_format($amount, 2, '.', ',');

        if (!$includeSymbol) {
            return $formatted;
        }

        return match($this) {
            self::USD => '$' . $formatted,
            self::AED => $formatted . ' د.إ',
            self::EURO => '€' . $formatted,
            self::OTHERS => $formatted,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(
            fn($currency) => [
                'value' => $currency->value,
                'label' => $currency->label(),
                'symbol' => $currency->symbol(),
                'iso_code' => $currency->isoCode(),
            ],
            self::cases()
        );
    }

    public static function default(): self
    {
        return self::USD;
    }
}
