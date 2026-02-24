<?php

namespace App\Models\Master;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Auth\User;
use App\Traits\HasUserTracking;
use App\Traits\ActivityLoggable;
use Illuminate\Support\Str;

class Position extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, ActivityLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'set_position';
    protected $fillable = [
        'name',
        'level',
        'slug',
        'structure_id',
        'created_by',
        'updated_by',
    ];

    // relationship
    public function structures(){
        return $this->belongsTo(Structure::class, 'structure_id');
    }

    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }

   


}
