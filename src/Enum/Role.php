<?php
namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static Gender MALE()
 * @method static Gender FEMALE()
 * @method static Gender OTHER()
 * @method static RoleUser ADHERENT()
 * @method static RoleUser COACH()
 * @method static RoleUser ADMIN()
 *
 */
class Role extends Enum
{
    private const ROlE_ADMIN = 'ROlE_ADMIN';
    private const ROlE_USER = 'ROLE_USER';




}
