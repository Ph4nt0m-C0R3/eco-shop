<?php

return [
    // Argon2id supports long UTF-8 passphrases without bcrypt's 72-byte boundary.
    'driver' => 'argon2id',
    'bcrypt' => ['rounds' => (int) env('BCRYPT_ROUNDS', 12), 'verify' => true, 'limit' => 72],
    'argon' => [
        'memory' => (int) env('ARGON_MEMORY', 65536),
        'threads' => (int) env('ARGON_THREADS', 1),
        'time' => (int) env('ARGON_TIME', 4),
        // Existing bcrypt hashes are verified, then upgraded on successful login.
        'verify' => false,
    ],
    'rehash_on_login' => true,
];
