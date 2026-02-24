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

class ApprovalFlowSteps extends Model
{
    use HasApiTokens, HasFactory, Notifiable, ActivityLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'approval_flow_steps';
    protected $fillable = [
        'approval_flow_id',
        'step_order',
        'approval_type',
        'min_approve',
        // 'approval_by',
        // 'approver_value',
        // 'created_by',
        // 'updated_by'
    ];

    // relationship
   public function approvers()
    {
        // punya banyak anak
        return $this->hasMany(ApprovalFlowApprover::class, 'approval_flow_step_id');
    }

    public function approval_flow(){
        // punya 1 parent
        return $this->belongsTo(ApprovalFlow::class, 'approval_flow_id');
    }


    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }



}
