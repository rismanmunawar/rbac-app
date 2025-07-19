<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AuditHelper
{
    public static function log(string $action, string $description = null, array $properties = [], $subject = null): void
    {
        $changes = [];

        if (isset($properties['before']) && isset($properties['after'])) {
            $before = $properties['before'];
            $after = $properties['after'];

            foreach ($after as $key => $value) {
                $old = $before[$key] ?? null;
                if ($old != $value) {
                    $changes[$key] = [
                        'before' => $old ?? '',
                        'after' => $value ?? '',
                    ];
                }
            }

            // tambahkan ke properties
            $properties['changes'] = $changes;
        }

        ActivityLog::create([
            'causer_id'    => Auth::id(),
            'action'       => $action,
            'description'  => $description,
            'properties'   => $properties,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->id,
        ]);
    }
}
