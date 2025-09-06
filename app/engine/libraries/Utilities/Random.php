<?php

namespace Engine\Libraries\Utilities;

class Random {
    protected string $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected string $alphanumeric = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public function number(int $minimum, int $maximum): int {
        return rand($minimum, $maximum);
    }

    public function alphabet(int $length = 1): string {
        return $this->pick($this->alphabet, $length);
    }

    public function alphanumeric(int $length = 1): string {
        return $this->pick($this->alphabet, $length);
    }

    public function pick(string $data, int $length = 1): string {
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $data[$this->number(0, length($data) - 1)];
        }
        return $string;
    }

    public function unique(): string {
        return md5(uniqid(mt_rand()));
    }
}
