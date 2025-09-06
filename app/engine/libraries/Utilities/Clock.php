<?php

namespace Engine\Libraries\Utilities;

class Clock {
    protected string $timeZone = 'Europe/Berlin';
    protected \DateTime $dateObject;

    protected function getDateObject(?string $timeZone = null) {
        if ($timeZone !== null) {
            $this->timeZone = $timeZone;
        }
        return $this->dateObject ??= new \DateTime('now', new \DateTimeZone($this->timeZone));
    }
    
    public function format(string $format): string {
        return $this->getDateObject()->format($format);
    }

    public function dateTime(): string {
        return $this->format('d-M-Y H:i:s');
    }

    public function date(): string {
        return $this->format('Y-m-d');
    }

    public function time(): string {
        return $this->format('H:i:s');
    }

    public function year(): string {
        return $this->format('Y');
    }

    public function month(): string {
        return $this->format('m');
    }

    public function day(): string {
        return $this->format('d');
    }

    public function hour(): string {
        return $this->format('H');
    }

    public function minute(): string {
        return $this->format('i');
    }

    public function second(): string {
        return $this->format('s');
    }

    public function unix(): int {
        return $this->getDateObject()->getTimestamp();
    }
}
