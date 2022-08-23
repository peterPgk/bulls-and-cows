<?php

namespace App\Game\Generators\Exceptions;


class UnsupportedDigitsLengthException extends \Exception
{

    public function __construct()
    {
        parent::__construct('It is not possible to generate number with unique numbers with provided length');
    }
}
