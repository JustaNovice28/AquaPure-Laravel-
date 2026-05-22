<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    /**
     * Get a setting value by key.
     * If the key does not exist, return the given default.
     */
    public static function getValue(string $key, float $default = 0): float
    {
        $setting = static::where('key', $key)->first();
        return $setting ? (float) $setting->value : $default;
    }

    /**
     * Set a setting value by key (create or update).
     */
    public static function setValue(string $key, float $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}