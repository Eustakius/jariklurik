<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use Myth\Auth\Config\Auth;

class JWT extends BaseConfig
{
    public string $key;
    public string $alg = 'HS256';
    public int $ttl;

    public function __construct()
    {
        parent::__construct();

        $this->key = env('JWT_SECRET', 'rardianto');

        $auth = new Auth();

        $this->ttl = $auth->rememberLength ?? 3600;
    }
}
