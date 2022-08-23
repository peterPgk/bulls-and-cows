<?php

namespace Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_will_return_empty_data_if_user_is_not_logged_in()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();

        $this->assertArrayHasKey('game', $data);
        $this->assertCount(0, $data['game']);
    }

    /** @test */
    public function index_will_return_game_data_if_user_is_logged_in()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $responseData = $response->getOriginalContent()->getData();

        $this->assertArrayHasKey('game', $responseData);
        $this->assertArrayHasKey('user', $responseData['game']);
        $this->assertArrayHasKey('number', $responseData['game']);
        $this->assertArrayHasKey('mainStat', $responseData['game']);
        $this->assertArrayHasKey('userStat', $responseData['game']);
    }

    /** @test */
    public function start_will_require_email()
    {
        $this->markTestIncomplete();
        $response = $this->post(route('game.start'));

        $response->assertSessionHasErrors('email');

        $response->assertJsonValidationErrorFor('email');
    }

    /** @test */
    public function start_will_create_a_user_if_not_in_database()
    {
        $this->assertDatabaseMissing('users', ['email' => 'some@email.bg']);
        $response = $this->post(route('game.start'), ['email' => 'some@email.bg']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'some@email.bg']);
    }

    /** @test */
    public function start_will_take_the_user_if_is_in_database()
    {
        $user = User::factory()->create(['email' => 'some@email.bg']);

        $this->assertDatabaseCount('users', 1);

        $response = $this->post(route('game.start'), ['email' => 'some@email.bg']);
        $responseData = $response->getOriginalContent();

        $response->assertStatus(200);
        $this->assertDatabaseCount('users', 1);
        $this->assertEquals($user->email, $responseData['user']['email']);
    }

    /** @test */
    public function finish_will_store_users_attempts()
    {
        $user = User::factory()->create(['email' => 'some@email.bg']);
        Game::factory()->create(['user_id' => $user, 'attempts' => 2]);

        $this->assertDatabaseCount('games', 1);
        $this->assertDatabaseMissing('games', ['user_id' => $user, 'attempts' => 22]);

        $response = $this->actingAs($user)->post(route('game.finish'), ['attempts' => array_fill(1, 10, ['test'])]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('games', 2);
        $this->assertDatabaseHas('games', ['user_id' => $user->id, 'attempts' => 10]);
    }
}
