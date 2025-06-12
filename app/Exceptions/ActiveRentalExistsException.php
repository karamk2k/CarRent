<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ActiveRentalExistsException extends HttpException
{
     public function __construct()
    {
        parent::__construct(409,'You already have a currently active rental.');
    }
}
