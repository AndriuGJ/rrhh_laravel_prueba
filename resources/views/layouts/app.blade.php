<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema RRHH') - {{ \App\Support\Empresa::nombre() }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        jet: '#22333b',
                        jetLight: '#1b282f',
                        jetDark: '#608ea3',
                        almond: '#eae0d5',
                        almondLight: '#f7f3ef',
                        almondDark: '#fbf9f7',
                        khaki: '#c6ac8f',
                        khakiLight: '#ddcebc',
                        khakiDark: '#94897d',
                        stone: '#5e503f',
                        stoneLight: '#ab9880',
                        brandBlack: '#0a0908'
                    },
                    fontFamily: {
                        sans: ['"Open Sans"', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Open Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        summary.nav-toggle {
            list-style: none;
            cursor: pointer;
        }
        summary.nav-toggle::-webkit-details-marker {
            display: none;
        }
        details[open] > summary.nav-toggle .nav-chevron {
            transform: rotate(180deg);
        }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen bg-almondDark flex flex-col">
    @yield('content')
</body>
</html>