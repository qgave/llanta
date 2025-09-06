<?php

namespace Engine\Libraries\Utilities;

use Engine\Core\HandleExceptions;

class Debug {
    public function enabled() {
        HandleExceptions::showErrors(true);
    }

    public function disabled() {
        HandleExceptions::showErrors(false);
    }
}