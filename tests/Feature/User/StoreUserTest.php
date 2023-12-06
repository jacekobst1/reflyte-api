<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use SanctumTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actAsAdmin();
    }

    public function testStoreUserSuccessfully()
    {
        // when
        $response = $this->postJson('/api/users', [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // then
        $response->assertSuccessful();
        $user = User::whereEmail('johndoe@gmail.com')->first();
        $roles = $user->roles;

        $this->assertNotNull($user);
        $this->assertCount(1, $roles);
        $this->assertEquals('user', $roles->first()->name);
    }
}
