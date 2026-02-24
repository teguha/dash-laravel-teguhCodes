<?php

namespace App\Http\Controllers\Perencanaan;

use App\Models\ApprovalStepApprover;
use App\Models\ApprovalSteps;
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
use App\Models\Approval;
use App\Models\Master\Position;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Structur;
use App\Models\Master\Structure;
use App\Models\Menu\Perencanaan;
use App\Models\Menu\Perencanaan as ModelsPerencanaan;
use App\Services\ApprovalService;
use Carbon\Carbon;

class PerencanaanController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    private function generateNoSurat($structure){
        $limitDigits = 5;
        $unit = strtoupper($structure);
        $year = now()->format('y');
        $initial =  $unit.'/'.$year.'/'; //'RIAU26';

        $lastData = Perencanaan::where('no', 'LIKE', '%'.$initial.'%')
        ->orderByRaw("CAST(SUBSTRING_INDEX(no, '/', -1) AS UNSIGNED) DESC")
        ->first();
        if($lastData)
        {
            $exData = explode($initial, $lastData->no);

            $numberLength = strlen((int)$exData[1] + 1);
            $padLength = max(5, $numberLength);
            $newNumber = ((int) $exData[1] + 1);

            $code = $initial.str_pad($newNumber, $padLength, "0", STR_PAD_LEFT);

            return $code;
        }
        else
        {
            $code = $initial.'00001';
        }
        
        return $code;
    }

    // private function getValidApprover($reference_id){
    //     $approval = Approval::find($reference_id);
    //     $step = ApprovalSteps::where('approval_id', $approval->id)->where('status', 'pending')->first();
    //     if($step->approval_type == 'parallel'){
    //         $step->approver
    //     }else{

    //     }
    // }

     public function index()
    {
        // $validApprover = $this->getValidApprover();
        return view('Perencanaan.index'); // blade utama


    }

    // get data
    public function getData(Request $request)
    {
        $struct = Auth::User()->re_structure->structures->id;
        // dd($struct);
        $query = ModelsPerencanaan::query();

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
            'no'            => 'no',
            'date'          => 'date',  
            'updated'       => 'updated_at',
            default         => 'id',
        };

        // Tentukan arah sort, default 'desc'
        $sortDir = $request->sort_dir ?? 'desc';

        $data = $query->where('structure_id', $struct)->orderBy($sortBy, $sortDir)->paginate($perPage, ['*'], 'page', $page);
        

        return response()->json([
            'data' =>$data->map(function ($item) {

                if($item->status == 'draft' || $item->status == 'pending'){
                    $color = 'yellow';
                }else if($item->status == 'approved'){
                    $color = 'green';
                }else{
                    $color = 'red';
                }

                if($item->status =='draft' || $item->status == 'reject'){
                    $approve = false;
                }else{
                    $approval   = Approval::find($item->approval_id);
                    if($approval){

                        $approval_step  = ApprovalSteps::where('approval_id', $approval->id)->where('status', 'pending')->where('step_order', $approval->current_step_order)->first();
                        if($approval_step && $approval_step->approval_type == 'sequence'){

                            $approver       = ApprovalStepApprover::where('approval_step_id', $approval_step->id)->where('status', 'pending')->first();
        
                            if($approver && $approver->approver_type == 'role'){
                                $approve = Auth::User()->role_id == $approver->approver_type_id ? true : false;
                            }elseif($approver && $approver->approver_type == 'structure'){
                                $approve = Auth::User()->struct_id == $approver->approver_type_id ? true : false;
                            }elseif($approver && $approver->approver_type == 'user'){
                                $approve = Auth::User()->id == $approver->approver_type_id ? true : false;
                            }else{
                                $approve = false;
                            }
                        }elseif($approval_step && $approval_step->approval_type == 'parallel'){
                            $approvers       = ApprovalStepApprover::where('approval_step_id', $approval_step->id)->where('status', 'pending')->get();
                            
                            foreach($approvers as $ap){
                                if($ap && $ap->approver_type == 'role' && $ap->status=='pending'){
                                    $approve = Auth::User()->role_id == $ap->approver_type_id ? true : false;
                                }elseif($ap && $ap->approver_type == 'structure' && $ap->status=='pending'){
                                    $approve = Auth::User()->struct_id == $ap->approver_type_id ? true : false;
                                }elseif($ap && $ap->approver_type == 'user' && $ap->status=='pending'){
                                    $approve = Auth::User()->id == $ap->approver_type_id ? true : false;
                                }else{
                                    $approve = false;
                                }

                                if($approve == true){
                                    break;
                                }
                            }
                        }else{
                            $approve = false;
                        }
                    }else{
                        $approve = false;
                    }
                }

                return [
                    'id'                => $item->id,
                    'name'              => ucfirst($item->name),
                    'no'                => $item->no,
                    'date'              => Carbon::parse($item->date)->format('d M Y'),  
                    'color'             => $color,
                    'approve'           => $approve,
                    'module'            => ucfirst($item->module_name),
                    'status'            => $item->approval->status == 'approved' ? 'approved' : $item->status,
                    'structure'         => $item->structures->name,
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
        $description = $request->input('description');
        $position  = $request->input('position');
        $module  = $request->input('module');
       


        $pst = Position::findOrFail($position);
        if($pst == null){
            return response()->json([
                'success' => false,
                'message' => 'Your Position not found'
            ]);
        }
        $structure = Structure::findOrFail($pst->structure_id);
        $no = $this->generateNoSurat($structure->name);
        
        $perencanaan_data = Perencanaan::create([
            'name'          => $name,
            'no'            => $no,
            'module_name'   => $module,
            'description'   => $description,
            'structure_id'  => $structure->id,
            'date'          => now(),
            'created_by'    => auth()->user()->id,
            'updated_by'    => auth()->user()->id,
            'approval_id'   => null
        ]); 


        // dd($perencanaan_data->id);

        $approval = $this->approvalService->start($module, $perencanaan_data->id, auth()->user()->id);

        $perencanaan_data->update([
            'approval_id' => $approval->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success add data'
        ]);

    }

    // get submit data
    public function submitGet(Request $request, $id){
        $data = Perencanaan::find($id);
        return view('Perencanaan.sumbit', compact('data', 'id'));
    }

    // submit Store data
    public function submitStore(Request $request, $id){
        Validator::make($request->all(), [
            'name'      => 'required',
        ]);

        $name  = $request->input('name');
        $description = $request->input('description');
        $perencanaan = Perencanaan::findOrFail($id);
        
        if($request->input('status') == 'draft' || $request->input('status') == 'reject'){
            $perencanaan->update([
                'name'          => $name,
                'description'   => $description,
                'status' => 'pending'
            ]);

            $approval = Approval::where('reference_id', $perencanaan->id)->first();
            if($approval->status == 'rejected'){
                $approval->update(['status' => 'pending']);
                ApprovalSteps::where('approval_id', $approval->id)->update(['status' => 'pending']);
                
                $stepIds = ApprovalSteps::where('approval_id', $approval->id)
                    ->pluck('id');

                ApprovalStepApprover::whereIn('approval_step_id', $stepIds)
                    ->update([
                        'status' => 'pending',
                        'note'   => ''
                    ]);
            }

        }else{
            $approval = Approval::find($perencanaan->approval_id);
            $step     = ApprovalSteps::where('approval_id', $approval->id)->where('status', 'pending')->first();
            $approver = ApprovalStepApprover::where('approval_step_id', $step->id)->where('status', 'pending')->first();
            
            if($approver && $approver->approver_type == 'role'){
                $userId = Auth::User()->role_id;
            }elseif($approver && $approver->approver_type == 'structure'){
                $userId = Auth::User()->struct_id;
            }else{
                $userId = Auth::User()->id;
            }

            if ($request->has('submit_data')) {
                $new_status=  $this->approvalService->approve($perencanaan->approval_id, $userId, $request->input('note'));
                if($new_status == 'approved'){
                    $perencanaan->update(['status' => 'approved']);
                }
            }else{
                $this->approvalService->reject($perencanaan->approval_id, $userId, $request->input('note'));
                $perencanaan->update(['status' => 'reject']);
                $approval->update(['current_step_order' => 1]);
            }   
        }
        
        // dd('approve');

        return redirect()->route('admin.perencanaan.index');
    }

    // edit data
    public function edit($id){
        $perencanaan = Perencanaan::find($id);
        return response()->json([
            'id'    => $perencanaan->id,
            'name'  => $perencanaan->name,
            'no'    => $perencanaan->no,
            'date'  => Carbon::parse($perencanaan->date)->format('d M Y'),
            'description' => $perencanaan->description,
            'structure' => $perencanaan->structures->name,
            'structure_text' => $perencanaan->structures->name,
            'module_name' => $perencanaan->module_name,
            
        ]);
    }

    // update data
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
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
        $description = $request->input('description');
        // $structure  = $request->input('structure');
        $module  = $request->input('module');

        $perencanaan = Perencanaan::findOrFail($id);
        $perencanaan->update([
            'name'          => $name,
            'module_name'   => $module,
            'description'   => $description,
            // 'structure_id'  => $structure,
            // 'date'          => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success update data'
        ]);
    }

    // delete data
    public function destroy($id){
        $perencanaan = Perencanaan::findOrFail($id);
        $perencanaan->delete();
        return response()->json([
            'success' => true,
            'message' => 'Success delete data'
        ]);
    }

    // simple track
    public function track($id){
        $item = Perencanaan::find($id);

        $name_created = $item->re_created_by?->name ?? 'System';
        $time_created = optional($item->created_at)
            ->timezone(config('app.timezone'))
            ->locale('id')
            ->translatedFormat('d M Y H:i');

        $html = ' 
            <div class="relative">
                <div class="absolute left-6 top-0 bottom-[-40] w-0.5 bg-gray-200"></div>
                <div class="space-y-6">
                    
                <div class="relative flex gap-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center font-bold z-10">
                        <i class="fa fa-history text-white"></i>
                    </div>
                    <div class="flex-1 pb-8">
                        <h3 class="font-bold text-gray-900 mb-2"> Create '.e($item->name).'</h3>
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
                                <h3 class="font-bold text-gray-900 mb-2"> Update '.e($item->name).'</h3>
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

        if($item->status != 'draft'){
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
                                <h3 class="font-bold text-gray-900 mb-2"> Submit '.e($item->name).'</h3>
                                <p class="text-sm text-gray-600 mb-2">Submited by '.e($name_updated).'</p>
                                <p class="text-sm text-gray-500">'.e($time_updated).'</p>
                            </div>
                        </div>

                    </div>
                </div>
            ';

            $approval   = Approval::where('reference_id', $item->id)->first();
            $step       = ApprovalSteps::where('approval_id', $approval->id)->pluck('id');
            $approver   = ApprovalStepApprover::whereIn('approval_step_id', $step)->get();

            foreach($approver as $ap){
                if($ap->status != 'pending'){
                    $name_updated = User::find($ap->user_id);
                    $time_updated = optional($ap->updated_at)
                    ->timezone(config('app.timezone'))
                    ->locale('id')
                    ->translatedFormat('d M Y H:i');

                   $bgColor = $ap->status === 'approved' ? 'bg-blue-500' : 'bg-red-500';

                    $html .= "
                            <div class=\"relative flex gap-4\">
                                <div class=\"w-12 h-12 {$bgColor} rounded-full flex items-center justify-center text-white font-bold z-10\">
                                    <i class=\"fa fa-history text-white\"></i>
                                </div>
                                <div class=\"flex-1 pb-8\">
                                    <h3 class=\"font-bold text-gray-900 mb-2\">
                                        Do approval with status " . e($ap->status) . "
                                    </h3>
                                    <p class=\"text-sm text-gray-600 mb-2\">
                                        Update by " . e($name_updated->name) . "
                                    </p>
                                    <p class=\"text-sm text-gray-500\">
                                        " . e($time_updated) . "
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    ";

                }
            }
        }

        return response()->json([
            'data' => $html
        ]);
    }

}
