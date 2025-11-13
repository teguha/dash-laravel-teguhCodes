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

    // get data
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
            $query->where('id', $role);
        }

        if(isset($request->date_start) && isset($request->date_end)){
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        // Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);

        $sortBy = match($request->sort_by ?? '') {
            'name'          => 'name',
            'updated'       => 'updated_at',
            'permission'    => 'permission',
            default         => 'id',
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
                    'id'                => $item->id,
                    'name'              => ucfirst($item->name),
                    'color'             => $item->color,
                    'permission_count'  => $count,
                    'updated_at'        => datatable_user_time($item->re_updated_by ?? $item->re_created_by, $item->updated_at ?? $item->created_at),
                ];
            }),
            'pagination' => [
                'current_page'          => $data->currentPage(),
                'per_page'              => $data->perPage(),
                'last_page'             => $data->lastPage(),
                'total'                 => $data->total(),
                'from'                  => $data->firstItem(),
                'to'                    => $data->lastItem(),
            ]
        ]);
    }

    // store data
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
            'name'  => $request->name,
            'slug'  => $slug,
            'color' => $request->color_theme,
        ]); 

        return response()->json([
            'success' => true,
            'message' => 'Success add data'
        ]);

    }

    // edit data
    public function edit($id){
        $role = Role::find($id);
        return response()->json([
            'id'    => $role->id,
            'name'  => $role->name,
            'color' => $role->color,
        ]);
    }

    // update data
    public function update(Request $request, $id){
        $request->validate([
            'name'          => 'required',   
            'color_theme'   => 'required'
        ]);

        if(empty($request->color_theme)){
            return response()->json([
                'success' => false,
                'message' => 'Color cannot be empty'
            ], 422);
        }

        $slug = Str::slug($request->name);
        $duplicateName = Role::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->first();
        

        if ($duplicateName) {
             return response()->json([
                'success' => false,
                'message' => 'Role alredy exist '
            ], 422);
        }

        $role = Role::findOrFail($id);
        $role->update([
            'name'  => $request->name,
            'slug'  => $slug,
            'color' => $request->color_theme,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success update data'
        ]);
    }

    // delete data
    public function destroy($id){
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Success delete data'
        ]);
    }

    // simple track
    public function track($id){
        $item = Role::find($id);

        $name_created = $item->re_created_by?->name ?? 'System';
        $time_created = optional($item->created_at)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

        $html = ' 
            <div class="relative">
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                <div class="space-y-6">
                    
                <div class="relative flex gap-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center font-bold z-10">
                        <i class="fa fa-history text-white"></i>
                    </div>
                    <div class="flex-1 pb-8">
                        <h3 class="font-bold text-gray-900 mb-2"> Create Role '.e($item->name).'</h3>
                        <p class="text-sm text-gray-600 mb-2"> Create by '.e($name_created).'</p>
                        <p class="text-sm text-gray-500">'.e($time_created).'</p>
                    </div>
                </div>
        ';
        if($item->updated_at){
            $name_updated = $item->re_updated_by?->name ?? 'System';
            $time_updated = optional($item->updated_at)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

            $html .= '  
                        <div class="relative flex gap-4">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                <i class="fa fa-history text-white"></i>
                            </div>
                            <div class="flex-1 pb-8">
                                <h3 class="font-bold text-gray-900 mb-2"> Update Role '.e($item->name).'</h3>
                                <p class="text-sm text-gray-600 mb-2">Update by '.e($name_updated).'</p>
                                <p class="text-sm text-gray-500">'.e($time_updated).'</p>
                            </div>
                        </div>

                    </div>
                </div>
            ';
        }else{
            $html += '
                </div>
            </div>
            ';
        }

        return response()->json([
            'data' => $html
        ]);
    }

    public function assignPermission($id) {
        $permissions        = Permission::all();
        $role               = Role::findOrFail($id);
        $lastPermissions    = json_decode($role->permission ?? '[]', true);
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
