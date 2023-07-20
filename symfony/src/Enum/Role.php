<?php

namespace App\Enum;

enum Role: string
{
    case ROLE_TUTOR = 'Наставник';
    case ROLE_ADMIN = 'Администратор';
    case ROLE_USER = 'Пользователь';
    case ROLE_SUPER_ADMIN = 'Администратор сайта';
    
}