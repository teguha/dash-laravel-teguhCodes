<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasUserTracking;
use Illuminate\Testing\Fluent\Concerns\Has;

class ActivityLog extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUserTracking;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'activity_logs';
    protected $fillable = [
        'user_id', 'action', 'model', 'model_id', 'before', 'after'
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array'
    ];

    
}
