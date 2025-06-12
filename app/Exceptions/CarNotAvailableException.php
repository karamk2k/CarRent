<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;


class CarNotAvailableException extends HttpException
{
    //
    public function __construct()
    {
        parent::__construct(409,'This car is not available.');
    }
}
