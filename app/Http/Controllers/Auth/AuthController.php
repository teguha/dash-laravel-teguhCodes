<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class AuthController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){

    }

    public function login(){

    }

    public function show(){

    }

    public function store(){

    }

    public function edit(){

    }

    public function update(){

    }



}
