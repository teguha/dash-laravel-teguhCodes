<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\Permission;
// use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// use Symfony\Component\HttpFoundation\Request;

class AuthController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){

    }

    public function checkEmailLogin(Request $request){
        $user = User::where('email', $request->email)->first();
        if(!empty($user)){
            return response()->json([
                'success' => true,
                'message' => 'Email find'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Email false'
            ]);
        }
    }

    public function login(Request $request){
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'message' => 'Login success',
                'route'   => route('admin.setting.role.index')  
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password'
        ], 401);

    }

    public function show(){

    }

    // public function store(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'name'      => 'required|string|max:255',
    //         'email'     => 'required|email|unique:users',
    //         'phone'     => 'required',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json([
    //             'success'   => false,
    //             'message'   => 'Failed add data'
    //         ]);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Simpan user baru
    //         User::create([
    //             'name'     => $request->name,
    //             'email'    => $request->email,
    //             'phone'    => $request->phone,
    //             'password' => Hash::make($request->password),
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'status'    => true,
    //             'message'   => 'Success add data',
    //         ], 201);

    //     } catch (\Exception $e) {
    //         DB::rollBack(); 
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed add data',
    //         ], 500);
    //     }
    // }

    public function edit(){

    }

    public function profileUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'phone'     => 'required',
            'date_birth' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'   => false,
                'message'   => 'Failed update data'
            ]);
        }

        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'date_birth' => $request->date_birth

            ]);
            DB::commit(); 

            return response()->json([
                'success'   => true,
                'message'   => 'Success update data'
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); 

            return response()->json([
                'success' => false,
                'message' => 'Failed update data',
            ], 500);
        }
        
    }

    public function profile(){
        $user = Auth::user();
        $role = Role::find($user->role_id);
        return view('Auth.User.profile', compact(['user', 'role']));
    }

    public function user(Request $request){
        $user = Auth::user();
        return response()->json([  
            'success' => true,
            'name'    => $user->name,
            'email'   => $user->email,
            'birth'   => format_date($user->date_birth, 'd M Y'),
            'phone'   => $user->phone
        ]);
    
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'success'   => false,
                'message'   => 'Failed update data'
            ]);
        }

        $user = Auth::user();

        // if (!Hash::check($request->current_password, $user->password)) {
        //     return response()->json([
        //         'message' => 'Current password is incorrect'
        //     ], 403);
        // }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Password updated successfully'
        ]);
    }
}
