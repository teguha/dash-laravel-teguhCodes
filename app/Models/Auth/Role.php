<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasUserTracking;
use Illuminate\Testing\Fluent\Concerns\Has;

class Role extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUserTracking;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'set_role';
    protected $fillable = [
        'name',
        'slug',
        'color',
        'permission',
        'created_by',
        'updated_by',
    ];

    // relationship
    public function re_user(){
        return $this->hasMany(User::class, 'role_id');
    }

    // public function re_created_by(){
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    // public function re_updated_by(){
    //     return $this->belongsTo(User::class, 'updated_by');
    // }

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'permission');
    // }

    
}
