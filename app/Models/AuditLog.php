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
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Log an admin/cashier action.
     *
     * @param string      $action
     * @param string      $description
     * @param int|null    $userId   The ID of the user who performed the action.
     */
    public static function log(string $action, string $description, ?int $userId = null): void
    {
        $adminUser = 'system';

        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                $adminUser = $user->username;
            }
        }

        static::create([
            'action'      => $action,
            'description' => $description,
            'admin_user'  => $adminUser,
            'user_id'     => $userId,
        ]);
    }
}