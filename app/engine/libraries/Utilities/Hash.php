<?php

namespace Engine\Libraries\Utilities;

class Hash {
    public function create(string $data, int $rounds = 10): string {
        if ($rounds < 4) $rounds = 4;
        if ($rounds > 31) $rounds = 31;
        return password_hash($data, PASSWORD_BCRYPT, ['cost' => $rounds]);
    }

    public function compare(string $data, string $hash): bool {
        return password_verify($data, $hash);
    }
}
