<?php

/**
 * Centralized Color Theme Configuration
 *
 * Each theme follows the 3-color rule:
 *   1. Primary  – main brand color (buttons, headers, badges, sidebar)
 *   2. Accent   – secondary brand color (highlights, links, secondary actions)
 *   3. Neutral  – body background, cards, borders, text hierarchy (slate)
 *
 * Semantic colors (success/warning/danger) stay consistent across themes.
 */

return [

    // Default theme key (can be overridden by system_settings DB)
    'default' => 'crimson',

    'themes' => [

        'crimson' => [
            'name' => 'Crimson & Navy',
            'icon' => 'school',
            'colors' => [
                // Primary palette
                'primary-50' => '#fef2f2',
                'primary-100' => '#fee2e2',
                'primary-200' => '#fdbbbb',
                'primary-300' => '#f98a8a',
                'primary-400' => '#e84d4d',
                'primary-500' => '#C1121F',
                'primary-600' => '#a50f1a',
                'primary-700' => '#780000',
                'primary-800' => '#5c0000',
                'primary-900' => '#450000',
                'primary-950' => '#2d0000',

                // Accent palette
                'accent-50' => '#f0f7fc',
                'accent-100' => '#daeaf5',
                'accent-200' => '#b8d7ec',
                'accent-300' => '#8ebfdb',
                'accent-400' => '#669BBC',
                'accent-500' => '#4a83a8',
                'accent-600' => '#336d92',
                'accent-700' => '#1a5278',
                'accent-800' => '#003049',
                'accent-900' => '#00253a',
                'accent-950' => '#001825',
            ],
            // Sidebar gradient stops (top → bottom)
            'sidebar' => ['#2d0000', '#450000', '#780000'],
            // Selection text background
            'selection' => 'rgba(120, 0, 0, 0.85)',
        ],

        'ocean' => [
            'name' => 'Ocean Blue',
            'icon' => 'waves',
            'colors' => [
                'primary-50' => '#eff6ff',
                'primary-100' => '#dbeafe',
                'primary-200' => '#bfdbfe',
                'primary-300' => '#93c5fd',
                'primary-400' => '#60a5fa',
                'primary-500' => '#3b82f6',
                'primary-600' => '#2563eb',
                'primary-700' => '#1d4ed8',
                'primary-800' => '#1e40af',
                'primary-900' => '#1e3a8a',
                'primary-950' => '#172554',

                'accent-50' => '#f0fdfa',
                'accent-100' => '#ccfbf1',
                'accent-200' => '#99f6e4',
                'accent-300' => '#5eead4',
                'accent-400' => '#2dd4bf',
                'accent-500' => '#14b8a6',
                'accent-600' => '#0d9488',
                'accent-700' => '#0f766e',
                'accent-800' => '#115e59',
                'accent-900' => '#134e4a',
                'accent-950' => '#042f2e',
            ],
            'sidebar' => ['#172554', '#1e3a8a', '#1d4ed8'],
            'selection' => 'rgba(29, 78, 216, 0.85)',
        ],

        'emerald' => [
            'name' => 'Emerald Forest',
            'icon' => 'park',
            'colors' => [
                'primary-50' => '#ecfdf5',
                'primary-100' => '#d1fae5',
                'primary-200' => '#a7f3d0',
                'primary-300' => '#6ee7b7',
                'primary-400' => '#34d399',
                'primary-500' => '#10b981',
                'primary-600' => '#059669',
                'primary-700' => '#047857',
                'primary-800' => '#065f46',
                'primary-900' => '#064e3b',
                'primary-950' => '#022c22',

                'accent-50' => '#faf5ff',
                'accent-100' => '#f3e8ff',
                'accent-200' => '#e9d5ff',
                'accent-300' => '#d8b4fe',
                'accent-400' => '#c084fc',
                'accent-500' => '#a855f7',
                'accent-600' => '#9333ea',
                'accent-700' => '#7e22ce',
                'accent-800' => '#6b21a8',
                'accent-900' => '#581c87',
                'accent-950' => '#3b0764',
            ],
            'sidebar' => ['#022c22', '#064e3b', '#047857'],
            'selection' => 'rgba(4, 120, 87, 0.85)',
        ],

        'royal' => [
            'name' => 'Royal Purple',
            'icon' => 'auto_awesome',
            'colors' => [
                'primary-50' => '#faf5ff',
                'primary-100' => '#f3e8ff',
                'primary-200' => '#e9d5ff',
                'primary-300' => '#d8b4fe',
                'primary-400' => '#c084fc',
                'primary-500' => '#a855f7',
                'primary-600' => '#9333ea',
                'primary-700' => '#7e22ce',
                'primary-800' => '#6b21a8',
                'primary-900' => '#581c87',
                'primary-950' => '#3b0764',

                'accent-50' => '#fff7ed',
                'accent-100' => '#ffedd5',
                'accent-200' => '#fed7aa',
                'accent-300' => '#fdba74',
                'accent-400' => '#fb923c',
                'accent-500' => '#f97316',
                'accent-600' => '#ea580c',
                'accent-700' => '#c2410c',
                'accent-800' => '#9a3412',
                'accent-900' => '#7c2d12',
                'accent-950' => '#431407',
            ],
            'sidebar' => ['#3b0764', '#581c87', '#7e22ce'],
            'selection' => 'rgba(126, 34, 206, 0.85)',
        ],

        'slate' => [
            'name' => 'Professional Slate',
            'icon' => 'business',
            'colors' => [
                'primary-50' => '#f8fafc',
                'primary-100' => '#f1f5f9',
                'primary-200' => '#e2e8f0',
                'primary-300' => '#cbd5e1',
                'primary-400' => '#94a3b8',
                'primary-500' => '#64748b',
                'primary-600' => '#475569',
                'primary-700' => '#334155',
                'primary-800' => '#1e293b',
                'primary-900' => '#0f172a',
                'primary-950' => '#020617',

                'accent-50' => '#eff6ff',
                'accent-100' => '#dbeafe',
                'accent-200' => '#bfdbfe',
                'accent-300' => '#93c5fd',
                'accent-400' => '#60a5fa',
                'accent-500' => '#3b82f6',
                'accent-600' => '#2563eb',
                'accent-700' => '#1d4ed8',
                'accent-800' => '#1e40af',
                'accent-900' => '#1e3a8a',
                'accent-950' => '#172554',
            ],
            'sidebar' => ['#020617', '#0f172a', '#334155'],
            'selection' => 'rgba(51, 65, 85, 0.85)',
        ],

        'sunset' => [
            'name' => 'Warm Sunset',
            'icon' => 'wb_twilight',
            'colors' => [
                'primary-50' => '#fff7ed',
                'primary-100' => '#ffedd5',
                'primary-200' => '#fed7aa',
                'primary-300' => '#fdba74',
                'primary-400' => '#fb923c',
                'primary-500' => '#f97316',
                'primary-600' => '#ea580c',
                'primary-700' => '#c2410c',
                'primary-800' => '#9a3412',
                'primary-900' => '#7c2d12',
                'primary-950' => '#431407',

                'accent-50' => '#fdf2f8',
                'accent-100' => '#fce7f3',
                'accent-200' => '#fbcfe8',
                'accent-300' => '#f9a8d4',
                'accent-400' => '#f472b6',
                'accent-500' => '#ec4899',
                'accent-600' => '#db2777',
                'accent-700' => '#be185d',
                'accent-800' => '#9d174d',
                'accent-900' => '#831843',
                'accent-950' => '#500724',
            ],
            'sidebar' => ['#431407', '#7c2d12', '#c2410c'],
            'selection' => 'rgba(194, 65, 12, 0.85)',
        ],
    ],
];
