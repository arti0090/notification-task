<?php

namespace App\Enum;

enum NotificationTransition: string
{
    case SEND = 'send';

    case FAIL = 'fail';
}
