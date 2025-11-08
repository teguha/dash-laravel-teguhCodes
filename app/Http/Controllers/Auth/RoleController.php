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
        $query = User::query();

        // 🔍 Filter pencarian
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // 🧩 Filter tambahan (role, status, dsb)
        if ($role = $request->role) {
            $query->where('role', $role);
        }

        // ⏳ Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);

        $data = $query->orderBy('id', 'desc')
                      ->paginate($perPage, ['*'], 'page', $page);

        // 🔁 Return data JSON agar JS bisa render
        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ]
        ]);
    }


    public function create(){
        return view('Dashboard.auth.role.create');
    }


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
            return redirect()->back()
            ->withErrors(['name' => 'Color role cannot be empty.'])
            ->withInput();
        }

        $slug = Str::slug($request->name);
        $duplicateName = Role::where('slug', $slug)->first();

        if ($duplicateName) {
            return redirect()->back()
            ->withErrors(['name' => 'Role name has ready use.'])
            ->withInput();
        }

        Role::create([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color_theme,
        ]); 

        return redirect()->route('admin.setting.role.index')->with('success', 'Success add data.');

    }

    public function edit($id){
        $role = Role::find($id);
        return view('Dashboard.auth.role.edit', compact('role'));
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
