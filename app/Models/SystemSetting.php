<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::remember("system_setting_{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (! $setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value, string $type = 'string', string $group = 'general', ?string $description = null): void
    {
        $stringValue = self::valueToString($value, $type);

        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );

        // Clear the cache
        Cache::forget("system_setting_{$key}");
    }

    /**
     * Check if registration is enabled
     */
    public static function isRegistrationEnabled(): bool
    {
        return (bool) self::get('registration_enabled', true);
    }

    /**
     * Toggle registration
     */
    public static function toggleRegistration(): bool
    {
        $currentValue = self::isRegistrationEnabled();
        $newValue = ! $currentValue;

        self::set('registration_enabled', $newValue, 'boolean', 'registration', 'Enable or disable public member registration');

        return $newValue;
    }

    /**
     * Check if processes are enabled
     */
    public static function areProcessesEnabled(): bool
    {
        return (bool) self::get('processes_enabled', true);
    }

    /**
     * Toggle processes
     */
    public static function toggleProcesses(): bool
    {
        $currentValue = self::areProcessesEnabled();
        $newValue = ! $currentValue;

        self::set('processes_enabled', $newValue, 'boolean', 'processes', 'Enable or disable system processes (claims, beneficiaries, etc.)');

        return $newValue;
    }

    /**
     * Get the active color theme key
     */
    public static function getColorTheme(): string
    {
        return (string) self::get('color_theme', config('themes.default', 'crimson'));
    }

    /**
     * Set the color theme
     */
    public static function setColorTheme(string $theme): void
    {
        $available = array_keys(config('themes.themes', []));
        if (! in_array($theme, $available)) {
            $theme = config('themes.default', 'crimson');
        }

        self::set('color_theme', $theme, 'string', 'appearance', 'System color theme');
    }

    /**
     * Cast value based on type
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Convert value to string for storage
     */
    protected static function valueToString(mixed $value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? 'true' : 'false',
            'json' => json_encode($value),
            default => (string) $value,
        };
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group): array
    {
        $settings = self::where('group', $group)->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = self::castValue($setting->value, $setting->type);
        }

        return $result;
    }
}
