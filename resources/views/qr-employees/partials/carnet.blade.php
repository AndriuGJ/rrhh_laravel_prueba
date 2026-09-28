<div class="carnet">
    <div class="carnet-body">
        <img class="carnet-qr" src="{{ $qrDataUri }}" alt="QR {{ $employee->dni }}">
        <div class="carnet-datos">
            <p><span>Nombre</span>{{ $employee->nombres }}</p>
            <p><span>Apellidos</span>{{ $employee->apellidos }}</p>
            <p><span>Régimen laboral</span>{{ $employee->regimen_laboral ?? '-' }}</p>
            <p><span>Cargo</span>{{ $employee->cargo ?? '-' }}</p>
        </div>
    </div>
    <div class="carnet-empresa">{{ \App\Support\Empresa::nombre() }}</div>
</div>

<style>
    .carnet {
        width: 5.5cm;
        height: 8.5cm;
        box-sizing: border-box;
        background: #ffffff;
        border: 1px solid #d6d3d1;
        display: flex;
        flex-direction: column;
        padding: 0.35cm;
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #1c1917;
        overflow: hidden;
    }
    .carnet-body {
        flex: 1;
        display: flex;
        gap: 0.28cm;
        align-items: flex-start;
        min-height: 0;
    }
    .carnet-qr {
        width: 2.35cm;
        height: 2.35cm;
        flex-shrink: 0;
        display: block;
    }
    .carnet-datos {
        flex: 1;
        min-width: 0;
        font-size: 7pt;
        line-height: 1.45;
    }
    .carnet-datos p {
        margin: 0 0 0.16cm;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .carnet-datos span {
        display: block;
        font-size: 5.5pt;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #78716c;
    }
    .carnet-empresa {
        text-align: center;
        font-size: 7pt;
        font-weight: 700;
        border-top: 1px solid #d6d3d1;
        padding-top: 0.16cm;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
</style>
