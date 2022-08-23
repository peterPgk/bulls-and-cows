<?php

namespace App\Game\Generators;


use App\Game\Generators\Contracts\NumbersGeneratorInterface;
use App\Game\Generators\Exceptions\UnsupportedDigitsLengthException;

class LimitedNumberGenerator implements NumbersGeneratorInterface
{

    private string $number = '';

    private string $pool = '';

    private array $sequence = [];

    private array $evenNumbers = [];

    private array $couples = [];

    public function __construct()
    {
        // @todo: accept as parameter (accept array|string)?
        $this->pool = '0123456789';

        // @todo: set these dynamically
        $this->evenNumbers = [4, 5];

        // @todo: generate this dynamically by getting only numbers (1 and 8)
        $this->couples = [
            1 => 8,
            8 => 1
        ];

    }

    /**
     * @param int $digits
     * @return string
     * @throws UnsupportedDigitsLengthException
     */
    public function generate(int $digits): string
    {
        $rr = 0;
        $this->reset();
        $this->validate($digits);

        while(($len = strlen($this->number)) < $digits) {
            $num = array_rand($this->sequence);

            if (in_array($num, $this->evenNumbers) && $len % 2 === 0) {
                continue;
            }

            if (array_key_exists($num, $this->couples)) {
                // This handles the case if the generator populates the last position in the string.
                // In this case we must remove the last number added to the string to be able to add this couple together.
                // We can check for this scenario with `if ($digits - $len === 1) {}`, but I don't like to put
                // one more nested if, so this be applied in any cases.
                // If we are in the 1 digit, then we will cast false to string
                $this->number = (string) substr($this->number, 0, -1);
                $this->attachNumber($this->couples[$num]);
            }

            $this->attachNumber($num);
        }

        return $this->number;
    }

    /**
     * @param $digits
     * @return void
     * @throws UnsupportedDigitsLengthException
     */
    private function validate($digits)
    {
        $rr = 0;
        if ($digits > $this->getMaxDigits() || $digits <= 1) {
            throw new UnsupportedDigitsLengthException();
        }
    }

    private function reset()
    {
        $this->sequence = str_split($this->pool);
        $this->number = '';
    }

    /**
     * There is some edge cases for long digit numbers;
     * If we want to generate 9 or 10 digits number, but in the 8 digit it is possible
     * only 4 and 5 to be left in the array = infinite loop. This is the reason to limit
     * max digits to 8
     */
    private function getMaxDigits(): int
    {
        return count($this->sequence) - count($this->evenNumbers);
    }

    private function attachNumber($number)
    {
        $this->number .= $number;

        unset($this->sequence[$number]);
    }
}
