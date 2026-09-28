<?php

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

beforeEach(function () {
    config(['app.qr_api_key' => 'clave-test']);

    Carbon::setTestNow(Carbon::create(2026, 9, 27, 8, 0, 0, 'America/Lima'));

    $this->employee = Employee::create([
        'nombres' => 'JUAN',
        'apellidos' => 'PEREZ LOYOLA',
        'dni' => '12345678',
        'email' => 'juan@example.com',
        'cargo' => 'Auxiliar',
        'regimen_laboral' => 'DL 276',
        'sueldo_basico' => 1500,
        'activo' => true,
        'estado' => true,
        'qr_code' => 'CodigoQRValido123456',
    ]);
});

afterEach(fn () => Carbon::setTestNow());

function apiMark(array $data, ?string $key = 'clave-test')
{
    $headers = $key === null ? [] : ['X-Api-Key' => $key];

    return test()->postJson('/api/attendances/mark', $data, $headers);
}

function apiLookup(string $codigo, ?string $key = 'clave-test')
{
    $headers = $key === null ? [] : ['X-Api-Key' => $key];

    return test()->postJson('/api/qr/lookup', ['codigo' => $codigo], $headers);
}

test('rechaza peticiones sin api key', function () {
    apiLookup('CodigoQRValido123456', null)
        ->assertStatus(401)
        ->assertJson(['ok' => false, 'codigo' => 'INVALID_API_KEY']);
});

test('rechaza api key incorrecta', function () {
    apiLookup('CodigoQRValido123456', 'clave-mala')
        ->assertStatus(401)
        ->assertJson(['codigo' => 'INVALID_API_KEY']);
});

test('el lookup devuelve los datos del empleado', function () {
    apiLookup('CodigoQRValido123456')
        ->assertOk()
        ->assertJson([
            'ok' => true,
            'empleado' => [
                'id' => $this->employee->id,
                'nombres' => 'JUAN',
                'apellidos' => 'PEREZ LOYOLA',
                'dni' => '12345678',
                'cargo' => 'Auxiliar',
                'regimen_laboral' => 'DL 276',
                'activo' => true,
            ],
        ]);
});

test('el lookup rechaza un qr desconocido', function () {
    apiLookup('CodigoInexistente999')
        ->assertStatus(404)
        ->assertJson(['ok' => false, 'codigo' => 'QR_NOT_FOUND']);
});

test('el lookup rechaza empleados inactivos', function () {
    $this->employee->update(['activo' => false]);

    apiLookup('CodigoQRValido123456')
        ->assertStatus(404)
        ->assertJson(['codigo' => 'EMPLOYEE_INACTIVE']);
});

test('registra entrada de mañana como puntual', function () {
    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '07:50:00',
    ])
        ->assertOk()
        ->assertJsonPath('ok', true)
        ->assertJsonPath('asistencia.turno', 'MANANA')
        ->assertJsonPath('asistencia.estado', 'PUNTUAL')
        ->assertJsonPath('asistencia.hora_entrada', '07:50');

    $asistencia = Attendance::where('employee_id', $this->employee->id)
        ->whereDate('fecha', '2026-09-27')
        ->where('turno', 'MANANA')
        ->firstOrFail();

    expect($asistencia->origen)->toBe('qr')
        ->and($asistencia->minutos_tardanza)->toBe(0);
});

test('calcula tardanza en la entrada de mañana', function () {
    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '08:03:00',
    ])
        ->assertOk()
        ->assertJsonPath('asistencia.estado', 'TARDE')
        ->assertJsonPath('asistencia.minutos_tardanza', 3);
});

test('rechaza la entrada duplicada de mañana', function () {
    $payload = [
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '07:50:00',
    ];

    apiMark($payload)->assertOk();
    apiMark($payload)
        ->assertStatus(409)
        ->assertJson(['ok' => false, 'codigo' => 'ALREADY_MARKED']);
});

test('rechaza la salida sin entrada registrada', function () {
    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'SALIDA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '08:05:00',
    ])
        ->assertStatus(409)
        ->assertJson(['codigo' => 'MARK_OUT_OF_ORDER']);
});

test('registra la salida de mañana después de la entrada', function () {
    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '07:50:00',
    ])->assertOk();

    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'SALIDA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '08:00:00',
    ])
        ->assertOk()
        ->assertJsonPath('asistencia.hora_salida', '08:00');

    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'SALIDA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '08:01:00',
    ])->assertStatus(409);
});

test('registra entrada y salida del turno tarde', function () {
    Carbon::setTestNow(Carbon::create(2026, 9, 27, 14, 0, 0, 'America/Lima'));

    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_TARDE',
        'fecha' => '2026-09-27',
        'hora' => '14:00:00',
    ])
        ->assertOk()
        ->assertJsonPath('asistencia.turno', 'TARDE')
        ->assertJsonPath('asistencia.estado', 'PUNTUAL');

    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'SALIDA_TARDE',
        'fecha' => '2026-09-27',
        'hora' => '14:00:00',
    ])
        ->assertOk()
        ->assertJsonPath('asistencia.hora_salida', '14:00');
});

test('registra la entrada del turno completo como puntual', function () {
    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_COMPLETO',
        'fecha' => '2026-09-27',
        'hora' => '08:00:00',
    ])
        ->assertOk()
        ->assertJsonPath('asistencia.turno', 'COMPLETO')
        ->assertJsonPath('asistencia.estado', 'PUNTUAL')
        ->assertJsonPath('asistencia.minutos_tardanza', 0);
});

test('rechaza marcaciones con fecha futura', function () {
    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_MANANA',
        'fecha' => '2026-09-30',
        'hora' => '08:00:00',
    ])
        ->assertStatus(422)
        ->assertJson(['ok' => false, 'codigo' => 'INVALID_DATE']);
});

test('rechaza horas desfasadas de la hora del servidor', function () {
    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '20:00:00',
    ])
        ->assertStatus(422)
        ->assertJson(['codigo' => 'INVALID_DATE']);
});

test('rechaza un tipo de marcacion desconocido', function () {
    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_NOCHE',
        'fecha' => '2026-09-27',
        'hora' => '08:00:00',
    ])->assertStatus(422);
});

test('no permite marcar empleados inactivos', function () {
    $this->employee->update(['activo' => false]);

    apiMark([
        'codigo' => 'CodigoQRValido123456',
        'tipo_marcacion' => 'ENTRADA_MANANA',
        'fecha' => '2026-09-27',
        'hora' => '08:00:00',
    ])
        ->assertStatus(404)
        ->assertJson(['codigo' => 'EMPLOYEE_INACTIVE']);

    expect(Attendance::count())->toBe(0);
});
