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

class ApprovalStepApprover extends Model
{
    use HasApiTokens, HasFactory, Notifiable, ActivityLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'approval_step_approvers';
    protected $fillable = [
        'approval_step_id',
        'user_id',
        'approver_type',
        'approver_type_id',
        'status',
        'approved_at',
        'note',
    ];

    // relationship
    // public function re_approval_step(){
    //     return $this->hasMany(ApprovalFlow::class, 'approval_step_id');
    // }

    public function step()
    {
        return $this->belongsTo(ApprovalSteps::class, 'approval_step_id');
    }

    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }



}
