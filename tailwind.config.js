import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
        './app/Http/Livewire/**/*.php',
    ],

      theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        PURPLE: {
          50: '#faf7ff',
          100: '#f0e9ff',
          200: '#e0d0ff',
          300: '#c9aefc',
          400: '#b08bf5',
          500: '#9a6fec',
          600: '#7f55d1',
          700: '#6543ad',
          800: '#4f358a',
          900: '#3e2a6d',
          950: '#241640',
        },
        BLUE: {
          50: '#fffcf8',
          100: '#fff6ee',
          200: '#d9f9ff',
          300: '#c2f5ff',
          400: '#a3d3ff',
          500: '#7ea8f5',
          600: '#547be5',
          700: '#3c3ac6',
          800: '#2f2b9e',
          900: '#47207a',
        },
        terracotta: {
          50: '#fdf2f6',
          100: '#fce7ef',
          200: '#f9cddc',
          300: '#f4a2bf',
          400: '#ec7ba0',
          500: '#de5a86',
          600: '#c7436d',
          700: '#a83259',
          800: '#862848',
          900: '#68203a',
          950: '#460f22',
        },
      },
    },
  },
  plugins: [forms],
};
