<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasUserTracking;


class Permission extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUserTracking;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'set_permission';
    protected $fillable = [
        'name',
        'slug',
        'header_menu',
        'child_menu',
        'created_by',
        'updated_by',
    ];

    // relationship
    // public function re_created_by(){
    //     return $this->belongsTo(User::class, 'created_by');
    // }

    // public function re_updated_by(){
    //     return $this->belongsTo(User::class, 'updated_by');
    // }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission');
    }

}
