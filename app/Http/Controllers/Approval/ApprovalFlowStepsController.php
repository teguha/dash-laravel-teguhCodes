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
use App\Models\ApprovalFlowApprover;
use App\Models\ApprovalFlowSteps;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Master\Structure;

class ApprovalFlowStepsController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($id)
    {
        return view('Approval.FlowSteps.index', compact('id')); // blade utama
    }

    // get data
    public function getData(Request $request, $id)
    {

         $query = ApprovalFlowSteps::where('approval_flow_id', $id);
        // $query ='';

        // Filter pencarian
        if ($search = $request->search) { // Menggunakan = untuk penugasan, pastikan ini adalah yang Anda inginkan
            $query->where(function ($q) use ($search) {
                // Pencarian untuk 'name' dan 'level' dengan 'direksi'
                $q->whereRaw('LOWER(approval_type) like ?', ['%' . strtolower($search) . '%']);
            });
        }

        if(isset($request->date_start) && isset($request->date_end)){
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
        }

        // Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);

        $sortBy = match($request->sort_by ?? '') {
            'step_order'          => 'step_order',
            'approval_type'       => 'approval_type',
            'min_approve'         => 'min_approve',
            default                => 'step_order',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'asc';
        $data = $query->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
    

        return response()->json([
            'data' =>$data->map(function ($item) {
                if($item->approval_by == 'role'){
                    $approver = Role::find($item->approver_value);
                }elseif($item->approval_by == 'structure'){
                    $approver = Structure::find($item->approver_value);
                }elseif($item->approval_by == 'user'){
                    $approver = User::find($item->approver_value);
                }else{
                    $approver = null;
                }

                return [
                    'id'                => $item->id,
                    'module'            => ucfirst($item->approval_flow->name),
                    'step_order'        => $item->step_order,
                    'approval_type'     => ucfirst($item->approval_type),
                    'min_approve'       => $item->min_approve,
                    'approval_by'       => ucfirst($item->approval_by),  // role , struture, user
                    'approver_value'    => $approver ? ucfirst($approver->name) : '-', // admin, direksi, teguh
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
            'step_order'        => 'required',
            'approval_type'     => 'required',
            'min_approve'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        $step_order  = strtolower($request->input('step_order'));
        $approval_type  = strtolower($request->input('approval_type'));
        $min_approve  = strtolower($request->input('min_approve'));
        $approval_flow_id = $request->input('approval_flow_id');


        $approval_flow_steps = ApprovalFlowSteps::create([
            'step_order'        => $step_order,
            'approval_type'     => $approval_type,
            'min_approve'       => $min_approve,
            'approval_flow_id'  => $approval_flow_id,
            // 'approval_by'       => $approval_by,
            // 'approver_value'    => $approval_value,
        ]); 

        return response()->json([
            'success' => true,
            'message' => 'Success add data'
        ]);

    }

    // edit data
    public function edit($id){
        $approval_flow_steps = ApprovalFlowSteps::find($id);
        return response()->json([
            'id'                => $approval_flow_steps->id,
            'approval_flow'     => $approval_flow_steps->approval_flow->name,
            'approval_type'     => $approval_flow_steps->approval_type,
            'min_approve'       => $approval_flow_steps->min_approve,
            'step_order'        => $approval_flow_steps->step_order,
            // 'approval_by'       => $approval_flow_steps->approval_by,
            // 'approver_value'    => $approval_flow_steps->approver_value,
        ]);
    }

    // update data
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'step_order'        => 'required',
            'approval_type'     => 'required',
            'min_approve'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        $step_order  = strtolower($request->input('step_order'));
        $approval_type  = strtolower($request->input('approval_type'));
        $min_approve  = strtolower($request->input('min_approve'));
        $approval_flow_id = $request->input('approval_flow_id');
        // $approval_by = $request->input('approval_by');

        // if($approval_by == 'user'){
        //     $approval_value = $request->input('approver_by_user');
        // }elseif($approval_by =='role'){
        //     $approval_value = $request->input('approver_by_role');
        // }else{
        //     $approval_value = $request->input('approver_by_structure');
        // }

        $approval_flow_steps = ApprovalFlowSteps::findOrFail($id);
        $approval_flow_steps->update([
            'step_order'    => $step_order,
            'approval_type' => $approval_type,
            'min_approve'   => $min_approve,
            'approval_flow_id' => $approval_flow_id,
            // 'approval_by' => $approval_by,
            // 'approver_value' => $approval_value,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success update data'
        ]);
    }

    // delete data
    public function destroy($id){
        $approval_flow_steps = ApprovalFlowSteps::findOrFail($id);
        $approval_flow_steps->delete();
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
