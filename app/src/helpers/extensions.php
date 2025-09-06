<?php

use Src\Libraries\Example;

kit()->store('example', Example::class);

function example() {
    return kit()->get();
}