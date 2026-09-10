<?php

namespace GorillaSoft\Grimlock\Module\Payment\Stripe\Enum;

enum Payment: string
{
    case CARD = "card";
    case OXXO = "oxxo";
    case KLARNA = "klarna";


}
