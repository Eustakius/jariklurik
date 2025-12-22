<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;
use Firebase\JWT\ExpiredException;

class JWTService
{
    protected $config;

    public function __construct()
    {
        $this->config = new JWTConfig();
    }

    public function generateToken(array $payload): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->config->ttl;

        $payload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expire,
        ]);

        return JWT::encode($payload, $this->config->key, $this->config->alg);
    }

    public function verifyToken(string $token)
    {
        try {
            return JWT::decode($token, new Key($this->config->key, $this->config->alg));
        } catch (ExpiredException $e) {
            return 'expired';
        } catch (\Exception $e) {
            return null; // token tidak valid
        }
    }
}
