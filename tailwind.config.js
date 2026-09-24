/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#eefdf6',
                    100: '#d6f9e7',
                    200: '#aef1d1',
                    300: '#75e3b3',
                    400: '#3ecd91',
                    500: '#17b377',
                    600: '#0d9264',
                    700: '#0c7453',
                    800: '#0e5c44',
                    900: '#0d4c39',
                },
                ink: {
                    50: '#f6f8fa',
                    100: '#eceff3',
                    200: '#d5dbe3',
                    300: '#aeb9c7',
                    400: '#8090a3',
                    500: '#617286',
                    600: '#4c596c',
                    700: '#3e4959',
                    800: '#293241',
                    900: '#161c26',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            boxShadow: {
                soft: '0 1px 2px rgba(16,24,40,.04), 0 1px 3px rgba(16,24,40,.06)',
                card: '0 2px 8px rgba(16,24,40,.06), 0 1px 2px rgba(16,24,40,.04)',
            },
            borderRadius: {
                xl2: '1.25rem',
            },
        },
    },
    plugins: [],
};
