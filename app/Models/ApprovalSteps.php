<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Auth\User;
use App\Traits\HasUserTracking;
use App\Traits\ActivityLoggable;
use Illuminate\Database\Eloquent\Model;

class ApprovalSteps extends Model
{
    use HasApiTokens, HasFactory, Notifiable, ActivityLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'approval_steps';
    protected $fillable = [
        'approval_id',
        'step_order',
        'approval_type', // [sequence, pararel]
        'status', //[draf, pending, approve, rejected]
        'started_at',
        'finished_at',
    ];

    // relationship
    // public function re_approval_flow(){
    //     return $this->hasMany(ApprovalFlow::class, 'approval_flow_id');
    // }

    

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }

    public function approvers()
    {
        return $this->hasMany(ApprovalStepApprover::class, 'approval_step_id');
    }

    // 🔥 PENTING: relasi ke master step
    public function flowStep()
    {
        return $this->belongsTo(
            ApprovalFlowSteps::class,
            'step_order',
            'step_order'
        )->whereColumn(
            'approval_flow_id',
            'approvals.approval_flow_id'
        );
    }

    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }



}
