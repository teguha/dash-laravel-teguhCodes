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
        $user = User::find($request->user_id);
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success'   => false,
                'message'   => 'Failed update data',
                'data'      => $user
            ]);
        }


        DB::beginTransaction();
        try {

            // // Debug user
            // if (!$user) {
            //     throw new Exception("USER TIDAK DITEMUKAN DARI AUTH");
            // }

            // // Debug request
            // if (!$request->name) {
            //     throw new Exception("REQUEST NAME KOSONG");
            // }

            $user->update([
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'date_birth'    => $request->date_birth
            ]);

            //  if (!$user) {
            //     throw new Exception("UPDATE RETURN FALSE");
            // }

            DB::commit(); 

            // $user->refresh(); // important!

            return response()->json([
                'success'   => true,
                'message'   => 'Success update data',
                'data'      => [
                    'name'  => ucwords($user->name),
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'date_birth' => format_date($user->date_birth, 'd F Y')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); 

            return response()->json([
                'success'   => false,
                'message'   => 'Failed update data'.$e->getMessage(),
                'data'      => ''
            ], 500);
        }
        
    }

    public function profile(){
        $user = Auth::user();
        $role = Role::find($user->role_id);
        return view('Auth.User.profile', compact(['user', 'role']));
    }

    // get data user detail
    public function user(Request $request){
        $user = Auth::user();
        return response()->json([  
            'success' => true,
            'name'    => ucwords($user->name),
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

        $user = User::find($request->user_id_password);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Password updated successfully'
        ]);
    }
}
