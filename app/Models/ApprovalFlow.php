<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Auth\User;
use App\Traits\HasUserTracking;
use App\Traits\ActivityLoggable;
use Illuminate\Database\Eloquent\Model;

class ApprovalFlow extends Model
{
    use HasFactory, ActivityLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'approval_flows';
    protected $fillable = [
        'module',
        'name',
        'description',
        'is_active',
    ];

    public function steps()
    {
        return $this->hasMany(ApprovalFlowSteps::class, 'approval_flow_id');
    }

    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }

}
