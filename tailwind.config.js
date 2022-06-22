module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {},
        container: {
            center: true,
            padding: {
                DEFAULT: '1rem',
                sm: '2rem',
                lg: '4rem',
                xl: '5rem',
                '2xl': '6rem',
            },
        },
    },
    plugins: [require('daisyui')],
    daisyui: {
        themes: [{
            mytheme: {
                "primary": "#570DF8",
                "secondary": "#6b7280",
                "accent": "#1c1917",
                "neutral": "#3D4451",
                "base-100": "#FFFFFF",
                "info": "#22c55e",
                "success": "#36D399",
                "warning": "#FBBD23",
                "error": "#F87272",
            },
        }, ],
    },
}
