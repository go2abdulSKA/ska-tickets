<?php
// app/Enums/Currency.php

namespace App\Enums;

/**
 * Currency Enum
 *
 * Defines the supported currencies for tickets with symbols and formatting
 *
 * File Location: app/Enums/Currency.php
 *
 * Usage Example:
 * ```php
 * $ticket->currency = Currency::USD;
 * echo $ticket->currency->symbol(); // "$"
 * echo $ticket->currency->format(1500.50); // "$1,500.50"
 * ```
 */
enum Currency: string
{
    case USD = 'usd';
    case AED = 'aed';
    case EURO = 'euro';
    case OTHERS = 'others';

    /**
     * Get currency label for display in UI
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::USD => 'USD',
            self::AED => 'AED',
            self::EURO => 'EURO',
            self::OTHERS => 'Others',
        };
    }

    /**
     * Get full currency name
     *
     * @return string
     */
    public function fullName(): string
    {
        return match($this) {
            self::USD => 'US Dollar',
            self::AED => 'UAE Dirham',
            self::EURO => 'Euro',
            self::OTHERS => 'Other Currency',
        };
    }

    /**
     * Get currency symbol
     *
     * @return string
     */
    public function symbol(): string
    {
        return match($this) {
            self::USD => '$',
            self::AED => 'د.إ',
            self::EURO => '€',
            self::OTHERS => '',
        };
    }

    /**
     * Get ISO currency code
     *
     * @return string
     */
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
     * Get number of decimal places for this currency
     *
     * @return int
     */
    public function decimals(): int
    {
        return match($this) {
            self::USD => 2,
            self::AED => 2,
            self::EURO => 2,
            self::OTHERS => 2,
        };
    }

    /**
     * Format amount with currency symbol
     *
     * @param float $amount
     * @param bool $includeSymbol
     * @return string
     */
    public function format(float $amount, bool $includeSymbol = true): string
    {
        $formatted = number_format($amount, $this->decimals(), '.', ',');

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

    /**
     * Get badge color class for UI display
     *
     * @return string CSS class name
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::USD => 'bg-success',
            self::AED => 'bg-primary',
            self::EURO => 'bg-info',
            self::OTHERS => 'bg-secondary',
        };
    }

    /**
     * Get icon class for UI display
     *
     * @return string Material Design Icons class
     */
    public function iconClass(): string
    {
        return match($this) {
            self::USD => 'mdi mdi-currency-usd',
            self::AED => 'mdi mdi-currency-usd-circle', // AED uses similar icon
            self::EURO => 'mdi mdi-currency-eur',
            self::OTHERS => 'mdi mdi-cash',
        };
    }

    /**
     * Get all currency values as array
     * Useful for validation rules
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all currencies with labels for dropdown
     * Returns: [['value' => 'usd', 'label' => 'USD', 'symbol' => '$'], ...]
     *
     * @return array
     */
    public static function options(): array
    {
        return array_map(
            fn($currency) => [
                'value' => $currency->value,
                'label' => $currency->label(),
                'full_name' => $currency->fullName(),
                'symbol' => $currency->symbol(),
                'iso_code' => $currency->isoCode(),
                'badge_class' => $currency->badgeClass(),
                'icon_class' => $currency->iconClass(),
            ],
            self::cases()
        );
    }

    /**
     * Create enum instance from string value
     *
     * @param string|null $value
     * @return self|null
     */
    public static function fromValue(?string $value): ?self
    {
        if ($value === null) {
            return null;
        }

        return self::tryFrom($value);
    }

    /**
     * Get default currency (USD)
     *
     * @return self
     */
    public static function default(): self
    {
        return self::USD;
    }
}
