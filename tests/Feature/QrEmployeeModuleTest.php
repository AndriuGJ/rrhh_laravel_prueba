<?php

use App\Models\Employee;
use App\Models\User;
use App\Support\Empresa;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    if (! DB::table('roles')->where('id', 1)->exists()) {
        DB::table('roles')->insert([
            ['id' => 1, 'nombre' => 'Administrador', 'descripcion' => 'Acceso total', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Usuario', 'descripcion' => 'Acceso limitado', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    $this->user = User::factory()->create(['rol_id' => 1]);

    $this->employee = Employee::create([
        'nombres' => 'JUAN',
        'apellidos' => 'PEREZ LOYOLA',
        'dni' => '12345678',
        'email' => 'juan@example.com',
        'cargo' => 'Auxiliar Administrativo',
        'regimen_laboral' => 'DL 276',
        'sueldo_basico' => 1500,
        'activo' => true,
        'estado' => true,
        'qr_code' => 'CodigoQRPrueba123456',
    ]);
});

test('los invitados son redirigidos al login', function () {
    $this->get('/qr-employees')->assertRedirect('/login');
    $this->get("/qr-employees/{$this->employee->id}")->assertRedirect('/login');
});

test('el index lista a los empleados', function () {
    $this->actingAs($this->user)
        ->get('/qr-employees')
        ->assertOk()
        ->assertSee('QR - Empleados')
        ->assertSee('JUAN')
        ->assertSee('PEREZ LOYOLA')
        ->assertSee('12345678');
});

test('el index busca por dni', function () {
    $this->actingAs($this->user)
        ->get('/qr-employees?search=12345678')
        ->assertOk()
        ->assertSee('JUAN');

    $this->actingAs($this->user)
        ->get('/qr-employees?search=99999999')
        ->assertOk()
        ->assertSee('No se encontraron empleados.');
});

test('la vista del carnet muestra QR, datos y empresa', function () {
    $html = $this->actingAs($this->user)
        ->get("/qr-employees/{$this->employee->id}")
        ->assertOk()
        ->assertSee('Carnet de JUAN PEREZ LOYOLA')
        ->assertSee('Régimen laboral')
        ->assertSee('DL 276')
        ->assertSee('Auxiliar Administrativo')
        ->assertSee(Empresa::nombre())
        ->getContent();

    expect($html)->toContain('data:image/png;base64');
});

test('la vista preliminar de impresión muestra el botón hacia el carnet', function () {
    $this->actingAs($this->user)
        ->get("/qr-employees/{$this->employee->id}/print")
        ->assertOk()
        ->assertSee('Vista preliminar')
        ->assertSee('Abrir carnet para imprimir')
        ->assertSee('5.5 cm de ancho × 8.5 cm de alto');
});

test('el carnet para imprimir es independiente y mide 5.5cm x 8.5cm', function () {
    $html = $this->actingAs($this->user)
        ->get("/qr-employees/{$this->employee->id}/card")
        ->assertOk()
        ->getContent();

    expect($html)
        ->toContain('size: 5.5cm 8.5cm')
        ->toContain('width: 5.5cm')
        ->toContain('height: 8.5cm')
        ->toContain('window.print()')
        ->toContain('data:image/png;base64')
        ->toContain(Empresa::nombre());
});

test('el QR se genera automaticamente al crear un empleado', function () {
    $this->actingAs($this->user)
        ->post('/employees', [
            'nombres' => 'MARIA',
            'apellidos' => 'GARCIA ROJAS',
            'dni' => '87654321',
            'email' => 'maria@example.com',
            'sueldo_basico' => 1800,
            'activo' => '1',
        ])
        ->assertRedirect('/employees');

    $creado = Employee::where('dni', '87654321')->firstOrFail();

    expect($creado->qr_code)->not->toBeNull()
        ->and(strlen($creado->qr_code))->toBe(20)
        ->and($creado->qr_code)->not->toBe($this->employee->qr_code);
});

test('el QR no se muestra en el index de empleados', function () {
    $this->actingAs($this->user)
        ->get('/employees')
        ->assertOk()
        ->assertDontSee('data:image/png;base64', false);
});
