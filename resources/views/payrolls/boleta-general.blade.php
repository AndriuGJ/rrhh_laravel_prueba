<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla General - {{ $periodo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #333333;
            line-height: 1.3;
            padding: 10px;
            background: #d0d0d0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        * {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .page {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            padding: 15px 20px;
        }

        /* Header */
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
            margin-bottom: 5px;
        }

        .header-top {
            width: 100%;
        }

        .header-top td {
            vertical-align: middle;
            padding: 5px;
        }

        .header-top .logo-cell {
            width: 90px;
            text-align: left;
        }

        .header-top .center-cell {
            text-align: center;
        }

        .header-top .right-cell {
            width: 200px;
            text-align: right;
            vertical-align: top;
        }

        .logo-img {
            width: 75px;
            height: auto;
        }

        .header h1 {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .header .subtitle {
            font-size: 10px;
            color: #555;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .header .periodo {
            font-size: 10px;
            color: #333;
            margin-bottom: 1px;
            font-weight: 700;
        }

        .header .modalidad {
            font-size: 9px;
            color: #555;
        }

        .header .numero-planilla {
            font-size: 9px;
            color: #333;
            margin-bottom: 2px;
            font-weight: 700;
        }

        /* Bloque por empleado */
        .empleado-bloque {
            border: 1px dashed #333;
            margin-bottom: 3px;
        }

        .empleado-bloque:last-child {
            margin-bottom: 0;
        }

        /* Encabezado del bloque */
        .bloque-header {
            background: #e0e0e0;
            border-bottom: 1px dashed #333;
        }

        .bloque-header td {
            padding: 4px 6px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            border-right: 1px dashed #ccc;
            color: #333;
        }

        .bloque-header td:last-child {
            border-right: none;
        }

        /* Cuerpo del bloque */
        .bloque-body td {
            padding: 4px 6px;
            vertical-align: top;
            border-right: 1px dashed #ccc;
        }

        .bloque-body td:last-child {
            border-right: none;
        }

        /* Columnas de datos */
        .col-datos {
            width: 28%;
            border-right: 1px dashed #333 !important;
        }

        .col-ingresos {
            width: 17%;
            border-right: 1px dashed #333 !important;
        }

        .col-egresos {
            width: 17%;
            border-right: 1px dashed #333 !important;
        }

        .col-totales {
            width: 22%;
            border-right: 1px dashed #333 !important;
        }

        .col-aportes {
            width: 16%;
        }

        /* Filas dentro de columnas */
        .dato-row {
            margin-bottom: 3px;
            line-height: 1.4;
        }

        .dato-label {
            font-size: 8px;
            color: #666;
            font-weight: 600;
        }

        .dato-value {
            font-size: 9px;
            color: #1a1a1a;
            font-weight: 600;
        }

        .ingreso-line {
            font-size: 9px;
            padding: 2px 0;
            color: #1a1a1a;
            font-weight: 600;
        }

        .ingreso-line::before {
            content: "+";
            color: #444;
            margin-right: 2px;
        }

        .egreso-line {
            font-size: 9px;
            padding: 2px 0;
            color: #1a1a1a;
            font-weight: 600;
        }

        .egreso-line::before {
            content: "-";
            color: #880000;
            margin-right: 2px;
        }

        .total-line {
            font-size: 9px;
            padding: 2px 0;
            color: #1a1a1a;
            font-weight: 600;
        }

        .total-line strong {
            font-weight: 700;
        }

        .aporte-line {
            font-size: 9px;
            padding: 2px 0;
            color: #1a1a1a;
            font-weight: 600;
        }

        .aporte-line strong {
            font-weight: 700;
        }

        .firma-line {
            margin-top: 20px;
            font-size: 9px;
            color: #555;
            font-weight: 600;
        }

        .firma-line span {
            border-top: 1px solid #333;
            display: block;
            padding-top: 2px;
            font-weight: 400;
        }

        /* Footer */
        .footer {
            text-align: right;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 10px;
            font-weight: 600;
            color: #333;
            font-style: italic;
            border-top: 1px dashed #333;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .page {
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <table class="header-top">
                <tr>
                    <td class="logo-cell">
                        <img src="{{ \App\Support\Empresa::logoPath() }}" alt="Logo" class="logo-img">
                    </td>
                    <td class="center-cell">
                        <h1>{{ \App\Support\Empresa::nombre() }}</h1>
                        <div class="subtitle">:: {{ $regimenLabel }} :: {{ $dl ?? 'DL. 276' }}</div>
                    </td>
                    <td class="right-cell">
                        @if($numPlanilla ?? false)
                        <div class="numero-planilla">N° PPLLA: {{ $numPlanilla }}</div>
                        @endif
                        <div class="periodo">PERIODO: {{ $periodo }}</div>
                        <div class="modalidad">Modalidad: Remuneraciones</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Bloques de empleados -->
        @foreach($payrolls as $p)
        @php
            $empleado = $p->employee;
            $diasAsistidos = 30 - ($p->faltas ?? 0);
        @endphp
        <table class="empleado-bloque" width="100%" cellpadding="0" cellspacing="0">
            <!-- Encabezado de columnas -->
            <tr class="bloque-header">
                <td class="col-datos">DATOS GENERALES</td>
                <td class="col-ingresos">INGRESOS</td>
                <td class="col-egresos">EGRESOS</td>
                <td class="col-totales">TOTALES</td>
                <td class="col-aportes">APORTES</td>
            </tr>
            <!-- Cuerpo -->
            <tr class="bloque-body">
                <!-- DATOS GENERALES -->
                <td class="col-datos">
                    <div class="dato-row">
                        <span class="dato-label">DNI: </span>
                        <span class="dato-value">{{ $empleado->dni }}</span>
                        <span class="dato-value" style="margin-left: 5px;">{{ strtoupper($empleado->apellidos) }} {{ strtoupper($empleado->nombres) }}</span>
                    </div>
                    <div class="dato-row">
                        <span class="dato-label">Cargo: </span>
                        <span class="dato-value">{{ $empleado->cargo ?? '-' }}</span>
                    </div>
                    <div class="dato-row">
                        <span class="dato-label">Fec.Ingreso: </span>
                        <span class="dato-value">{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</span>
                        <span style="margin-left: 10px;">
                            <span class="dato-label">Nivel: </span>
                            <span class="dato-value">{{ $empleado->nivel ?? 'OBRERO' }}</span>
                        </span>
                    </div>
                    <div class="dato-row">
                        <span class="dato-label">Reg.Pens.: </span>
                        <span class="dato-value">{{ $p->sistema_pensiones }}</span>
                        <span style="margin-left: 10px;">
                            <span class="dato-label">Cuspp: </span>
                            <span class="dato-value">{{ $empleado->cuspp ?? '-' }}</span>
                        </span>
                    </div>
                    <div class="dato-row">
                        <span class="dato-label">Días: </span>
                        <span class="dato-value">{{ $diasAsistidos }}</span>
                        <span style="margin-left: 10px;">
                            <span class="dato-label">N° Cta.Cte.: </span>
                            <span class="dato-value">{{ $empleado->cuenta_bancaria ?? '-' }}</span>
                        </span>
                    </div>
                    <div class="dato-row">
                        <span class="dato-label">Faltas/Tard.: </span>
                        <span class="dato-value">Día {{ $p->faltas ?? 0 }} &nbsp; Hora {{ $p->tardanzas ?? 0 }}</span>
                    </div>
                </td>

                <!-- INGRESOS -->
                <td class="col-ingresos">
                    <div class="ingreso-line">Rem Mens {{ number_format($p->sueldo_basico, 2) }}</div>
                    <div class="ingreso-line">Asignación F {{ number_format($p->asignacion_familiar, 2) }}</div>
                    @if($p->bonificaciones_remunerativas > 0)
                    <div class="ingreso-line">Bonif. Rem. {{ number_format($p->bonificaciones_remunerativas, 2) }}</div>
                    @endif
                </td>

                <!-- EGRESOS -->
                <td class="col-egresos">
                    @if($p->retencion_pensiones > 0)
                    <div class="egreso-line">{{ $p->sistema_pensiones }} {{ number_format($p->retencion_pensiones, 2) }}</div>
                    @endif
                    @if($p->renta_quinta > 0)
                    <div class="egreso-line">Renta 5ta {{ number_format($p->renta_quinta, 2) }}</div>
                    @endif
                    @if($p->monto_tardanzas > 0)
                    <div class="egreso-line">Tardanzas {{ number_format($p->monto_tardanzas, 2) }}</div>
                    @endif
                    @if($p->monto_faltas > 0)
                    <div class="egreso-line">Faltas {{ number_format($p->monto_faltas, 2) }}</div>
                    @endif
                </td>

                <!-- TOTALES -->
                <td class="col-totales">
                    <div class="total-line">T.Rem.Aseg: <strong>{{ number_format($p->remuneracion_bruta, 2) }}</strong></div>
                    <div class="total-line">Tot.Ingresos: <strong>{{ number_format($p->total_ingresos, 2) }}</strong></div>
                    <div class="total-line">Tot.Egresos: <strong>{{ number_format($p->total_descuentos, 2) }}</strong></div>
                    <div class="total-line" style="margin-top: 5px;">Neto Pagar: <strong>{{ number_format($p->neto_pagar, 2) }}</strong></div>
                </td>

                <!-- APORTES (con firma) -->
                <td class="col-aportes">
                    <div class="aporte-line">Essalud: <strong>{{ number_format($p->essalud, 2) }}</strong></div>
                    @if($p->sctr_salud > 0 || $p->sctr_pensiones > 0)
                    <div class="aporte-line">SCTR: <strong>{{ number_format($p->sctr_salud + $p->sctr_pensiones, 2) }}</strong></div>
                    @endif
                    <div class="firma-line">
                        <span>Firma:</span>
                    </div>
                </td>
            </tr>
        </table>
        @endforeach

        <!-- Footer -->
        <div class="footer">
            Pág. 1 de {{ $totalPaginas ?? 1 }}
        </div>

        <!-- Totales finales -->
        <div style="margin-top: 15px; border-top: 2px dashed #333; padding-top: 10px;">
            <table width="100%" style="border-collapse: collapse; font-size: 9px;">
                <tr style="background: #cccccc;">
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;"></td>
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;">SUELDO</td>
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;">ASIG.FAM.</td>
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;">BONIF.REM.</td>
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;">REM.BRUTA</td>
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;">ESSALUD</td>
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;">SCTR</td>
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;">TOTAL APORTES</td>
                    <td style="border: 1px dashed #333; padding: 4px; text-align: center; font-weight: bold;">NETO</td>
                </tr>
                <tr style="background: #e0e0e0;">
                    <td style="border: 1px dashed #333; padding: 6px; text-align: center; font-weight: bold;">TOTALES</td>
                    <td style="border: 1px dashed #333; padding: 6px; text-align: right; font-weight: bold;">{{ number_format($payrolls->sum('sueldo_basico'), 2) }}</td>
                    <td style="border: 1px dashed #333; padding: 6px; text-align: right; font-weight: bold;">{{ number_format($payrolls->sum('asignacion_familiar'), 2) }}</td>
                    <td style="border: 1px dashed #333; padding: 6px; text-align: right; font-weight: bold;">{{ number_format($payrolls->sum('bonificaciones_remunerativas'), 2) }}</td>
                    <td style="border: 1px dashed #333; padding: 6px; text-align: right; font-weight: bold;">{{ number_format($payrolls->sum('remuneracion_bruta'), 2) }}</td>
                    <td style="border: 1px dashed #333; padding: 6px; text-align: right; font-weight: bold;">{{ number_format($payrolls->sum('essalud'), 2) }}</td>
                    <td style="border: 1px dashed #333; padding: 6px; text-align: right; font-weight: bold;">{{ number_format($payrolls->sum('sctr_salud') + $payrolls->sum('sctr_pensiones'), 2) }}</td>
                    <td style="border: 1px dashed #333; padding: 6px; text-align: right; font-weight: bold;">{{ number_format($payrolls->sum('total_aportes_empleador'), 2) }}</td>
                    <td style="border: 1px dashed #333; padding: 6px; text-align: right; font-weight: bold;">{{ number_format($payrolls->sum('neto_pagar'), 2) }}</td>
                </tr>
            </table>
            <div style="text-align: center; font-size: 9px; margin-top: 5px; padding: 4px;">
                ------------------------------------------------------------
            </div>
        </div>
    </div>
</body>
</html>

