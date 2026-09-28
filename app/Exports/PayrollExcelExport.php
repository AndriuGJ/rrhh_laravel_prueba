<?php

namespace App\Exports;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;

class PayrollExcelExport
{
    protected $payrolls;

    protected $periodo;

    protected $regimenLabel;

    public function __construct($payrolls, $periodo, $regimenLabel = 'TODOS LOS REGÍMENES')
    {
        $this->payrolls = $payrolls;
        $this->periodo = $periodo;
        $this->regimenLabel = $regimenLabel;
    }

    public function generate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $this->configureSheet($sheet);
        $this->addHeaders($sheet);
        $lastDataRow = $this->addData($sheet);
        $this->addTotals($sheet, $lastDataRow);

        return $spreadsheet;
    }

    protected function configureSheet($sheet): void
    {
        $sheet->setTitle('Planilla de Remuneraciones');
        $sheet->getProtection()->setSheet(false);

        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(14);
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(22);
        $sheet->getColumnDimension('L')->setWidth(12);
        $sheet->getColumnDimension('M')->setWidth(12);
        $sheet->getColumnDimension('N')->setWidth(12);
        $sheet->getColumnDimension('O')->setWidth(12);
        $sheet->getColumnDimension('P')->setWidth(12);
        $sheet->getColumnDimension('Q')->setWidth(13);
        $sheet->getColumnDimension('R')->setWidth(13);
        $sheet->getColumnDimension('S')->setWidth(12);
        $sheet->getColumnDimension('T')->setWidth(12);
        $sheet->getColumnDimension('U')->setWidth(12);
        $sheet->getColumnDimension('V')->setWidth(12);
        $sheet->getColumnDimension('W')->setWidth(14);
    }

    protected function addHeaders($sheet): void
    {
        $sheet->mergeCells('A1:W1');
        $sheet->setCellValue('A1', strtoupper(\App\Support\Empresa::nombre()));
        $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:W2');
        $sheet->setCellValue('A2', 'PLANILLA DE REMUNERACIONES - '.strtoupper($this->regimenLabel));
        $sheet->getStyle('A2')->getFont()->setSize(12)->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:W3');
        $sheet->setCellValue('A3', 'PERÍODO: '.$this->periodo);
        $sheet->getStyle('A3')->getFont()->setSize(10)->setBold(true);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A4:W4');
        $sheet->setCellValue('A4', 'RUC: '.\App\Support\Empresa::ruc().' - '.\App\Support\Empresa::direccion());
        $sheet->getStyle('A4')->getFont()->setSize(10);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headers = [
            'DNI',
            'APELLIDOS Y NOMBRES',
            'CARGO',
            'FECHA INGRESO',
            'NIVEL',
            'RÉG. PENS.',
            'CUSPP',
            'META',
            'RUBRO',
            'BANCO',
            'CUENTA BANCARIA',
            'FALTAS/TARD',
            'DÍAS ASIST.',
            'SUELDO',
            'ASIG. FAM.',
            'BONIF. REM.',
            'REM. BRUTA',
            'APORTE AFP/ONP',
            'ESSALUD',
            'SCTR',
            'TOTAL APORTES EMP.',
            'NETO A PAGAR',
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $cell = $col.'5';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(9);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $col++;
        }
    }

    protected function addData($sheet): int
    {
        $row = 6;
        foreach ($this->payrolls as $payroll) {
            $empleado = $payroll->employee;
            $diasAsistidos = 30 - ($payroll->faltas ?? 0);

            $data = [
                $empleado->dni,
                $empleado->apellidos.' '.$empleado->nombres,
                $empleado->cargo ?? '-',
                Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y'),
                $empleado->nivel ?? 'OBRERO',
                $payroll->sistema_pensiones,
                $empleado->cuspp ?? '-',
                $empleado->meta ?? '-',
                $empleado->rubro ?? '-',
                $empleado->banco ?? '-',
                $empleado->cuenta_bancaria ?? '-',
                ($payroll->tardanzas ?? 0).' min / '.($payroll->faltas ?? 0).' días',
                $diasAsistidos,
                $payroll->sueldo_basico,
                $payroll->asignacion_familiar,
                $payroll->bonificaciones_remunerativas ?? 0,
                $payroll->remuneracion_bruta,
                $payroll->retencion_pensiones,
                $payroll->essalud,
                $payroll->sctr_salud + $payroll->sctr_pensiones,
                $payroll->total_aportes_empleador,
                $payroll->neto_pagar,
            ];

            $col = 'A';
            foreach ($data as $value) {
                $cell = $col.$row;
                $sheet->setCellValue($cell, $value);

                if (in_array($col, ['N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W'])) {
                    $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
                }

                $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $col++;
            }

            if ($row % 2 == 0) {
                $sheet->getStyle('A'.$row.':S'.$row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('F5F5F5');
            }

            $row++;
        }

        return $row;
    }

    protected function addTotals($sheet, int $lastDataRow): void
    {
        $lastRow = $lastDataRow;

        $sheet->mergeCells('A'.$lastRow.':M'.$lastRow);
        $sheet->setCellValue('A'.$lastRow, 'TOTALES:');
        $sheet->getStyle('A'.$lastRow)->getFont()->setBold(true);
        $sheet->getStyle('A'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $columnsTotals = ['N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W'];
        $dataStartRow = 6;
        foreach ($columnsTotals as $col) {
            $cell = $col.$lastRow;
            $sheet->setCellValue($cell, '=SUM('.$col.$dataStartRow.':'.$col.($lastRow - 1).')');
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DDFFDD');
        }

        $firmasRow = $lastRow + 3;
        $sheet->mergeCells('A'.$firmasRow.':E'.$firmasRow);
        $sheet->setCellValue('A'.$firmasRow, 'GERENTE DE PERSONAL');
        $sheet->getStyle('A'.$firmasRow)->getFont()->setSize(9);
        $sheet->getStyle('A'.$firmasRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('F'.$firmasRow.':J'.$firmasRow);
        $sheet->setCellValue('F'.$firmasRow, 'JEFE DE TESORERÍA');
        $sheet->getStyle('F'.$firmasRow)->getFont()->setSize(9);
        $sheet->getStyle('F'.$firmasRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('K'.$firmasRow.':S'.$firmasRow);
        $sheet->setCellValue('K'.$firmasRow, 'CONTABILIDAD');
        $sheet->getStyle('K'.$firmasRow)->getFont()->setSize(9);
        $sheet->getStyle('K'.$firmasRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    public function download(string $filename = 'planilla.xlsx'): void
    {
        $spreadsheet = $this->generate();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportToResponse(): Response
    {
        $spreadsheet = $this->generate();

        $filename = 'Planilla_'.$this->periodo.'_'.str_replace(' ', '_', $this->regimenLabel).'.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
