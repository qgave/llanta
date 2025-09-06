<?php
namespace Engine\Libraries\Http;

class ResponseException extends \Exception {
    protected $response;

    public function __construct($response) {
        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }
}
