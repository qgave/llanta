<?php

namespace Engine\Libraries\Http;

interface ResponseInterface {
    public function getHeaders(): array;
    public function getStatusCode();
    public function sendContent();
}