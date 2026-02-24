<?php

namespace App\Http\Controllers\Master\Structur;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Master\Structur;
use App\Models\Master\Structure;

class BagianController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     public function index()
    {
        return view('Struct.Bagian.index'); // blade utama
    }

    // get data
    public function getData(Request $request)
    {
        $query = Structure::query();

        // Filter pencarian
        if ($search = $request->search) { // Menggunakan = untuk penugasan, pastikan ini adalah yang Anda inginkan
            $query->where(function ($q) use ($search) {
                // Pencarian untuk 'name' dan 'level' dengan 'direksi'
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($search) . '%'])
                ->where('level', 'bagian');
            })
            ->orWhere(function ($qq) use ($search) {
                // Pencarian untuk 'name' dan 'level' dengan 'main_corp'
                $qq->whereRaw('LOWER(name) like ?', ['%' . strtolower($search) . '%'])
                ->where('level', 'direksi');
            });
        }

        if(isset($request->date_start) && isset($request->date_end)){
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        // Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);

        $sortBy = match($request->sort_by ?? '') {
            'name'          => 'name',
            'parent'        => 'parent_id',
            'updated'       => 'updated_at',
            default         => 'id',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'desc';

        if($sortBy == 'parent'){
            $data = $query->where('level', 'direksi')->orderBy('name', $sortDir)->paginate($perPage, ['*'], 'page', $page);
        }else{
            // Jalankan query dengan orderBy, baru paginate
            $data = $query->where('level', 'bagian')->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
        }

        return response()->json([
            'data' =>$data->map(function ($item) {

                return [
                    'id'                => $item->id,
                    'name'              => ucfirst($item->name),
                    'parent'            => $item->re_parent ? $item->re_parent->name :  '-' ,
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
        
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'parent'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        $name  = $request->input('name');
        $level = 'bagian';
        $parent = $request->parent;


        $slug = Str::slug(strtolower($name).' '.strtolower($level).' '.strtolower($parent));
        $duplicateName = Structure::where('slug', $slug)->first();

        if ($duplicateName) {
            return response()->json([
                'success' => false,
                'message' => 'Structure alredy exist '
            ], 422);
        }

        Structure::create([
            'name'      => $name,
            'slug'      => $slug,
            'parent_id' => $parent,
            'level'     => 'bagian',
            'phone'     => '',
            'tax'       => '',
            'address'   => '',
        ]); 

        return response()->json([
            'success' => true,
            'message' => 'Success add data'
        ]);

    }

    // edit data
    public function edit($id){
        $structure = Structure::find($id);
        return response()->json([
            'id'    => $structure->id,
            'name'  => $structure->name,
            'parent'  => $structure->parent_id,
        ]);
    }

    // update data
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'parent'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        $name  = $request->input('name');
        $level = 'bagian';
        $parent = $request->parent;   

        $slug = Str::slug(strtolower($name).' '.strtolower($level).' '.$parent);
        $duplicateName = Structure::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
        ->where('id', '!=', $id)
        ->first();
        

        if ($duplicateName) {
            return response()->json([
                'success' => false,
                'message' => 'Structure alredy exist'
            ], 422);
        }

        $structure = Structure::findOrFail($id);
        $structure->update([
            'name'  => $request->name,
            'slug'  => $slug,
            'parent_id' => $request->parent
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success update data'
        ]);
    }

    // delete data
    public function destroy($id){
        $structure = Structure::findOrFail($id);
        $structure->delete();
        return response()->json([
            'success' => true,
            'message' => 'Success delete data'
        ]);
    }

    // simple track
    public function track($id){
        $item = Structure::find($id);

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


}
