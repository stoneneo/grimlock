<?php

namespace GorillaSoft\Grimlock\Module\Payment\Stripe\Dto;

use GorillaSoft\Grimlock\Module\Payment\Stripe\Enum\Currency;

class Product
{
    /**
     * @param string $name
     * @param Currency $currency
     * @param int $price
     * @param int $quantity
     * @param array<string> $images
     */
    public function __construct(
        public string $name,
        public Currency $currency,
        public int $price,
        public int $quantity,
        public array $images = [],
    ) {
    }

}
