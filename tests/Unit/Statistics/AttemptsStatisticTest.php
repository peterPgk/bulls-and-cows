<?php

namespace Tests\Unit\Statistics;

use App\Game\Statistics\AttemptsStatistic;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptsStatisticTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Class under test
     * @var AttemptsStatistic|null
     */
    protected $cuti = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cuti = AttemptsStatistic::make();
    }

    /** @test */
    public function general_method_returns_proper_number_of_non_distinct_results()
    {
        [$userOne, $userTwo] = User::factory()->count(2)->create();
        Game::factory()->count(2)->create(['user_id' => $userOne]);
        Game::factory()->count(2)->create(['user_id' => $userTwo]);

        $this->assertCount(2, $this->cuti->general(2));
        $this->assertCount(3, $this->cuti->general(3));
    }

    /** @test */
    public function general_method_returns_proper_number_of_distinct_results()
    {
        [$userOne, $userTwo] = User::factory()->count(2)->create();
        Game::factory()->count(2)->create(['user_id' => $userOne]);
        Game::factory()->count(2)->create(['user_id' => $userTwo]);

        $this->assertCount(2, $this->cuti->general(3, true));
        $this->assertCount(2, $this->cuti->general(4, true));
    }

    /** @test */
    public function general_method_returns_proper_results_when_is_not_distinct()
    {
        [$userOne, $userTwo] = User::factory()->count(2)->create();
        Game::factory()->count(2)
            ->state(new Sequence(
                ['attempts' => 2],
                ['attempts' => 1],
            ))
            ->create(['user_id' => $userOne]);

        Game::factory()->count(2)
            ->state(new Sequence(
                ['attempts' => 4],
                ['attempts' => 3],
            ))
            ->create(['user_id' => $userTwo]);

        $this->assertSame(
            [$userOne->email, $userOne->email, $userTwo->email, $userTwo->email],
            $this->cuti->general()->pluck('user.email')->toArray()
        );

        Game::byUser($userOne)->where('attempts', 1)->update(['attempts' => 6]);

        $this->assertSame(
            [$userOne->email, $userTwo->email, $userTwo->email, $userOne->email],
            $this->cuti->general()->pluck('user.email')->toArray()
        );
    }

    /** @test */
    public function general_method_returns_proper_results_when_distinct()
    {
        [$userOne, $userTwo] = User::factory()->count(2)->create();
        Game::factory()->count(2)
            ->state(new Sequence(
                ['attempts' => 2],
                ['attempts' => 1],
            ))
            ->create(['user_id' => $userOne]);

        Game::factory()->count(2)
            ->state(new Sequence(
                ['attempts' => 4],
                ['attempts' => 3],
            ))
            ->create(['user_id' => $userTwo]);

        $this->assertSame(
            [$userOne->email, $userTwo->email],
            $this->cuti->general(10, true)->pluck('user.email')->toArray()
        );

        $this->assertSame(
            [1, 3],
            $this->cuti->general(10, true)->pluck('attempts')->toArray()
        );

        Game::byUser($userOne)->where('attempts', 1)->update(['attempts' => 6]);

        $this->assertSame(
            [$userOne->email, $userTwo->email],
            $this->cuti->general(10, true)->pluck('user.email')->toArray()
        );
        $this->assertSame(
            [2, 3],
            $this->cuti->general(10, true)->pluck('attempts')->toArray()
        );

        Game::byUser($userOne)->where('attempts', 2)->update(['attempts' => 7]);

        $this->assertSame(
            [$userTwo->email, $userOne->email],
            $this->cuti->general(10, true)->pluck('user.email')->toArray()
        );
        $this->assertSame(
            [3, 6],
            $this->cuti->general(10, true)->pluck('attempts')->toArray()
        );
    }

    /** @test */
    public function forUser_method_returns_proper_number_of_results()
    {
        [$userOne, $userTwo] = User::factory()->count(2)->create();
        Game::factory()->count(3)
            ->state(new Sequence(
                ['attempts' => 2],
                ['attempts' => 1],
                ['attempts' => 5],
            ))
            ->create(['user_id' => $userOne]);

        Game::factory()->count(3)
            ->state(new Sequence(
                ['attempts' => 4],
                ['attempts' => 3],
                ['attempts' => 5],
            ))
            ->create(['user_id' => $userTwo]);

        $this->assertCount(2, $this->cuti->forUser($userOne, 2));
        $this->assertCount(3, $this->cuti->forUser($userOne, 3));
    }

    /** @test */
    public function forUser_method_returns_proper_results()
    {
        [$userOne, $userTwo] = User::factory()->count(2)->create();
        Game::factory()->count(3)
            ->state(new Sequence(
                ['attempts' => 2],
                ['attempts' => 1],
                ['attempts' => 5],
            ))
            ->create(['user_id' => $userOne]);

        Game::factory()->count(3)
            ->state(new Sequence(
                ['attempts' => 4],
                ['attempts' => 3],
                ['attempts' => 5],
            ))
            ->create(['user_id' => $userTwo]);

        $this->assertEquals([1, 2], $this->cuti->forUser($userOne, 2)->pluck('attempts')->toArray());
        $this->assertEquals([3, 4, 5], $this->cuti->forUser($userTwo, 3)->pluck('attempts')->toArray());
    }
}
