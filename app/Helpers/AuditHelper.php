<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AuditHelper
{
    public static function log($action, $description = '', $properties = [], $subject = null)
    {
        $log = new ActivityLog();
        $log->causer_id = Auth::id();
        $log->action = $action;
        $log->description = $description;
        $log->properties = $properties;

        if ($subject) {
            $log->subject_type = get_class($subject);
            $log->subject_id = $subject->id;
        }

        $log->save();
    }
}
