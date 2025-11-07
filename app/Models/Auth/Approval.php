<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasUserTracking;

use App\Models\Master\Structure;
class Approval extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUserTracking;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'set_approvals';
    protected $fillable = [
        'approval_by',
        'slug',
        'role_id',
        'struct_id',
        'sub_corp_id',
        'approval_type',
        'permission_menu_id',
        'approval postion',
        'created_by',
        'updated_by',
    ];

    // relationship

    public function re_role_by(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function re_struct_by(){
        return $this->belongsTo(Structure::class, 'struct_id');
    }

    public function re_corp_by(){
        return $this->belongsTo(Structure::class, 'sub_corp_id');
    }

    public function re_permission_by(){
        return $this->belongsTo(Permission::class, 'permission_menu_id');
    }

}
