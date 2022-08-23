<?php

namespace Tests\Unit\Generators;

use App\Game\Generators\Exceptions\UnsupportedDigitsLengthException;
use App\Game\Generators\LimitedNumberGenerator;
use PHPUnit\Framework\TestCase;

class LimitedNumberGeneratorTest extends TestCase
{
    /** @test */
    public function it_only_contains_numbers()
    {
        $generator = new LimitedNumberGenerator();

        $this->assertMatchesRegularExpression('/^[0-9]+$/', $generator->generate(8));
    }

    /** @test */
    public function it_generates_number_with_proper_length()
    {
        $generator = new LimitedNumberGenerator();

        $this->assertEquals(4, strlen($generator->generate(4)));
        $this->assertEquals(5, strlen($generator->generate(5)));
    }

    /** @test */
    public function it_throws_an_error_if_generated_number_is_more_than_10_digits()
    {
        $generator = new LimitedNumberGenerator();

        $this->expectException(UnsupportedDigitsLengthException::class);
        $generator->generate(10);
    }

    /** @test */
    public function it_throws_an_error_if_generated_number_is_less_than_1_digits()
    {
        $generator = new LimitedNumberGenerator();

        $this->expectException(UnsupportedDigitsLengthException::class);
        $generator->generate(1);
    }

    // It is hard (impossible?) to test for uniqueness. In our tests we run reasonable number of
    // tests that will give enough confidence that works

    /** @test */
    public function it_generates_number_with_unique_digits_in_each_position()
    {
        $generator = new LimitedNumberGenerator();

        foreach (range(1, 100) as $item) {
            $splitted = str_split($generator->generate(4));
            $this->assertEquals(4, count(array_flip($splitted)));
        }
    }

    /** @test */
    public function if_1_or_8_is_present_they_must_be_next_to_each_other()
    {
        // Since we still don't have a way to change the pool or some other way to affect
        // the work of the generator, again will use range type of test

        $generator = new LimitedNumberGenerator();

        foreach (range(1, 100) as $item) {
            $string = str_replace(['18', '81'], '', $generator->generate(4));

            $this->assertStringNotContainsString('1', $string);
            $this->assertStringNotContainsString('8', $string);
        }
    }

    /** @test */
    public function if_4_or_5_is_present_they_must_be_in_odd_position()
    {
        $generator = new LimitedNumberGenerator();
        $num = 0;

        while($num < 100) {
            $string = $generator->generate(4);

            if (str_contains($string, '4')) {
                $this->assertEquals(0, strpos('4', $string) % 2);
                $num++;
            }

            if (str_contains($string, '5')) {
                $this->assertEquals(0, strpos('5', $string) % 2);
                $num++;
            }
        }
    }
}
