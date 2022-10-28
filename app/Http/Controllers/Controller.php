<?php

namespace App\Http\Controllers;

use App\Helpers\ErrorManage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends ErrorManage
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
