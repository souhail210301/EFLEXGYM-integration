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
    private const ADHERENT = 'adherent';
    private const COACH = 'coach';

    private const ADMIN= 'admin';
}
