@extends('layouts.dashboard')

@section('title', 'Planillas')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">RRHH</p>
                    <h1 class="text-2xl font-semibold">Planillas</h1>
                </div>
            </div>
            <div class="flex gap-2">
                @if(Auth::user()->isAdmin())
                <a href="{{ route('configurations.index') }}" class="px-4 py-2 bg-amber-600 text-white text-sm font-medium hover:bg-amber-700">
                    <i class="fas fa-cog mr-2"></i>Editar Aportes Empleador
                </a>
                @endif
                <button type="button" onclick="document.getElementById('marcarPagadoModal').showModal()" class="px-4 py-2 bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                    <i class="fas fa-check-circle mr-2"></i>Marcar Período Pagado
                </button>
                <button type="button" onclick="document.getElementById('deletePeriodoModal').showModal()" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Eliminar Período
                </button>
                <a href="{{ route('payrolls.create') }}" class="px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                    <i class="fas fa-plus mr-2"></i>Generar Planilla
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card border border-white/40 p-4">
            <form method="GET" action="{{ route('payrolls.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-stone mb-1">Buscar</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nombre, apellido o DNI..." class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                </div>
                <div>
                    <label class="block text-xs font-medium text-stone mb-1">Período</label>
                    <select name="periodo" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <option value="">Todos</option>
                        @foreach($periodos as $p)
                        <option value="{{ $p }}" {{ $periodo == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-stone mb-1">Régimen</label>
                    <select name="regimen" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <option value="">Todos</option>
                        @foreach($regimenes as $r)
                        <option value="{{ $r }}" {{ $regimen == $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-stone mb-1">Estado</label>
                    <select name="estado" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <option value="">Todos</option>
                        <option value="GENERADA" {{ $estado == 'GENERADA' ? 'selected' : '' }}>Generada</option>
                        <option value="PAGADA" {{ $estado == 'PAGADA' ? 'selected' : '' }}>Pagada</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
            </form>
            <form method="GET" action="{{ route('payrolls.pdfGeneral') }}" target="_blank" class="flex gap-2">
                <input type="hidden" name="periodo" value="{{ $periodo }}">
                <input type="hidden" name="regimen" value="{{ $regimen }}">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-2"></i>PDF General
                </button>
            </form>
            <form method="GET" action="{{ route('payrolls.excelGeneral') }}" class="flex gap-2">
                <input type="hidden" name="periodo" value="{{ $periodo }}">
                <input type="hidden" name="regimen" value="{{ $regimen }}">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                    <i class="fas fa-file-excel mr-2"></i>Excel General
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="glass-card border border-white/40 overflow-x-auto">
            <table class="w-full min-w-[1200px]">
                <thead class="bg-stone/5">
                    <tr class="text-left text-xs font-medium text-stone uppercase tracking-wider">
                        <th class="px-4 py-3">Período</th>
                        <th class="px-4 py-3">Empleado</th>
                        <th class="px-4 py-3">Régimen</th>
                        <th class="px-4 py-3">Sueldo</th>
                        <th class="px-4 py-3">Asig.Fam</th>
                        <th class="px-4 py-3">Bonif.Rem</th>
                        <th class="px-4 py-3">Tard/Faltas</th>
                        <th class="px-4 py-3">Rem.Bruta</th>
                        <th class="px-4 py-3">AFP/ONP</th>
                        <th class="px-4 py-3">Bonif.NoRem</th>
                        <th class="px-4 py-3">Rent.5ta</th>
                        <th class="px-4 py-3">Neto</th>
                        <th class="px-4 py-3">Aportes Emp.</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/10">
                    @forelse($payrolls as $p)
                    <tr class="hover:bg-stone/5">
                        <td class="px-4 py-3 font-mono">{{ $p->periodo }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium">{{ $p->employee->nombres }} {{ $p->employee->apellidos }}</div>
                            <div class="text-xs text-stoneLight">{{ $p->employee->dni }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs">{{ $p->employee->regimen_laboral }}</td>
                        <td class="px-4 py-3">S/. {{ number_format($p->sueldo_basico, 2) }}</td>
                        <td class="px-4 py-3 text-blue-600">S/. {{ number_format($p->asignacion_familiar, 2) }}</td>
                        <td class="px-4 py-3 text-green-600">S/. {{ number_format($p->bonificaciones_remunerativas ?? 0, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($p->tardanzas > 0 || $p->faltas > 0)
                            <div class="text-xs text-stoneLight">{{ $p->tardanzas }}min / {{ $p->faltas }}faltas</div>
                            <div class="text-red-600 text-xs">-S/. {{ number_format($p->monto_tardanzas + $p->monto_faltas, 2) }}</div>
                            @else
                            <span class="text-stoneLight">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">S/. {{ number_format($p->remuneracion_bruta, 2) }}</td>
                        <td class="px-4 py-3">
                            <div>{{ $p->sistema_pensiones }}</div>
                            <div class="text-xs text-stoneLight">
                                @if($p->sistema_pensiones === 'ONP')
                                    (13%)
                                @elseif($p->sistema_pensiones === 'NO_APORTE')
                                    (0%)
                                @else
                                    ({{ number_format($p->retencion_pensiones / $p->remuneracion_bruta * 100, 2) }}%)
                                @endif
                            </div>
                            <div class="text-xs text-red-600">-S/. {{ number_format($p->retencion_pensiones, 2) }}</div>
                        </td>
                        <td class="px-4 py-3 text-blue-600">S/. {{ number_format($p->bonificaciones_no_remunerativas ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-red-600">
                            @if($p->renta_quinta > 0)
                            -S/. {{ number_format($p->renta_quinta, 2) }}
                            @else
                            <span class="text-stoneLight">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-bold">S/. {{ number_format($p->neto_pagar, 2) }}</td>
                        <td class="px-4 py-3 text-amber-600">
                            <div class="text-xs">Essalud: S/. {{ number_format($p->essalud, 2) }}</div>
                            @if($p->sctr_salud > 0 || $p->sctr_pensiones > 0)
                            <div class="text-xs">SCTR: S/. {{ number_format($p->sctr_salud + $p->sctr_pensiones, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @switch($p->estado)
                                @case('GENERADA')
                                <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium">Generada</span>
                                @break
                                @case('PAGADA')
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Pagada</span>
                                @break
                                @case('ANULADA')
                                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Anulada</span>
                                @break
                            @endswitch
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('payrolls.boleta', $p->id) }}" target="_blank" class="text-red-600 hover:text-red-800" title="Boleta PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('payrolls.show', $p->id) }}" class="text-stone hover:text-jet" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($p->estado === 'GENERADA')
                                <form action="{{ route('payrolls.marcarPagado', $p->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Marcar Pagado" onclick="return confirm('¿Marcar como pagado?')">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('payrolls.destroy', $p->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar" onclick="return confirm('¿Eliminar?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-center text-stoneLight">No hay planillas generadas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payrolls->hasPages())
        <div class="flex justify-center">
            {{ $payrolls->links() }}
        </div>
        @endif
    </div>

    <!-- Marcar Periodo Pagado Modal -->
    <dialog id="marcarPagadoModal" class="modal p-6 bg-white rounded-lg shadow-xl">
        <h3 class="text-lg font-semibold mb-4">Marcar Período como Pagado</h3>
        <form method="POST" action="{{ route('payrolls.marcarPeriodoPagado') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-stone mb-1">Período</label>
                    <select name="periodo" required class="w-full px-3 py-2 border border-stone/30">
                        <option value="">Seleccionar...</option>
                        @foreach($periodos as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-stone mb-1">Régimen (opcional)</label>
                    <select name="regimen" class="w-full px-3 py-2 border border-stone/30">
                        <option value="todos">Todos los regímenes</option>
                        @foreach($regimenes as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('marcarPagadoModal').close()" class="px-4 py-2 border border-stone/30 text-stone">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white" onclick="return confirm('¿Marcar como pagadas las planillas seleccionadas?')">Marcar Pagado</button>
                </div>
            </div>
        </form>
    </dialog>

    <!-- Delete Periodo Modal -->
    <dialog id="deletePeriodoModal" class="modal p-6 bg-white rounded-lg shadow-xl">
        <h3 class="text-lg font-semibold mb-4">Eliminar Planillas por Período</h3>
        <form method="POST" action="{{ route('payrolls.destroyPeriodo') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-stone mb-1">Período</label>
                    <select name="periodo" required class="w-full px-3 py-2 border border-stone/30">
                        <option value="">Seleccionar...</option>
                        @foreach($periodos as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('deletePeriodoModal').close()" class="px-4 py-2 border border-stone/30 text-stone">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white" onclick="return confirm('¿Eliminar todas las planillas de este período?')">Eliminar</button>
                </div>
            </div>
        </form>
    </dialog>
@endsection