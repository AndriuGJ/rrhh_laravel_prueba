<?php

use App\Models\BonusType;
use App\Models\Employee;
use App\Models\Regimen;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    DB::table('roles')->insert([
        ['id' => 1, 'nombre' => 'Administrador', 'descripcion' => 'Acceso total', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
        ['id' => 2, 'nombre' => 'Usuario', 'descripcion' => 'Acceso limitado', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);

    $this->admin = User::factory()->create(['rol_id' => 1]);
    $this->user = User::factory()->create(['rol_id' => 2]);
});

test('los invitados son redirigidos al login', function () {
    $this->get('/bonifications')->assertRedirect('/login');
    $this->get('/regimens')->assertRedirect('/login');
});

test('los no administradores reciben 403', function () {
    $this->actingAs($this->user)->get('/bonifications')->assertForbidden();
    $this->actingAs($this->user)->get('/regimens')->assertForbidden();
});

test('el administrador puede gestionar bonificaciones', function () {
    $this->actingAs($this->admin)
        ->get('/bonifications')
        ->assertOk()
        ->assertSee('Bonificaciones');

    $this->actingAs($this->admin)
        ->get('/bonifications/create')
        ->assertOk();

    $this->actingAs($this->admin)
        ->post('/bonifications', [
            'nombre' => 'Bono por Titulación',
            'descripcion' => 'Bono único',
            'es_remunerativo' => 1,
            'estado' => 1,
        ])
        ->assertRedirect('/bonifications')
        ->assertSessionHas('success');

    $bonification = BonusType::where('nombre', 'Bono por Titulación')->firstOrFail();
    expect($bonification->es_remunerativo)->toBeTrue();

    $this->actingAs($this->admin)->get("/bonifications/{$bonification->id}")->assertOk();
    $this->actingAs($this->admin)->get("/bonifications/{$bonification->id}/edit")->assertOk();

    $this->actingAs($this->admin)
        ->put("/bonifications/{$bonification->id}", [
            'nombre' => 'Bono por Titulación v2',
            'descripcion' => 'Actualizado',
            'es_remunerativo' => 0,
            'estado' => 1,
        ])
        ->assertRedirect('/bonifications')
        ->assertSessionHas('success');

    expect($bonification->fresh()->nombre)->toBe('Bono por Titulación v2');

    $this->actingAs($this->admin)
        ->delete("/bonifications/{$bonification->id}")
        ->assertRedirect('/bonifications')
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('bonus_types', ['id' => $bonification->id]);
});

test('no se elimina una bonificación en uso', function () {
    $bonus = BonusType::create(['nombre' => 'Bono en Uso', 'es_remunerativo' => true, 'estado' => true]);

    $employee = Employee::create([
        'nombres' => 'Juan',
        'apellidos' => 'Pérez',
        'dni' => '12345678',
        'email' => 'juan@example.com',
    ]);

    $bonus->employeeBonuses()->create(['employee_id' => $employee->id, 'monto' => 100]);

    $this->actingAs($this->admin)
        ->delete("/bonifications/{$bonus->id}")
        ->assertRedirect('/bonifications')
        ->assertSessionHas('error');

    $this->assertDatabaseHas('bonus_types', ['id' => $bonus->id]);
});

test('valida nombres duplicados de bonificaciones', function () {
    BonusType::create(['nombre' => 'Bono Duplicado', 'es_remunerativo' => true, 'estado' => true]);

    $this->actingAs($this->admin)
        ->from('/bonifications/create')
        ->post('/bonifications', [
            'nombre' => 'Bono Duplicado',
            'es_remunerativo' => 1,
            'estado' => 1,
        ])
        ->assertRedirect('/bonifications/create')
        ->assertSessionHasErrors('nombre');
});

test('el administrador puede gestionar regímenes', function () {
    $this->actingAs($this->admin)
        ->get('/regimens')
        ->assertOk()
        ->assertSee('Régimen');

    $this->actingAs($this->admin)->get('/regimens/create')->assertOk();

    $this->actingAs($this->admin)
        ->post('/regimens', [
            'nombre' => 'LEY 30334',
            'descripcion' => 'Régimen especial',
            'estado' => 1,
        ])
        ->assertRedirect('/regimens')
        ->assertSessionHas('success');

    $regimen = Regimen::where('nombre', 'LEY 30334')->firstOrFail();

    $this->actingAs($this->admin)->get("/regimens/{$regimen->id}")->assertOk();
    $this->actingAs($this->admin)->get("/regimens/{$regimen->id}/edit")->assertOk();

    $this->actingAs($this->admin)
        ->put("/regimens/{$regimen->id}", [
            'nombre' => 'LEY 30334',
            'descripcion' => 'Descripción nueva',
            'estado' => 0,
        ])
        ->assertRedirect('/regimens')
        ->assertSessionHas('success');

    expect($regimen->fresh()->descripcion)->toBe('Descripción nueva')
        ->and($regimen->fresh()->estado)->toBeFalse();

    $this->actingAs($this->admin)
        ->delete("/regimens/{$regimen->id}")
        ->assertRedirect('/regimens')
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('regimens', ['id' => $regimen->id]);
});

test('no se elimina un régimen asignado a empleados', function () {
    $regimen = Regimen::create(['nombre' => 'DL 999', 'estado' => true]);

    Employee::create([
        'nombres' => 'Ana',
        'apellidos' => 'Torres',
        'dni' => '87654321',
        'email' => 'ana@example.com',
        'regimen_laboral' => 'DL 999',
    ]);

    $this->actingAs($this->admin)
        ->delete("/regimens/{$regimen->id}")
        ->assertRedirect('/regimens')
        ->assertSessionHas('error');

    $this->assertDatabaseHas('regimens', ['id' => $regimen->id]);
});

test('el formulario de empleados muestra los regímenes del catálogo', function () {
    Regimen::create(['nombre' => 'CATALOGO TEST', 'estado' => true]);

    $html = $this->actingAs($this->admin)->get('/employees/create')->assertOk()->getContent();

    expect($html)->toContain('CATALOGO TEST')
        ->not->toContain('@foreach');
});

test('la edición de empleados marca los regímenes fuera de catálogo', function () {
    $employee = Employee::create([
        'nombres' => 'Luis',
        'apellidos' => 'Ramos',
        'dni' => '11223344',
        'email' => 'luis@example.com',
        'regimen_laboral' => 'REGIMEN VIEJO',
    ]);

    $html = $this->actingAs($this->admin)->get("/employees/{$employee->id}/edit")->assertOk()->getContent();

    expect($html)->toContain('REGIMEN VIEJO (no está en el catálogo)')
        ->not->toContain('@foreach');
});
