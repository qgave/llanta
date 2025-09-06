<?php

namespace Engine\Libraries\Utilities;

use OpenSSLCertificateSigningRequest;

class Encryption {

    protected string $encryptionKey = 'Yw3Gp2EKp1bN6Yf0xd4lT8wXqA9aEs7mb';
    protected string $hashKey = 'Kg4fGc9H2p9x8dAe6fQc7YnRtVq1ZtXy9s0s';

    public function principalKey(string $value): void {
        $this->encryptionKey = $value;
    }

    public function secondaryKey(string $value): void {
        $this->hashKey = $value;
    }

    public function aes128Encrypt(string $data): string|false {
        $iv = random_bytes((int) openssl_cipher_iv_length('aes-128-cbc'));
        $value = openssl_encrypt($data, 'aes-128-cbc', $this->encryptionKey, 1, $iv);
        return $iv . $value;
    }

    public function aes128Decrypt(string $data): string|false {
        $ivSize = openssl_cipher_iv_length('aes-128-cbc');
        return openssl_decrypt(substr($data, $ivSize), 'aes-128-cbc', $this->encryptionKey, 1, substr($data, 0, $ivSize));
    }

    public function hashSha512($data): string {
        return hash_hmac('sha512', $data, $this->hashKey, false);
    }

    protected function compareSha512(string $value, string $hashedValue): bool {
        $hash = hash_hmac('sha512', $value, $this->hashKey, false);
        $diff = 0;
        for ($i = 0; $i < 128; $i++) {
            $diff |= ord($hashedValue[$i]) ^ ord($hash[$i]);
        }
        return $diff === 0;
    }

    public function encrypt(string $data): string {
        $value = $this->aes128Encrypt($data);
        $value = bin2hex($value);
        return $this->hashSha512($value) . $value;
    }

    public function decrypt(string $data): string|false {
        if (length($data) <= 128) return false;
        $value = substr($data, 128);
        if (!$this->compareSha512($value, substr($data, 0, 128))) {
            return false;
        }
        $value = hex2bin($value);
        return $this->aes128Decrypt($value);
    }
}
