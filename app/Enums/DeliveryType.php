<?php
namespace App\Enums;

interface DeliveryType
{
    const SAMEDAY       = 1;
    const BYROAD       = 5;
    const BYAIR       = 6;
    const NEXTDAY       = 2;
    const SUBCITY       = 3;
    const OUTSIDECITY   = 4;
}
