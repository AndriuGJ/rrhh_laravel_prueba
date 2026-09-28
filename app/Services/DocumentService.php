<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentService
{
    protected const ALLOWED_EXTENSIONS = ['pdf', 'docx', 'png', 'jpg', 'jpeg'];

    protected const MAX_SIZE_MB = 5;

    public function subirArchivo(UploadedFile $archivo, string $dni, string $tipo = 'ingreso'): ?string
    {
        if (! $archivo) {
            return null;
        }

        $extension = strtolower($archivo->getClientOriginalExtension());

        if (! in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \InvalidArgumentException('Extensión no permitida. Solo: '.implode(', ', self::ALLOWED_EXTENSIONS));
        }

        $maxSize = self::MAX_SIZE_MB * 1024 * 1024;
        if ($archivo->getSize() > $maxSize) {
            throw new \InvalidArgumentException('El archivo no debe exceder '.self::MAX_SIZE_MB.'MB');
        }

        $carpeta = 'documents/employees';

        if (! Storage::disk('public')->exists($carpeta)) {
            Storage::disk('public')->makeDirectory($carpeta);
        }

        $nombreArchivo = $dni.'_'.$tipo.'_'.time().'.'.$extension;

        $archivo->storeAs($carpeta, $nombreArchivo, 'public');

        return $carpeta.'/'.$nombreArchivo;
    }

    public function eliminarArchivo(?string $ruta): void
    {
        if (! $ruta) {
            return;
        }

        if (Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->delete($ruta);
        }
    }

    public function obtenerUrl(?string $ruta): ?string
    {
        if (! $ruta || ! Storage::exists($ruta)) {
            return null;
        }

        return asset('storage/'.$ruta);
    }

    public function esImagen(?string $ruta): bool
    {
        if (! $ruta) {
            return false;
        }

        $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));

        return in_array($extension, ['png', 'jpg', 'jpeg']);
    }

    public function obtenerExtension(?string $ruta): ?string
    {
        if (! $ruta) {
            return null;
        }

        return strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
    }

    public function descargarArchivo(string $ruta): Response
    {
        if (! Storage::exists($ruta)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::download($ruta);
    }

    public static function getAllowedExtensions(): array
    {
        return self::ALLOWED_EXTENSIONS;
    }

    public static function getMaxSize(): int
    {
        return self::MAX_SIZE_MB;
    }
}
