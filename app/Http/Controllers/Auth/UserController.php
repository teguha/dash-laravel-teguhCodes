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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return view('Auth.User.index'); // blade utama
    }

    // get data
    public function getData(Request $request)
    {
        $query = User::with('re_role');

        // filter search
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        // Filter tambahan (role, status, dsb)
        if ($role = $request->role) {
            $query->where('role_id', $role);
        }

        if(isset($request->date_start) && isset($request->date_end)){
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        // Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);

        $sortBy = match($request->sort_by ?? '') {
            'name'      => 'name',
            'updated'   => 'updated_at',
            'role'      => 'role',
            default     => 'id',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'desc';

        // Jalankan query dengan orderBy, baru paginate
        if($sortBy == 'role'){
            // $data = $query->orderBy($item->re_role, $sortDir)
            //    ->paginate($perPage, ['*'], 'page', $page);

            //  $data = $query->whereHas('re_role', function ($q) use ($sortDir) {
            //     $q->orderBy('name', $sortDir);
            //  })


            $query->select('users.*')->leftJoin('set_role', 'users.role_id', '=', 'set_role.id')->orderBy('set_role.name', $sortDir)
            ->paginate($perPage, ['*'], 'page', $page);
        }else{
            $data = $query->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
        }

        return response()->json([
            'data' =>$data->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => ucwords($item->name),
                    'initial'       => get_initial($item->name),
                    'color'         => random_color(),
                    'email'         => $item->email,
                    'role'          => $item->re_role? $item->re_role->name : 'none',
                    'status'        => ucfirst($item->status),
                    'phone'         => $item->phone ?? '-',
                    'updated_at'    => datatable_user_time($item->re_updated_by ?? $item->re_created_by, $item->updated_at ?? $item->created_at),
                ];
            }),
            'pagination' => [
                'current_page'  => $data->currentPage(),
                'per_page'      => $data->perPage(),
                'last_page'     => $data->lastPage(),
                'total'         => $data->total(),
                'from'          => $data->firstItem(),
                'to'            => $data->lastItem(),
            ]
        ]);
    }

    // store data
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'phone'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to add data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Simpan user baru
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role_id'  => $request->role,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Success add data',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'success' => false,
                'message' => 'Failed add data'.$e->getMessage(),
            ], 500);
        }
    }

    // edit data
    public function edit($id){
        $user = User::find($id);
        return response()->json([
            'id'        => $user->id,
            'name'      => $user->name,
            'phone'     => $user->phone,
            'email'     => $user->email,
            'role_id'   => $user->role_id
        ]);
    }

    // update data
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'phone'     => 'required',
            'role_id'   => 'required'
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
                'role_id'  => $request->role,
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

    // function active && not active user
    public function activateUser(Request $request, $id){
        $user = User::find($id);
        if(isset($user)){
            $new_status = $request->status == 1 ? 'active' : 'inactive';
            $user->update(['status' => $new_status]);
            return response()->json([
                'status' => true,
                'message' => 'Success update data',
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed update data',
            ], 500);
        }
    }

    // delete data
    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Success delete data'
        ]);
    }

}
