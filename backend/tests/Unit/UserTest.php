<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{

    public function test_user_can_be_created()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_user_email_is_unique()
    {
        User::create([
            'name' => 'User One',
            'email' => 'unique@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'User Two',
            'email' => 'unique@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_user_password_is_hidden_in_array()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
    }

    public function test_user_has_correct_fillable_fields()
    {
        $user = new User();

        $this->assertEquals(
            ['name', 'email', 'password'],
            $user->getFillable()
        );
    }
}
