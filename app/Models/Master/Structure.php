<?php

namespace App\Models\Master;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Auth\User;

class Structure extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'set_structure';
    protected $fillable = [
        'parent_id',
        'level',
        'slug',
        'name',
        'phone',
        'tax',
        'address',
        'created_by',
        'updated_by',
    ];

    // relationship
    public function re_user(){
        return $this->hasMany(User::class, 'role_id');
    }

    public function re_created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function re_parent(){
        return $this->belongsTo(Structure::class, 'parent_id');
    }


    public function parent()
    {
        return $this->belongsTo(Structure::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Structure::class, 'parent_id');
    }

    // Mendapatkan bagian dari sub_corporate
    public function bagian()
    {
        return $this->hasMany(Structure::class, 'parent_id')->where('level', 'bagian');
    }

    // Mendapatkan sub_bagian dari bagian
    public function sub_bagian()
    {
        return $this->hasMany(Structure::class, 'parent_id')->where('level', 'sub_bagian');
    }


}
