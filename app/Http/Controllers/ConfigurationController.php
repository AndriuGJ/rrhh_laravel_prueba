<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigurationRequest;
use App\Models\Configuration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConfigurationController extends Controller
{
    public function index(): View
    {
        $configurations = Configuration::all();
        return view('configurations.index', compact('configurations'));
    }

    public function update(ConfigurationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->guardarImagen($request, 'logo', 'empresa_logo', 'logo_default', 'logo');
        $this->guardarImagen($request, 'fondo_login', 'empresa_fondo_login', 'fondo_default', 'fondo');

        unset($data['logo'], $data['fondo_login'], $data['logo_default'], $data['fondo_default']);

        foreach ($data as $clave => $valor) {
            Configuration::set($clave, $valor);
        }

        return redirect()->route('configurations.index')
            ->with('success', 'Configuración actualizada correctamente.');
    }

    private function guardarImagen(ConfigurationRequest $request, string $campo, string $clave, string $checkboxReset, string $prefijo): void
    {
        $actual = Configuration::get($clave);

        if ($request->boolean($checkboxReset)) {
            if ($actual !== null && $actual !== '') {
                Storage::disk('public')->delete($actual);
                Configuration::set($clave, '');
            }

            return;
        }

        if (! $request->hasFile($campo)) {
            return;
        }

        if ($actual !== null && $actual !== '') {
            Storage::disk('public')->delete($actual);
        }

        /** @var UploadedFile $archivo */
        $archivo = $request->file($campo);
        $extension = strtolower($archivo->getClientOriginalExtension());
        $ruta = $archivo->storeAs('empresa', $prefijo.'_'.time().'.'.$extension, 'public');

        Configuration::set($clave, $ruta);
    }
}
