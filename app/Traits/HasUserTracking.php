<?php

namespace App\Traits;
use App\Models\Auth\User;

trait HasUserTracking{

    public function re_created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function re_updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}


