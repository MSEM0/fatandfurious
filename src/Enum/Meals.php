<?php

namespace App\Enum;

enum Meals: string
{
    public const BRK = 'breakfast';
    public const R_BRK = 'randomBreakfast';
    public const DNR = 'dinner';
    public const R_DNR = 'randomDinner';
    public const SPR = 'supper';
    public const R_SPR = 'randomSupper';

}