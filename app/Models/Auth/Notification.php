<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Notification extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'sys_notifications';
    protected $fillable = [
        'name',
        'haeder_menu',
        'child_menu',
        'created_by',
        'role_receive',
        'status',
    ];

    // relationship
    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

}
