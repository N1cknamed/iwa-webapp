<?php

namespace App\Types;

use App\Type\EnumType;

class CoordinatesEnumType extends EnumType
{
    public const ABOVE = 'COORDINATES_GREATER_THAN';
    public const BELOW = 'COORDINATES_LESS_THAN';
    public const BETWEEN = 'COORDINATES_BETWEEN';
    public const RADIUS = 'COORDINATES_RADIUS';
    
    protected static string $name = 'coordinates_enum';
}
