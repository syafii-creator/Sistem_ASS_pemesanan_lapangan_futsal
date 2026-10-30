<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Schema;

trait LogsActivity
{
    /**
     * Boot the trait to listen for Eloquent model lifecycle events
     * Mendukung soft delete dengan flag archived=1 saat hapus data dan log aktivitas otomatis
     */
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            try {
                $name = class_basename($model);
                ActivityLog::record(
                    action: strtolower($name) . '_created',
                    description: "Menambahkan {$name} baru: " . ($model->name ?? $model->title ?? $model->booking_code ?? "#{$model->id}"),
                    subject: $model,
                    properties: ['attributes' => $model->getAttributes()]
                );
            } catch (\Throwable $e) {}
        });

        static::updated(function ($model) {
            try {
                $name = class_basename($model);
                $changes = $model->getDirty();
                unset($changes['updated_at']);

                if (!empty($changes)) {
                    $old = array_intersect_key($model->getOriginal(), $changes);
                    ActivityLog::record(
                        action: strtolower($name) . '_updated',
                        description: "Memperbarui data {$name}: " . ($model->name ?? $model->title ?? $model->booking_code ?? "#{$model->id}"),
                        subject: $model,
                        properties: [
                            'old' => $old,
                            'new' => $changes,
                        ]
                    );
                }
            } catch (\Throwable $e) {}
        });

        static::deleting(function ($model) {
            // Sinkronisasi flag archived=1 saat hapus data
            try {
                if (Schema::hasColumn($model->getTable(), 'archived')) {
                    $model->archived = 1;
                }
            } catch (\Throwable $e) {}
        });

        static::deleted(function ($model) {
            $name = class_basename($model);
            $isSoft = method_exists($model, 'isForceDeleting') ? !$model->isForceDeleting() : false;

            // Pastikan nilai archived=1 tersimpan di database jika soft delete
            if ($isSoft) {
                try {
                    if (Schema::hasColumn($model->getTable(), 'archived')) {
                        \Illuminate\Support\Facades\DB::table($model->getTable())
                            ->where('id', $model->id)
                            ->update(['archived' => 1]);
                    }
                } catch (\Throwable $e) {}
            }

            try {
                ActivityLog::record(
                    action: strtolower($name) . ($isSoft ? '_soft_deleted' : '_deleted'),
                    description: ($isSoft ? "Soft delete (arsip) {$name}: " : "Hapus permanen {$name}: ") . ($model->name ?? $model->title ?? $model->booking_code ?? "#{$model->id}"),
                    subject: $model,
                    properties: [
                        'archived' => $isSoft ? 1 : 0,
                        'attributes' => $model->getAttributes()
                    ]
                );
            } catch (\Throwable $e) {}
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                $name = class_basename($model);

                // Sinkronisasi kembalikan flag archived=0 saat restore data
                try {
                    if (Schema::hasColumn($model->getTable(), 'archived')) {
                        \Illuminate\Support\Facades\DB::table($model->getTable())
                            ->where('id', $model->id)
                            ->update(['archived' => 0]);
                    }
                } catch (\Throwable $e) {}

                try {
                    ActivityLog::record(
                        action: strtolower($name) . '_restored',
                        description: "Memulihkan (Restore) {$name}: " . ($model->name ?? $model->title ?? $model->booking_code ?? "#{$model->id}"),
                        subject: $model,
                        properties: [
                            'archived' => 0,
                            'attributes' => $model->getAttributes()
                        ]
                    );
                } catch (\Throwable $e) {}
            });
        }
    }
}
