<?php

namespace Engine\Libraries\Routing;

class NextException extends \Exception {
    public $result;

    public function __construct($result = null) {
        $this->result = $result;
    }

    public function getResult() {
        return $this->result;
    }
}
