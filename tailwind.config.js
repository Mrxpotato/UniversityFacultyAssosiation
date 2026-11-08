import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Roboto', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: 'var(--color-primary-50, #fef2f2)',
                    100: 'var(--color-primary-100, #fee2e2)',
                    200: 'var(--color-primary-200, #fdbbbb)',
                    300: 'var(--color-primary-300, #f98a8a)',
                    400: 'var(--color-primary-400, #e84d4d)',
                    500: 'var(--color-primary-500, #C1121F)',
                    600: 'var(--color-primary-600, #a50f1a)',
                    700: 'var(--color-primary-700, #780000)',
                    800: 'var(--color-primary-800, #5c0000)',
                    900: 'var(--color-primary-900, #450000)',
                    950: 'var(--color-primary-950, #2d0000)',
                },
                accent: {
                    50: 'var(--color-accent-50, #f0f7fc)',
                    100: 'var(--color-accent-100, #daeaf5)',
                    200: 'var(--color-accent-200, #b8d7ec)',
                    300: 'var(--color-accent-300, #8ebfdb)',
                    400: 'var(--color-accent-400, #669BBC)',
                    500: 'var(--color-accent-500, #4a83a8)',
                    600: 'var(--color-accent-600, #336d92)',
                    700: 'var(--color-accent-700, #1a5278)',
                    800: 'var(--color-accent-800, #003049)',
                    900: 'var(--color-accent-900, #00253a)',
                    950: 'var(--color-accent-950, #001825)',
                },
                cream: '#FDF0D5',
                success: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065f46',
                    900: '#064e3b',
                },
                warning: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out forwards',
                'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                'fade-in-down': 'fadeInDown 0.6s ease-out forwards',
                'fade-in-left': 'fadeInLeft 0.6s ease-out forwards',
                'fade-in-right': 'fadeInRight 0.6s ease-out forwards',
                'slide-in-up': 'slideInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'slide-in-down': 'slideInDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'slide-in-left': 'slideInLeft 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'slide-in-right': 'slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'scale-in': 'scaleIn 0.3s ease-out forwards',
                'scale-in-center': 'scaleInCenter 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'bounce-in': 'bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards',
                'float': 'float 6s ease-in-out infinite',
                'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
                'shimmer': 'shimmer 2s linear infinite',
                'gradient-shift': 'gradientShift 8s ease infinite',
                'spin-slow': 'spin 3s linear infinite',
                'wiggle': 'wiggle 1s ease-in-out infinite',
                'heartbeat': 'heartbeat 1.5s ease-in-out infinite',
                'ripple': 'ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite',
                'count-up': 'countUp 0.5s ease-out forwards',
                'typewriter': 'typewriter 2s steps(40) forwards',
                'blink': 'blink 1s step-end infinite',
                'progress': 'progress 1.5s ease-in-out infinite',
                'stagger-1': 'fadeInUp 0.5s ease-out 0.1s forwards',
                'stagger-2': 'fadeInUp 0.5s ease-out 0.2s forwards',
                'stagger-3': 'fadeInUp 0.5s ease-out 0.3s forwards',
                'stagger-4': 'fadeInUp 0.5s ease-out 0.4s forwards',
                'stagger-5': 'fadeInUp 0.5s ease-out 0.5s forwards',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                fadeInRight: {
                    '0%': { opacity: '0', transform: 'translateX(30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInUp: {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                slideInDown: {
                    '0%': { transform: 'translateY(-100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.9)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                scaleInCenter: {
                    '0%': { opacity: '0', transform: 'scale(0.5)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                bounceIn: {
                    '0%': { opacity: '0', transform: 'scale(0.3)' },
                    '50%': { transform: 'scale(1.05)' },
                    '70%': { transform: 'scale(0.9)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                pulseGlow: {
                    '0%, 100%': { boxShadow: '0 0 5px rgba(30, 58, 138, 0.4), 0 0 20px rgba(66, 99, 204, 0.2)' },
                    '50%': { boxShadow: '0 0 20px rgba(30, 58, 138, 0.6), 0 0 40px rgba(66, 99, 204, 0.35)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                gradientShift: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-3deg)' },
                    '50%': { transform: 'rotate(3deg)' },
                },
                heartbeat: {
                    '0%, 100%': { transform: 'scale(1)' },
                    '14%': { transform: 'scale(1.1)' },
                    '28%': { transform: 'scale(1)' },
                    '42%': { transform: 'scale(1.1)' },
                    '70%': { transform: 'scale(1)' },
                },
                ripple: {
                    '0%': { transform: 'scale(0)', opacity: '1' },
                    '100%': { transform: 'scale(4)', opacity: '0' },
                },
                countUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                typewriter: {
                    '0%': { width: '0' },
                    '100%': { width: '100%' },
                },
                blink: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0' },
                },
                progress: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(100%)' },
                },
            },
            backgroundSize: {
                '300%': '300%',
            },
            transitionTimingFunction: {
                'bounce-in': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                'smooth': 'cubic-bezier(0.16, 1, 0.3, 1)',
            },
            boxShadow: {
                'glow': '0 0 20px rgba(30, 58, 138, 0.35)',
                'glow-lg': '0 0 40px rgba(30, 58, 138, 0.4)',
                'glow-accent': '0 0 25px rgba(14, 165, 233, 0.3), 0 0 50px rgba(30, 58, 138, 0.2)',
                'inner-glow': 'inset 0 0 20px rgba(66, 99, 204, 0.15)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'card-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                'card-accent': '0 4px 15px -3px rgba(14, 165, 233, 0.2), 0 2px 6px -2px rgba(30, 58, 138, 0.1)',
                'float': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
                'inner-subtle': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    plugins: [forms],
};
