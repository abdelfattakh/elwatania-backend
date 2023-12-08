<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;

class WebController extends Controller
{
    public function __construct()
    {
        $this->guard = 'web';
        $this->broker = 'users';
        $this->authModel = User::class;
        $this->user = auth($this->guard)->user();
    }
}
