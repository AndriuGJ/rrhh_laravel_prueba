<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema RRHH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        jet: '#22333b',
                        jetLight: '#1b282f',
                        almond: '#eae0d5',
                        almondLight: '#f7f3ef',
                        almondDark: '#fbf9f7',
                        khaki: '#c6ac8f',
                        khakiLight: '#ddcebc',
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
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            min-height: 100vh;
            background-image: url('{{ \App\Support\Empresa::fondoUrl() }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.30);
            z-index: 0;
        }
        .login-container {
            position: relative;
            z-index: 1;
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 40px 32px;
        }
        .login-container, input, button {
            border-radius: 0 !important;
        }
        input:focus, button:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="login-container">
        <!-- Logo -->
        <div class="text-center mb-6">
            <img
                src="{{ \App\Support\Empresa::logoUrl() }}"
                alt="{{ \App\Support\Empresa::nombre() }}"
                class="h-16 mx-auto"
            >
        </div>

        <!-- Título institucional -->
        <div class="text-center mb-7">
            <h1 class="text-lg font-semibold text-brandBlack tracking-tight">
                SISTEMA DE REMUNERACIÓN
            </h1>
            <p class="text-sm text-stone mt-1">
                {{ \App\Support\Empresa::nombre() }}
            </p>
        </div>

        <!-- Mensaje de error -->
        @if ($errors->any())
        <div class="mb-5 p-3 bg-red-50 text-red-700 text-sm" style="border-left: 3px solid #e53e3e;">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="/login" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-stone text-xs font-medium mb-1.5">Correo institucional</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 focus:border-jet focus:ring-0"
                    placeholder="correo@example.com"
                >
            </div>
            <div>
                <label for="password" class="block text-stone text-xs font-medium mb-1.5">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 focus:border-jet focus:ring-0"
                    placeholder="••••••••"
                >
            </div>
            <button
                type="submit"
                class="w-full py-2.5 bg-jet text-white font-semibold hover:bg-jetLight transition duration-150"
            >
                Iniciar sesión
            </button>
        </form>

        <!-- Usuario demo -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                <span class="font-medium">Usuario demo:</span> rrhh@gmail.com
            </p>
            <p class="text-xs text-gray-500">
                Contraseña: admin123
            </p>
        </div>
    </div>
</body>
</html>
