<?php
namespace App\Enum;

use MyCLabs\Enum\Enum;


/**
 * @method static Gender MALE()
 * @method static Gender FEMALE()
 * @method static Gender OTHER()
 */
class Gender extends Enum
{
    private const MALE = 'male';
    private const FEMALE = 'female';
}
