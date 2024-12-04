<?php

namespace Auth\Domain;

enum Role: string
{
    case ADMIN = 'ADMIN';
    case USER = 'USER';
}
