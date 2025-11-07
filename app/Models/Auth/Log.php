<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasUserTracking;
use Exception;

class Log extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUserTracking;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table= 'sys_log';
    protected $fillable = [
        'name',
        'haeder_menu',
        'child_menu',
        'created_by',
        'status',
    ];

    // relationship
    // public function re_created_by(){
    //     return $this->belongsTo(User::class, 'created_by');
    // }





    // try{
    //     $a = 1;

    //     if($a != 1){
    //         throw new Exception("error message");
    //     }
    // }catch(Exception $e){
    //     echo $e->getMessage();
    // }finally{
    //     echo "ini selalu jalan walau muncul error message";
    // }

}
