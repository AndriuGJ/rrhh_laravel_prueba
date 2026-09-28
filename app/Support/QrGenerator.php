<?php

namespace App\Support;

use App\Models\Employee;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class QrGenerator
{
    public static function generarCodigo(): string
    {
        do {
            $codigo = Str::random(20);
        } while (Employee::where('qr_code', $codigo)->exists());

        return $codigo;
    }

    public static function pngDataUri(?string $codigo, int $size = 300): string
    {
        if ($codigo === null || $codigo === '') {
            return '';
        }

        $qrCode = new QrCode(data: $codigo, size: $size, margin: 8);

        return (new PngWriter)->write($qrCode)->getDataUri();
    }
}
