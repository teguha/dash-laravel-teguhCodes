<?php

namespace App\Models\Menu;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Auth\User;
use App\Models\Master\Structure;
use App\Models\Approval;   
use App\Traits\HasUserTracking; 
use App\Traits\ActivityLoggable;
use Illuminate\Database\Eloquent\Model;
use Psy\Util\Str;

class Perencanaan extends Model
{
    use HasApiTokens, HasFactory, Notifiable, ActivityLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'perencanaans';
    protected $fillable = [
        'name',
        'no', 
        'description',
        'date',
        'status',
        'note',
        'module_name',
        'structure_id',
        'approval_id',
        'created_by',
        'updated_by'
    ];

    // relationship
    public function approval()
    {
        return $this->belongsTo(Approval::class, 'approval_id');
    }

    public function structures()
    {
        return $this->belongsTo(Structure::class, 'structure_id');
    }

    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }



}
