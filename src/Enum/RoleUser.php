<?php
namespace App\Enum;

use MyCLabs\Enum\Enum;


/**
 * @method static RoleUser ADHERENT()
 * @method static RoleUser COACH()
 * @method static RoleUser ADMIN()
 */
class RoleUser extends Enum
{
    private const ADHERENT = 'Adherent';
    private const COACH = 'Coach';

    private const ADMIN= 'Admin';
}
