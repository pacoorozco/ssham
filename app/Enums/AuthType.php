<?php

namespace App\Enums;

enum AuthType: string
{
    case Local = 'local';
    case External = 'external';
}
