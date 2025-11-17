<?php 
namespace App\Traits;

use App\Models\Auth\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait ActivityLoggable
{
    public static function bootActivityLoggable()
    {
        static::created(function ($model) {
            self::recordActivity($model, 'create', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            self::recordActivity(
                $model, 
                'update', 
                $model->getOriginal(), 
                $model->getChanges()
            );
        });

        static::deleted(function ($model) {
            self::recordActivity($model, 'delete', $model->getOriginal(), null);
        });
    }

    protected static function recordActivity($model, $action, $before, $after)
    {
        ActivityLog::create([
            'user_id'  => Auth::id(),
            'action'   => $action,
            'model'    => get_class($model),
            'model_id' => $model->id,
            'before'   => $before,
            'after'    => $after
        ]);
    }
}
