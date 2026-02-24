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

class Approval extends Model
{
    use HasApiTokens, HasFactory, Notifiable, ActivityLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'approvals';
    protected $fillable = [
        'module',
        'reference_id', // id modulnya
        'approval_flow_id',
        'current_step_order', // step saat ini
        'status',
        'created_by',
    ];

    // relationship
    public function flow()
    {
        return $this->belongsTo(ApprovalFlow::class, 'approval_flow_id');
    }

    public function steps()
    {
        return $this->hasMany(ApprovalSteps::class);
    }

    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }



}
