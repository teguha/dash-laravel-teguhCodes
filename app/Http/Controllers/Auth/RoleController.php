<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class RoleController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return view('Auth.Role.index'); // blade utama
    }

    public function getData(Request $request)
    {
        $query = Role::query();

        // 🔍 Filter pencarian
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        // Filter tambahan (role, status, dsb)
        if ($role = $request->role) {
            $query->where('name', $role);
        }

        if($date = $request->date){
            $query->whereDate('created_at', $date);
        }

        // Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);

        $sortBy = match($request->sort_by ?? '') {
            'name' => 'name',
            'updated' => 'updated_at',
            'permission' => 'permission',
            default => 'id',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'desc';

        // Jalankan query dengan orderBy, baru paginate
        if($sortBy == 'permission'){
            $data = $query->orderByRaw('JSON_LENGTH(permission) ' . $sortDir)->paginate($perPage, ['*'], 'page', $page);
        }else{
            $data = $query->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
        }

        return response()->json([
            'data' =>$data->map(function ($item) {
                $permissions = json_decode($item->permission ?? '[]', true);
                $count = is_array($permissions) ? count($permissions) : 0;

                return [
                    'id' => $item->id,
                    'name' => ucfirst($item->name),
                    'color' => $item->color,
                    'permission_count' => $count,
                    'updated_at' => datatable_user_time($item->re_updated_by ?? $item->re_created_by, $item->updated_at ?? $item->created_at),
                ];
            }),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'last_page' => $data->lastPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ]);
    }


    // public function create(){
    //     return view('Dashboard.auth.role.create');
    // }


    public function show($id){
        $role = Role::findOrFail($id);
       
        return response()->json([
            'id' => $role->id,
            'name' => $role->name,
            'permission' => $role->permission,
            'color' => $role->color,
        ]);
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required',   
            'color_theme' => 'required'
        ]);

        if(empty($request->color_theme)){
            return response()->json([
                'success' => false,
                'message' => 'Color cannot be empty'
            ], 422);
        }

        $slug = Str::slug($request->name);
        $duplicateName = Role::where('slug', $slug)->first();

        if ($duplicateName) {
            return response()->json([
                'success' => false,
                'message' => 'Role alredy exist '
            ], 422);
        }

        Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color_theme,
        ]); 

        return response()->json([
            'success' => true,
            'message' => 'Success add data'
        ]);

    }

    public function edit($id){
        $role = Role::find($id);
        return response()->json([
            'id' => $role->id,
            'name' => $role->name,
            'color' => $role->color,
        ]);
        // return view('Dashboard.auth.role.edit', compact('role'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',   
            'color_theme' => 'required'
        ]);

        if(empty($request->color_theme)){
            return redirect()->back()
            ->withErrors(['name' => 'Color role cannot be empty.'])
            ->withInput();
        }

        $slug = Str::slug($request->name);
        $duplicateName = Role::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->first();
        

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Role name has ready use.'])
            ->withInput();
        }

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color_theme,
        ]);

        return redirect()->route('admin.setting.role.index')->with('success', 'Success updated data.');
    }

    public function destroy($id){
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully.']);
    }

    public function assignPermission($id) {
        $permissions = Permission::all();
        $role = Role::findOrFail($id);
        $lastPermissions = json_decode($role->permission ?? '[]', true);
        return view('Dashboard.auth.role.permission-assign', compact('id','permissions', 'role', 'lastPermissions'));
    }

    public function storePermission(Request $request, $id) {
        $request->validate([
            'permissions' => 'nullable|array',
        ]);
        $role = Role::findOrFail($id);
        $role->update([
            'permission' => json_encode($request->input('permissions', [])),
        ]);

        return redirect()->route('admin.setting.role.index')->with('success', 'Success assign permission data.');
    }


}
