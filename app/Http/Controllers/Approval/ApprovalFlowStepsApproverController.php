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
use App\Models\Master\Position;
use App\Models\Master\Structure;

class ApprovalFlowStepsApproverController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($id)
    {
        return view('Approval.FlowStepsApprover.index', compact('id')); // blade utama
    }

    // get data
    public function getData(Request $request, $id)
    {

        $query = ApprovalFlowApprover::where('approval_flow_step_id', $id);

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
            'approval_type'       => 'approval_type',
            default                => 'id',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'asc';
        $data = $query->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
    

        return response()->json([
            'data' =>$data->map(function ($item) {
                if($item->approver_type == 'role'){
                    $approver = Role::find($item->approver_value);
                }elseif($item->approver_type == 'structure'){
                    $approver = Position::find($item->approver_value);
                }elseif($item->approver_type == 'user'){
                    $approver = User::find($item->approver_value);
                }else{
                    $approver = null;
                }

                return [
                    'id'                => $item->id,
                    'module'            => ucfirst($item->approval_flow_step->approval_flow->name),
                    'step_order'        => $item->approval_flow_step->step_order,
                    'approver_type'     => ucfirst($item->approver_type),  // role , struture, user
                    'approver_value'    => $approver ? ($item->approver_type == 'structure' ? ucfirst($approver->level) . ' ' . ucfirst($approver->name) : ucfirst($approver->name)) : '-',
                    // admin, direksi, teguh
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
            'approval_type'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        
        $approval_flow_steps_id = $request->input('approval_flow_step_id');
        $approval_type = $request->input('approval_type');

        if($approval_type == 'user'){  
            $approval_value = $request->input('approver_by_user');
        }elseif($approval_type =='role'){
            $approval_value = $request->input('approver_by_role');
        }else{ //approve by position
            $approval_value = $request->input('approver_by_structure');
        }


        $approval_flow_steps = ApprovalFlowApprover::create([
            'approver_type'             => $approval_type,
            'approval_flow_step_id'    => $approval_flow_steps_id,
            'approver_value'            => $approval_value,
        ]); 

        return response()->json([
            'success' => true,
            'message' => 'Success add data'
        ]);

    }

    // edit data
    public function edit($id){
        $approval_approver = ApprovalFlowApprover::find($id);
        return response()->json([
            'id'                => $approval_approver->id,
            'module'            => $approval_approver->approval_flow_step->approval_flow->name,
            'step'              => $approval_approver->approval_flow_step->step_order,
            'approval_type'     => $approval_approver->approver_type,
            'approver_value'    => $approval_approver->approver_value,
        ]);
    }

    // update data
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [

            'approval_type'     => 'required',
            
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update data',
                'errors'  => $validator->errors(), // <-- ini kuncinya
                'fields'  => $validator->errors()->keys(), // opsional: hanya nama field yg error
            ], 422);
        }

        $approval_flow_steps_id = $request->input('approval_flow_step_id');
        $approval_type = $request->input('approval_type');

        if($approval_type == 'user'){
            $approval_value = $request->input('approver_by_user');
        }elseif($approval_type =='role'){
            $approval_value = $request->input('approver_by_role');
        }else{
            $approval_value = $request->input('approver_by_structure');
        }

        $approval_flow_approver = ApprovalFlowApprover::findOrFail($id);
        $approval_flow_approver->update([
            'approver_type'     => $approval_type,
            'approval_flow_step_id'  => $approval_flow_steps_id,
            'approver_value'    => $approval_value,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success update data'
        ]);
    }

    // delete data
    public function destroy($id){
        $approval_flow_approver = ApprovalFlowApprover::findOrFail($id);
        $approval_flow_approver->delete();
        return response()->json([
            'success' => true,
            'message' => 'Success delete data'
        ]);
    }

    // show tracking 
    // public function showTrack($id){

    // }

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
