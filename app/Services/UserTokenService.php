<?php
namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Throwable;

class UserTokenService
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function create(User $user): string
    {
        return JWT::encode(["user_id" => $user->id], $this->secret);
    }

    public function getUser(string $token): ?User
    {
        try {
            $decoded = JWT::decode($token, $this->secret, ["HS256"]);
            return User::find($decoded->user_id);
        } catch (Throwable $e) {
            return null;
        }
    }
}
