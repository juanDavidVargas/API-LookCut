<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\ConvertString;


class ApiController extends Controller
{
    use ApiResponser, ConvertString;

    public function __construct()
    {}
}