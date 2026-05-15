<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false; // only has created_at, no updated_at

    protected $fillable = [
        'action',
        'description',
        'admin_user',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Helper: call this anywhere to log an admin action
    public static function log(string $action, string $description): void
    {
        static::create([
            'action'      => $action,
            'description' => $description,
            'admin_user'  => 'admin',
        ]);
    }
}