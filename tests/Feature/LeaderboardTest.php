<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Winner;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Alice',
            'age' => 25,
            'address' => '123 Street Name'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'name', 'age', 'address', 'points']);
    }

    /** @test */
    public function it_can_fetch_the_leaderboard()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/leaderboard');
        
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_update_user_points()
    {
        $user = User::factory()->create();

        $response = $this->patchJson("/api/users/{$user->id}/points", ['points' => 10]);

        $response->assertStatus(200)
                 ->assertJson(['points' => 10]);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User deleted']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_resets_all_user_scores()
    {
        User::factory()->count(3)->create(['points' => 50]);

        $response = $this->postJson('/api/reset-scores');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Scores reset']);

        $this->assertDatabaseHas('users', ['points' => 0]);
    }

    /** @test */
    public function it_groups_users_by_score_with_average_age()
    {
        User::factory()->create(['name' => 'Emma', 'points' => 25, 'age' => 18]);
        User::factory()->create(['name' => 'Noah', 'points' => 18, 'age' => 17]);

        $response = $this->getJson('/api/users-grouped');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '25' => ['names', 'average_age'],
                     '18' => ['names', 'average_age']
                 ]);
    }

    /** @test */
    public function it_records_a_winner_if_there_is_no_tie()
    {
        $user = User::factory()->create(['points' => 100]);
        $this->artisan('queue:work --once');

        $this->assertDatabaseHas('winners', ['user_id' => $user->id, 'points' => 100]);
    }

    /** @test */
    public function it_does_not_record_a_winner_if_there_is_a_tie()
    {
        User::factory()->create(['points' => 100]);
        User::factory()->create(['points' => 100]);

        $this->artisan('queue:work --once');

        $this->assertDatabaseCount('winners', 0);
    }
}
