<?php

namespace App\Config;

class ModelRegistry
{
    /**
     * Daftar model yang diizinkan untuk table component
     * key = kode yang dipakai di frontend
     * value = [
     *    'model' => Model Class,
     *    'columns' => kolom yang default ditampilkan
     * ]
     */
    public static array $tables = [
        'user' => [
            'model' => \App\Models\UserModel::class,
            'columns' => [
                'name' => 'Nama',
                'email' => 'Email',
            ]
        ],
    ];
}
