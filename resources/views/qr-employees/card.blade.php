<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carnet QR - {{ $employee->nombres }} {{ $employee->apellidos }}</title>
    <style>
        @page {
            size: 5.5cm 8.5cm;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #f5f5f4;
            min-height: 100vh;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2cm;
        }

        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px;
            background: #1c1917;
            color: #fafaf9;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
            z-index: 10;
        }

        .print-toolbar button,
        .print-toolbar a {
            padding: 8px 18px;
            border: 0;
            background: #fafaf9;
            color: #1c1917;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .print-toolbar button:hover,
        .print-toolbar a:hover {
            background: #e7e5e4;
        }

        @media print {
            html, body {
                background: #ffffff;
                padding: 0;
                min-height: auto;
            }

            body {
                display: block;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="print-toolbar no-print">
        <span>Carnet 5.5 cm × 8.5 cm — {{ $employee->nombres }} {{ $employee->apellidos }}</span>
        <button type="button" onclick="window.print()">Imprimir</button>
        <a href="{{ route('qr-employees.print', $employee->id) }}">Volver</a>
    </div>

    @include('qr-employees.partials.carnet', ['qrDataUri' => $qrDataUri])
</body>
</html>
