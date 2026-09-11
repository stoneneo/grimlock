<?php

namespace GorillaSoft\Grimlock\Module\Payment\Stripe;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Module\Payment\Stripe\Dto\Product;
use GorillaSoft\Grimlock\Module\Payment\Stripe\Enum\Mode;
use GorillaSoft\Grimlock\Module\Payment\Stripe\Enum\Payment;

interface StripeClientInterface
{
    /**
     * @param Mode $mode
     * @param Collection<Payment> $payments
     * @param Collection<Product> $products
     * @param string $urlSuccess
     * @param string $urlCancel
     * @return string
     */
    public function obtainId(Mode $mode, Collection $payments, Collection $products, string $urlSuccess, string $urlCancel): string;

}
