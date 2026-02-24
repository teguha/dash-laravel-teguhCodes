<?php

namespace App\Http\Controllers\Approval;

use App\Models\Auth\Approval;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\ApprovalFlow;
use App\Models\ApprovalFlowSteps;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Master\Structure;

class ApprovalFlowController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return view('Approval.Flow.index'); // blade utama
    }

    // get data
    public function getData(Request $request)
    {
        // $query = ApprovalFlow::query();
         $query = ApprovalFlow::query();
        // $query ='';

        // Filter pencarian
        if ($search = $request->search) { // Menggunakan = untuk penugasan, pastikan ini adalah yang Anda inginkan
            $query->where(function ($q) use ($search) {
                // Pencarian untuk 'name' dan 'level' dengan 'direksi'
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($search) . '%']);
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
            'module'        => 'module',
            'updated'       => 'updated_at',
            default         => 'id',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'desc';
        $data = $query->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
    

        return response()->json([
            'data' =>$data->map(function ($item) {

                return [
                    'id'                => $item->id,
                    'name'              => ucfirst($item->name),
                    'module'            => ucfirst($item->module),
                    'is_active'         => $item->is_active,
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
            'name'       => 'required',
            'module'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        $name  = strtolower($request->input('name'));
        $module  = strtolower($request->input('module'));
        $duplicateName = ApprovalFlow::where('name', $name)->where('module', $module)->first();

        if ($duplicateName) {
            return response()->json([
                'success' => false,
                'message' => 'Approval flow alredy exist '
            ], 422);
        }

        ApprovalFlow::create([
            'name'          => $name,
            'module'        => $module,
            'description'   => $request->input('description'),
            'is_active'     => $request->input('is_active') == 'true' ? 1 : 0,
        ]); 

        return response()->json([
            'success' => true,
            'message' => 'Success add data'
        ]);

    }

    // edit data
    public function edit($id){
        $approval_flow = ApprovalFlow::find($id);
        return response()->json([
            'id'            => $approval_flow->id,
            'name'          => $approval_flow->name,
            'module'        => $approval_flow->module,
            'description'   => $approval_flow->description,
            'is_active'     => $approval_flow->is_active,
        ]);
    }

    // update data
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'module'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        $name  = strtolower($request->input('name'));
        $module  = strtolower($request->input('module'));
        $duplicateName = ApprovalFlow::where('name', $name)->where('module', $module)->where('id', '!=', $id)->first();
        

        if ($duplicateName) {
            return response()->json([
                'success' => false,
                'message' => 'Approval flow alredy exist'
            ], 422);
        }

        $approval_flow = ApprovalFlow::findOrFail($id);
        $approval_flow->update([
            'name'          => $name,
            'module'        => $module,
            'description'   => $request->input('description'),
            'is_active'     => $request->input('is_active') == 'true' ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success update data'
        ]);
    }

    // delete data
    public function destroy($id){
        $approval_flow = ApprovalFlow::findOrFail($id);
        $approval_flow->delete();
        return response()->json([
            'success' => true,
            'message' => 'Success delete data'
        ]);
    }

    // simple track
    // public function track($id){
    //     $item = Structure::find($id);

    //     $name_created = $item->re_created_by?->name ?? 'System';
    //     $time_created = optional($item->created_at)
    //         ->timezone(config('app.timezone'))
    //         ->locale('id')
    //         ->translatedFormat('d M Y H:i');

    //     $html = ' 
    //         <div class="relative">
    //             <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
    //             <div class="space-y-6">
                    
    //             <div class="relative flex gap-4">
    //                 <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center font-bold z-10">
    //                     <i class="fa fa-history text-white"></i>
    //                 </div>
    //                 <div class="flex-1 pb-8">
    //                     <h3 class="font-bold text-gray-900 mb-2"> Create Role '.e($item->name).'</h3>
    //                     <p class="text-sm text-gray-600 mb-2"> Create by '.e($name_created).'</p>
    //                     <p class="text-sm text-gray-500">'.e($time_created).'</p>
    //                 </div>
    //             </div>
    //     ';
    //     if($item->updated_at){
    //         $name_updated = $item->re_updated_by?->name ?? 'System';
    //         $time_updated = optional($item->updated_at)
    //         ->timezone(config('app.timezone'))
    //         ->locale('id')
    //         ->translatedFormat('d M Y H:i');

    //         $html .= '  
    //                     <div class="relative flex gap-4">
    //                         <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold z-10">
    //                             <i class="fa fa-history text-white"></i>
    //                         </div>
    //                         <div class="flex-1 pb-8">
    //                             <h3 class="font-bold text-gray-900 mb-2"> Update Role '.e($item->name).'</h3>
    //                             <p class="text-sm text-gray-600 mb-2">Update by '.e($name_updated).'</p>
    //                             <p class="text-sm text-gray-500">'.e($time_updated).'</p>
    //                         </div>
    //                     </div>

    //                 </div>
    //             </div>
    //         ';
    //     }else{
    //         $html += '
    //             </div>
    //         </div>
    //         ';
    //     }

    //     return response()->json([
    //         'data' => $html
    //     ]);
    // }
    


}
