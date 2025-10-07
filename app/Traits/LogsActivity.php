<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            ActivityLog::log(
                $model->getActivityAction('created'),
                $model->getActivityDescription('created'),
                $model
            );
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = $model->getOriginal();
            
            $oldValues = [];
            $newValues = [];
            
            foreach ($changes as $key => $value) {
                if (in_array($key, $model->getActivityLogAttributes())) {
                    $oldValues[$key] = $original[$key] ?? null;
                    $newValues[$key] = $value;
                }
            }
            
            if (!empty($newValues)) {
                ActivityLog::log(
                    $model->getActivityAction('updated'),
                    $model->getActivityDescription('updated'),
                    $model,
                    $oldValues,
                    $newValues
                );
            }
        });

        static::deleted(function ($model) {
            ActivityLog::log(
                $model->getActivityAction('deleted'),
                $model->getActivityDescription('deleted'),
                $model
            );
        });
    }

    protected function getActivityAction($event)
    {
        $modelName = strtolower(class_basename($this));
        return "{$modelName}_{$event}";
    }

    protected function getActivityDescription($event)
    {
        $modelName = class_basename($this);
        $eventName = ucfirst($event);
        
        if (method_exists($this, 'getActivityDescriptionFor')) {
            return $this->getActivityDescriptionFor($event);
        }
        
        return "{$modelName} {$eventName}";
    }

    protected function getActivityLogAttributes()
    {
        return $this->fillable ?? [];
    }

    // Manual logging methods
    public function logActivity($action, $description = null, $oldValues = null, $newValues = null)
    {
        return ActivityLog::log($action, $description, $this, $oldValues, $newValues);
    }
}
