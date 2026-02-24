<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Master\Structure;
use App\Traits\ActivityLoggable;
use App\Traits\HasUserTracking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Auth\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUserTracking ,ActivityLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'users';
    protected $fillable = [
        'name',
        'username',
        'email',
        'date_birth',
        'phone',
        'password',
        'status',
        'role_id',
        'struct_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //relationship
    public function re_role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
        // return $this->belongsToMany(Role::class, 'role_id');
    }

    

    public function re_user(){
        return $this->hasMany(User::class);
    }

    public function re_structure(){
        return $this->belongsTo(Structure::class, 'struct_id');
    }


    // Di dalam model User atau model yang menerima notifikasi
    public function notifications()
    {
        return $this->morphMany('Illuminate\Notifications\DatabaseNotification', 'notifiable');
    }


    //$user = User::find(1); // Misalnya mendapatkan user dengan ID 1
    // $notifications = $user->notifications; // Mengambil semua notifikasi untuk user tersebut


}
