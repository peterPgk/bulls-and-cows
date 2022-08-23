<?php

namespace App\Game\Generators\Contracts;


interface NumbersGeneratorInterface
{
    public function generate(int $digits): string;
}
