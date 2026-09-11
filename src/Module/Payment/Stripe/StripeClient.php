<?php

namespace GorillaSoft\Grimlock\Module\Payment\Stripe;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\Payment\Stripe\Enum\Mode;
use GorillaSoft\Grimlock\Module\Payment\Stripe\Enum\Payment;
use GorillaSoft\Grimlock\Module\Payment\Stripe\Dto\Product;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripeClient implements StripeClientInterface
{
    /**
     * @throws CoreException
     */
    public function __construct(string $apiKey)
    {
        if (empty($apiKey)) {
            throw new CoreException(self::class, 'Stripe API Key is empty.');
        }
        Stripe::setApiKey($apiKey);
    }

    /**
     * @param Mode $mode
     * @param Collection<Payment> $payments
     * @param Collection<Product> $products
     * @param string $urlSuccess
     * @param string $urlCancel
     * @return string
     * @throws ApiErrorException
     */
    public function obtainId(Mode $mode, Collection $payments, Collection $products, string $urlSuccess, string $urlCancel): string
    {
        $line_items = $products->map(fn ($product) => [
            'price_data' => [
                'currency' => $product->currency->value,
                'unit_amount_decimal' => (string) ($product->price * 100),
                'product_data' => [
                    'name' => $product->name,
                    'images' => $product->images,
                ],
            ],
            'quantity' => $product->quantity,
        ]);

        $payment_types = $payments->map(fn ($payment) => $payment->value);

        $checkout_session = Session::create([
            'payment_method_types' => $payment_types,
            'mode' => $mode->value,
            'success_url' => $urlSuccess,
            'cancel_url' => $urlCancel,
            'line_items' => $line_items,
        ]);

        return $checkout_session->id;
    }

}
