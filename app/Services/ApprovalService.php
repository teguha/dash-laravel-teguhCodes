<?php

namespace App\Services;

use App\Models\Approval;       
use App\Models\ApprovalFlow;
use App\Models\ApprovalFlowSteps;
use App\Models\ApprovalStepApprover;
use App\Models\ApprovalSteps;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Structure;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu\Perencanaan;

class ApprovalService {

    // hanya bisa jalan di class ini saja dan class yang mewarisi
    public static function start($module, $referenceId, $userId){
        $flow = ApprovalFlow::where('module', $module)->where('is_active', true)->first();
        
        $approval = Approval::create([
            'module'            => $module,
            'reference_id'      => $referenceId,
            'approval_flow_id'  => $flow->id,
            'created_by'        => $userId,
            'status'            => 'pending',
            'current_step_order' => 1,
        ]);

        self::generateSteps($approval);
        return $approval;
    } 


    public static function generateSteps($approval){

        // nyambung ke approval flow steps
        $steps = $approval->flow->steps()->orderBy('step_order', 'asc')->get();

        foreach($steps as $s){
            $approvalStep = ApprovalSteps::create([
                'approval_id' => $approval->id,
                'step_order' => $s->step_order,
                'approval_type' => $s->approval_type, // sequence, pararel
                'started_at' => now()
            ]);

            self::generateApprovers($approvalStep, $s);
        }
    }
                                                // approval step, approval flow step
    protected static function generateApprovers($approvalStep, $flowStep)
    {
        //generate yang melakukan approval
        foreach ($flowStep->approvers as $approver) {
            $users = self::resolveApproverUsers($approver);

            // foreach ($users as $user) {
                ApprovalStepApprover::create([
                    'approval_step_id' => $approvalStep->id,
                    'approver_type_id' => $users->id,
                    'approver_type' => $approver->approver_type,
                ]);
            // }
        }
    }

    // protected static function resolveApproverUsers($approver)
    // {
    //     return match ($approver->approver_type) {
    //         'role' => Role::find($approver->approver_value),
    //         'structure' => Structure::find($approver->approver_value),
    //         'user' => User::find($approver->approver_value),
    //     };
    // }


    protected static function resolveApproverUsers($approver)
{
    // return match ($approver->approver_type) {
    //     'role' => User::whereHas('role_id', function ($q) use ($approver) {
    //         $q->where('roles.id', $approver->approver_value);
    //     })->get(),

    //     'structure' => User::where('struct_id', $approver->approver_value)->get(),

    //     'user' => User::where('id', $approver->approver_value)->get(),

    //     default => collect(),
    // };

    if($approver->approver_type == 'role'){
        return $data = Role::find($approver->approver_value);
    }elseif($approver->approver_type == 'structure'){ // by position
        return $data = Structure::find($approver->approver_value);
    }else{  
        return $data = User::find($approver->approver_value);
    }
}



    // function approve
    public static function approve($approvalId, $userId, $note = null)
    {
        $approver = ApprovalStepApprover::where('approver_type_id', $userId)
            ->whereHas('step', function($q) use ($approvalId){ 
                $q->where('approval_id', $approvalId)->where('status','pending');
        })->first();

        $approver->update([
            'status' => 'approved',
            'approved_at' => now(),
            'user_id'   => Auth::User()->id,
            'note' => $note
        ]);


        $new_status = self::checkStepCompletion($approver->step);
        return $new_status;
    }

    // function rejected
    public static function reject($approvalId, $userId, $note)
    {
        $approver = ApprovalStepApprover::where('approver_type_id', $userId)
            ->whereHas('step', fn($q) => $q->where('approval_id', $approvalId))
            ->first();

        $approver->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'note' => $note
        ]);

        // approval step status pending
        $approver->step->update(['status' => 'rejected']);

        // approval status pending
        $approver->step->approval->update(['status' => 'rejected']);

        // perencanaan 
        
    }


    // penting untuk paralel
    protected static function checkStepCompletion($step)
    {
        if ($step->approval_type === 'sequence') {
            
            $new_status = self::finishStep($step);
            return $new_status;
        }

        // PARALLEL
        $approvedCount = $step->approvers()->where('status','approved')->count();
        // $minApprove = $step->approval->flow->steps->min_approve;
        $stepFlow = ApprovalFlowSteps::where('approval_flow_id', $step->approval->approval_flow_id)->where('step_order', $step->step_order)->first();

        // dd( $step->approval->approval_flow_id, $step->step_order);
        if ($approvedCount >= $stepFlow->min_approve) {
            $new_status = self::finishStep($step);
            return $new_status;
        }
    }

    protected static function finishStep($step)
    {
        $step->update([
            'status' => 'approved',
            'finished_at' => now()
        ]);

        $approval = $step->approval;
        $nextStep = $approval->steps()
            ->where('step_order', '>', $step->step_order)
            ->orderBy('step_order')
            ->first();

        if ($nextStep) {
            $approval->update(['current_step_order' => $nextStep->step_order]);
            return 'pending';
        } else {
            $approval->update(['status' => 'approved']);
            return 'approved';
            // update status modul (perencanaan, dll)
        }
    }


}