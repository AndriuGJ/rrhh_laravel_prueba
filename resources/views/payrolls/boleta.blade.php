<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Pago - {{ $payroll->employee->nombres }} {{ $payroll->employee->apellidos }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #333333;
            background: #d0d0d0;
            padding: 20px;
            line-height: 1.4;
        }

        .page {
            max-width: 750px;
            margin: 0 auto;
            background: #ffffff;
            overflow: hidden;
        }

        /* Header Institucional */
        .header {
            background: #ffffff;
            padding: 18px 25px;
            border-bottom: 2px solid #333333;
        }

        .header-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .header-table td {
            vertical-align: middle;
            padding: 5px;
        }

        .header-center {
            text-align: center;
        }

        .header-center h1 {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .header-center .sub {
            font-size: 9px;
            color: #555555;
            margin-bottom: 2px;
        }

        .logo-img {
            width: 70px;
            height: 70px;
            display: block;
        }

        .badge-periodo {
            text-align: center;
            margin-top: 10px;
            padding: 6px;
            background: #e0e0e0;
            border-top: 1px solid #cccccc;
            border-bottom: 1px solid #cccccc;
            font-size: 10px;
            font-weight: 700;
            color: #333333;
            letter-spacing: 1px;
        }

        /* Info Section */
        .info-section {
            padding: 15px 25px;
            border-bottom: 1px solid #d0d0d0;
        }

        .section-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #555555;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 1px solid #cccccc;
        }

        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }

        .info-label {
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            color: #888888;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 10px;
            font-weight: 600;
            color: #1a1a1a;
        }

        /* Tabla Conceptos */
        .table-section {
            padding: 12px 25px;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table.main-table thead th {
            background: #e8e8e8;
            padding: 8px 10px;
            text-align: left;
            font-weight: 700;
            font-size: 8px;
            text-transform: uppercase;
            color: #555555;
            border: 1px solid #cccccc;
        }

        table.main-table thead th.right {
            text-align: right;
        }

        table.main-table tbody td {
            padding: 6px 10px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }

        table.main-table tbody td.right {
            text-align: right;
            font-weight: 500;
        }

        table.main-table tbody tr.alt td {
            background: #f8f8f8;
        }

        .amount-pos {
            color: #444444;
            font-weight: 600;
        }

        .amount-neg {
            color: #880000;
            font-weight: 600;
        }

        .total-row td {
            background: #d8d8d8 !important;
            font-weight: 700 !important;
            border-top: 2px solid #aaaaaa;
            font-size: 10px;
            color: #1a1a1a;
        }

        /* Resumen */
        .summary-section {
            padding: 12px 25px;
            background: #f5f5f5;
            border-top: 1px solid #d0d0d0;
        }

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
        }

        .summary-table td {
            background: #ffffff;
            border: 1px solid #cccccc;
            padding: 10px;
            text-align: center;
        }

        .summary-label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #777777;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .summary-value.bruto { color: #333333; }
        .summary-value.desc { color: #880000; }
        .summary-value.neto { color: #2a5a2a; }

        /* Aportes del Empleador */
        .employer-contribution {
            padding: 15px 25px;
            background: #fff8e6;
            border-top: 1px solid #e0d0a0;
        }

        .employer-contribution .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #996600;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-bottom: 1px solid #e0d0a0;
            padding-bottom: 5px;
        }

        .contribution-table {
            width: 100%;
            font-size: 11px;
        }

        .contribution-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #e8e0c8;
        }

        .contribution-table td.amount {
            text-align: right;
            font-weight: 600;
            color: #333333;
        }

        .contribution-table tr.total-row td {
            background: #e8d8a8;
            font-weight: 700;
            color: #664400;
            border-top: 2px solid #c0a060;
        }

        /* Firmas */
        .form-section {
            padding: 20px 25px;
        }

        .form-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .form-table td {
            text-align: center;
            width: 50%;
            padding: 0 30px;
        }

        .form-title {
            font-size: 8px;
            font-weight: 700;
            color: #888888;
            margin-bottom: 35px;
            text-transform: uppercase;
        }

        .form-line {
            border-top: 1px solid #333333;
            padding-top: 5px;
            font-size: 9px;
            color: #555555;
        }

        /* Footer */
        .footer {
            background: #e8e8e8;
            color: #555555;
            padding: 10px 25px;
            text-align: center;
            font-size: 8px;
            border-top: 1px solid #cccccc;
        }

        /* Print */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .page {
                max-width: 100%;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 80px; text-align: left;">
                        <img src="{{ \App\Support\Empresa::logoPath() }}" alt="Logo" class="logo-img">
                    </td>
                    <td>
                        <div class="header-center">
                            <h1>{{ \App\Support\Empresa::nombre() }}</h1>
                            <div class="sub">RUC: {{ \App\Support\Empresa::ruc() }}</div>
                            <div class="sub">{{ \App\Support\Empresa::direccion() }}</div>
                        </div>
                    </td>
                    <td style="width: 80px; text-align: right;">
                        <img src="{{ \App\Support\Empresa::logoPath() }}" alt="Logo" class="logo-img">
                    </td>
                </tr>
            </table>
            <div class="badge-periodo">BOLETA DE PAGO - PERIODO: {{ $payroll->periodo }}</div>
        </div>

        <!-- Datos del Trabajador -->
        <div class="info-section">
            <div class="section-title">Datos del Trabajador</div>
            <table class="info-table">
                <tr>
                    <td style="width: 50%;">
                        <div class="info-label">Apellidos y Nombres</div>
                        <div class="info-value">{{ $payroll->employee->apellidos }} {{ $payroll->employee->nombres }}</div>
                    </td>
                    <td style="width: 50%;">
                        <div class="info-label">DNI</div>
                        <div class="info-value">{{ $payroll->employee->dni }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="info-label">Cargo</div>
                        <div class="info-value">{{ $payroll->employee->cargo ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="info-label">Régimen Laboral</div>
                        <div class="info-value">{{ $payroll->employee->regimen_laboral }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="info-label">Fecha de Ingreso</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($payroll->employee->fecha_ingreso)->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div class="info-label">Nivel</div>
                        <div class="info-value">{{ $payroll->employee->nivel ?? '-' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="info-label">Meta</div>
                        <div class="info-value">{{ $payroll->employee->meta ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="info-label">Rubro</div>
                        <div class="info-value">{{ $payroll->employee->rubro ?? '-' }}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="info-label">Sistema de Pensiones</div>
                        <div class="info-value">{{ $payroll->sistema_pensiones }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Detalle -->
        <div class="table-section">
            <div class="section-title">Detalle de Ingresos y Descuentos</div>
            <table class="main-table">
                <thead>
                    <tr>
                        <th style="width: 55%;">Concepto</th>
                        <th class="right">Ingresos</th>
                        <th class="right">Descuentos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Sueldo Básico</td>
                        <td class="right amount-pos">S/. {{ number_format($payroll->sueldo_basico, 2) }}</td>
                        <td class="right">-</td>
                    </tr>
                    <tr class="alt">
                        <td>Asignación Familiar</td>
                        <td class="right amount-pos">S/. {{ number_format($payroll->asignacion_familiar, 2) }}</td>
                        <td class="right">-</td>
                    </tr>
                    @if($payroll->bonificaciones_remunerativas > 0)
                    <tr>
                        <td>Bonificaciones Remunerativas</td>
                        <td class="right amount-pos">S/. {{ number_format($payroll->bonificaciones_remunerativas, 2) }}</td>
                        <td class="right">-</td>
                    </tr>
                    @endif
                    @if($payroll->tardanzas > 0)
                    <tr class="alt">
                        <td>Tardanzas ({{ $payroll->tardanzas }} min)</td>
                        <td class="right">-</td>
                        <td class="right amount-neg">S/. {{ number_format($payroll->monto_tardanzas, 2) }}</td>
                    </tr>
                    @endif
                    @if($payroll->faltas > 0)
                    <tr>
                        <td>Faltas ({{ $payroll->faltas }})</td>
                        <td class="right">-</td>
                        <td class="right amount-neg">S/. {{ number_format($payroll->monto_faltas, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="alt">
                        <td>
                            {{ $payroll->sistema_pensiones }}
                            @if($payroll->sistema_pensiones === 'AFP')
                                ({{ number_format($payroll->retencion_pensiones / $payroll->remuneracion_bruta * 100, 2) }}%)
                            @elseif($payroll->sistema_pensiones === 'ONP')
                                (13%)
                            @endif
                        </td>
                        <td class="right">-</td>
                        <td class="right amount-neg">S/. {{ number_format($payroll->retencion_pensiones, 2) }}</td>
                    </tr>
                    @if($payroll->renta_quinta > 0)
                    <tr>
                        <td>Renta Quinta Categoría</td>
                        <td class="right">-</td>
                        <td class="right amount-neg">S/. {{ number_format($payroll->renta_quinta, 2) }}</td>
                    </tr>
                    @endif
                    @if($payroll->bonificaciones_no_remunerativas > 0)
                    <tr class="alt">
                        <td>Bonificaciones No Remunerativas</td>
                        <td class="right amount-pos">S/. {{ number_format($payroll->bonificaciones_no_remunerativas, 2) }}</td>
                        <td class="right">-</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td class="right">S/. {{ number_format($payroll->total_ingresos, 2) }}</td>
                        <td class="right">S/. {{ number_format($payroll->total_descuentos, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Resumen -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td>
                        <div class="summary-label">Remuneración Bruta</div>
                        <div class="summary-value bruto">S/. {{ number_format($payroll->remuneracion_bruta, 2) }}</div>
                    </td>
                    <td>
                        <div class="summary-label">Total Descuentos</div>
                        <div class="summary-value desc">- S/. {{ number_format($payroll->total_descuentos, 2) }}</div>
                    </td>
                    <td>
                        <div class="summary-label">Neto a Pagar</div>
                        <div class="summary-value neto">S/. {{ number_format($payroll->neto_pagar, 2) }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Aportes del Empleador -->
        <div class="employer-contribution">
            <div class="section-title">Aportes del Empleador</div>
            <table class="contribution-table">
                <tr>
                    <td>ESSALUD (9%)</td>
                    <td class="amount">S/. {{ number_format($payroll->essalud, 2) }}</td>
                </tr>
                <tr>
                    <td>SCTR - Salud</td>
                    <td class="amount">S/. {{ number_format($payroll->sctr_salud, 2) }}</td>
                </tr>
                <tr>
                    <td>SCTR - Pensiones</td>
                    <td class="amount">S/. {{ number_format($payroll->sctr_pensiones, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Aportes del Empleador</td>
                    <td class="amount">S/. {{ number_format($payroll->total_aportes_empleador, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Firmas -->
        <div class="form-section">
            <table class="form-table">
                <tr>
                    <td>
                        <div class="form-title">Firma del Empleador</div>
                        <div class="form-line">Representante Legal</div>
                    </td>
                    <td>
                        <div class="form-title">Firma del Trabajador</div>
                        <div class="form-line">{{ $payroll->employee->apellidos }} {{ $payroll->employee->nombres }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            Documento generado automáticamente - Sistema de Recursos Humanos - {{ \App\Support\Empresa::nombre() }}
        </div>
    </div>
</body>
</html>

