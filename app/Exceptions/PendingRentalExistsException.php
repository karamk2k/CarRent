<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;


class PendingRentalExistsException extends HttpException
{
    public function __construct()
    {
        parent::__construct(409,'You already have a pending rental.');
    }
}
