<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
            'user_type' => $this->faker->randomElement(['admin', 'agent', 'tenant', 'owner', 'staff', 'client']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'admin',
            ];
        });
    }

    /**
     * Indicate that the user is an agent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function agent()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'agent',
            ];
        });
    }

    /**
     * Indicate that the user is a tenant.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function tenant()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'tenant',
            ];
        });
    }

    /**
     * Indicate that the user is an owner.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function owner()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'owner',
            ];
        });
    }

    /**
     * Indicate that the user is personnel.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function personnel()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'staff',
            ];
        });
    }

    /**
     * Indicate that the user is a client.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function client()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'client',
            ];
        });
    }

}