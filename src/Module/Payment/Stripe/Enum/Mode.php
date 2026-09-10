<?php

namespace GorillaSoft\Grimlock\Module\Payment\Stripe\Enum;

enum Mode: string
{
    case PAYMENT = "payment";
    case SUBSCRIPTION = "subscription";
    case SETUP = "setup";

}
