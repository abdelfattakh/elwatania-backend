<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class ApiController extends Controller
{

    public function __construct()
    {
        $this->guard = 'users';
        $this->broker = 'users';
        $this->authModel = User::class;
        $this->user = auth($this->guard)->user();
    }

}
