<?php

namespace Tests\Http\User;

use App\Models\User;
use Tests\TestCase;
use Faker;
use Laravel\Lumen\Testing\DatabaseMigrations;

class UserHttpTest extends TestCase
{
    use DatabaseMigrations;

    private const VALID_CPF = '48472338088';

    private Faker\Generator $faker;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker\Factory::create();

        $this->user = User::create([
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'cpf' => self::VALID_CPF
        ]);
    }

    public function testShouldCorrectlyReturnAllUsersThatAreNotDeleted(): void
    {
        $response = $this
            ->call(
                'GET',
                '/users'
            )
        ;

        $response->assertStatus(self::HTTP_SUCCESS_STATUS);
        $response->assertJson([
            [
                'uuid' => $this->user->uuid,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'cpf' => $this->user->cpf
            ]
        ]);
    }

    public function testShouldCorrectlyReturnUserById(): void
    {
        $response = $this->call('GET', '/user/' . $this->user->uuid);

        $response->assertStatus(self::HTTP_SUCCESS_STATUS);

        $response->assertJsonPath('original.result', 'success');
        $response->assertJsonPath('original.user.id', $this->user->uuid);
        $response->assertJsonPath('original.user.name', $this->user->name);
        $response->assertJsonPath('original.user.email', $this->user->email);
        $response->assertJsonPath('original.user.cpf', $this->user->cpf);
        $response->assertJsonPath('original.user.created_at', $this->user->created_at->format('Y-m-d H:i:s'));
        $this->assertIsBool($response->json('original.eligibility'));
    }

    public function testShouldCorrectlyDeleteUserById(): void
    {
        $response = $this->call('DELETE', '/user/' . $this->user->uuid);

        $response->assertStatus(self::HTTP_SUCCESS_STATUS);

        $response->assertJsonPath('result', 'success');
        $response->assertJsonPath('message', 'User deleted successfully');

        $deletedUser = User::find($this->user->uuid);
        $this->assertNull($deletedUser);
    }
}
