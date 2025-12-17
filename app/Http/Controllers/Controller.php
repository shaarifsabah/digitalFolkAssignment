<?php

namespace App\Http\Controllers;

use App\traits\ResponseTrait;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use ResponseTrait;
}
