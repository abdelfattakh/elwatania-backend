<?php
namespace App\Settings;


use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    /**
     * Tax Pricing.
     * @var string
     */
    public string $tax_price;

    /**
     * About Text.
     * @var string
     */
    public string $about;

    /**
     * Return Policy Text.
     * @var string
     */
    public string $returnPolicy;

    /**
     * Return  phone text.
     * @var string
     */
    public string $phone;

    /**
     * Return  email text.
     * @var string
     */
    public string $email;

    /**
     * Grouping Setting.
     * @return string
     */
    public static function group(): string
    {
        return 'general';
    }
}
