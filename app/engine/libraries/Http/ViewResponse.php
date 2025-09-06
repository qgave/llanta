<?php

namespace Engine\Libraries\Http;

use Engine\Libraries\Utilities\Render;

class ViewResponse extends BasicResponse {
    public function __construct(string $view, array $data = []) {
        $render = new Render($view, $data);
        parent::__construct($render->getContent());
    }
}
