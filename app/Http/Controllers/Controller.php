<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $success_code =   200;
    protected $error_code = 400;
    protected $unauthorized_code=401;
    protected $check_user_error_code = 402;
    protected $validate_data_error_code=403;
    protected $not_confirmed_code=419;
    use AuthorizesRequests, ValidatesRequests;
}
