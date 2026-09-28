document.addEventListener('DOMContentLoaded', function() {
    // Pension fields
    const regimenSelect = document.getElementById('regimen_pensionario');
    const afpEspecifica = document.getElementById('afp_especifica');
    const afpTipoComision = document.getElementById('afp_tipo_comision');
    const afpTieneJubilacion = document.getElementById('afp_tiene_jubilacion');
    const percentageDisplay = document.getElementById('afp_percentage_display');
    
    const afpEspecificaGroup = document.getElementById('afp_especifica_group');
    const afpTipoComisionGroup = document.getElementById('afp_tipo_comision_group');
    const afpTieneJubilacionGroup = document.getElementById('afp_tiene_jubilacion_group');
    
    const afpComisiones = {
        'HABITAT': 1.47,
        'INTEGRA': 1.55,
        'PRIMA': 1.60,
        'PROFUTURO': 1.69
    };
    
    // Asignacion familiar fields
    const tieneAsignacion = document.getElementById('tiene_asignacion_familiar');
    const asignacionPorcentaje = document.getElementById('asignacion_familiar_porcentaje');
    const asignacionMonto = document.getElementById('asignacion_familiar_monto');
    const asignacionDisplay = document.getElementById('asignacion_display');
    const asignacionGroup = document.getElementById('asignacion_porcentaje_group');
    const asignacionMontoGroup = document.getElementById('asignacion_monto_group');
    
    // Renta quinta fields
    const aplicaRentaQuinta = document.getElementById('aplica_renta_quinta');
    const rentaPorcentaje = document.getElementById('renta_quinta_porcentaje');
    const rentaUit = document.getElementById('renta_quinta_uit');
    const rentaDisplay = document.getElementById('renta_display');
    const rentaGroup = document.getElementById('renta_quinta_group');
    const rentaPorcentajeGroup = document.getElementById('renta_porcentaje_group');
    const rentaUitGroup = document.getElementById('renta_uit_group');
    const rentaAnualDisplay = document.getElementById('renta_anual');
    const rentaBaseDisplay = document.getElementById('renta_base');
    
    // Sueldo
    const sueloBasico = document.getElementById('sueldo_basico');
    
    const UIT = 38500;
    const SUELDO_MINIMO = 2750;
    
    function calcularAFP() {
        const regimen = regimenSelect ? regimenSelect.value : 'AFP';
        
        if (regimen === 'ONP') {
            if (afpEspecificaGroup) afpEspecificaGroup.style.display = 'none';
            if (afpTipoComisionGroup) afpTipoComisionGroup.style.display = 'none';
            if (afpTieneJubilacionGroup) afpTieneJubilacionGroup.style.display = 'none';
            if (percentageDisplay) percentageDisplay.textContent = '13.00%';
            return;
        }
        
        if (regimen === 'LICENCIA') {
            if (afpEspecificaGroup) afpEspecificaGroup.style.display = 'none';
            if (afpTipoComisionGroup) afpTipoComisionGroup.style.display = 'none';
            if (afpTieneJubilacionGroup) afpTieneJubilacionGroup.style.display = 'none';
            if (percentageDisplay) percentageDisplay.textContent = '0.00%';
            return;
        }
        
        if (afpEspecificaGroup) afpEspecificaGroup.style.display = '';
        if (afpTipoComisionGroup) afpTipoComisionGroup.style.display = '';
        if (afpTieneJubilacionGroup) afpTieneJubilacionGroup.style.display = '';
        
        const afp = afpEspecifica ? afpEspecifica.value : '';
        const tipoComision = afpTipoComision ? afpTipoComision.value : 'FLUJO';
        const tieneJub = afpTieneJubilacion ? afpTieneJubilacion.checked : false;
        
        if (!afp) {
            if (percentageDisplay) percentageDisplay.textContent = '0.00%';
            return;
        }
        
        let porcentaje = 10;
        if (!tieneJub) {
            porcentaje += 1.37;
        }
        if (tipoComision === 'FLUJO') {
            porcentaje += (afpComisiones[afp] || 0);
        }
        
        if (percentageDisplay) {
            percentageDisplay.textContent = porcentaje.toFixed(2) + '%';
        }
    }
    
    function calcularAsignacionFamiliar() {
        const tiene = tieneAsignacion ? tieneAsignacion.checked : false;
        
        if (!tiene) {
            if (asignacionPorcentaje) asignacionPorcentaje.disabled = true;
            if (asignacionMonto) asignacionMonto.disabled = true;
            if (asignacionGroup) asignacionGroup.style.display = 'none';
            if (asignacionMontoGroup) asignacionMontoGroup.style.display = 'none';
            if (asignacionDisplay) asignacionDisplay.textContent = 'S/. 0.00';
            return;
        }
        
        if (asignacionPorcentaje) asignacionPorcentaje.disabled = false;
        if (asignacionMonto) asignacionMonto.disabled = false;
        if (asignacionGroup) asignacionGroup.style.display = '';
        if (asignacionMontoGroup) asignacionMontoGroup.style.display = '';
        
        const pct = parseFloat(asignacionPorcentaje ? asignacionPorcentaje.value : 0) || 0;
        const mto = parseFloat(asignacionMonto ? asignacionMonto.value : 0) || 0;
        const monto = (pct / 100) * mto;
        
        if (asignacionDisplay) {
            asignacionDisplay.textContent = 'S/. ' + monto.toFixed(2);
        }
    }
    
    function calcularRentaQuinta() {
        const basico = parseFloat(sueloBasico ? sueloBasico.value : 0) || 0;
        
        if (basico < SUELDO_MINIMO) {
            if (aplicaRentaQuinta) aplicaRentaQuinta.checked = false;
            if (aplicaRentaQuinta) aplicaRentaQuinta.disabled = true;
            if (rentaPorcentajeGroup) rentaPorcentajeGroup.classList.add('hidden');
            if (rentaUitGroup) rentaUitGroup.classList.add('hidden');
            if (rentaDisplay) {
                rentaDisplay.classList.add('hidden');
                rentaDisplay.textContent = 'S/. 0.00';
            }
            if (rentaAnualDisplay) rentaAnualDisplay.textContent = '0';
            if (rentaBaseDisplay) rentaBaseDisplay.textContent = '0';
            return;
        }
        
        if (aplicaRentaQuinta) aplicaRentaQuinta.disabled = false;
        
        const aplica = aplicaRentaQuinta ? aplicaRentaQuinta.checked : false;
        
        if (!aplica) {
            if (rentaPorcentajeGroup) rentaPorcentajeGroup.classList.add('hidden');
            if (rentaUitGroup) rentaUitGroup.classList.add('hidden');
            if (rentaDisplay) {
                rentaDisplay.classList.add('hidden');
                rentaDisplay.textContent = 'S/. 0.00';
            }
            if (rentaAnualDisplay) rentaAnualDisplay.textContent = '0';
            if (rentaBaseDisplay) rentaBaseDisplay.textContent = '0';
            return;
        }
        
        if (rentaPorcentajeGroup) rentaPorcentajeGroup.classList.remove('hidden');
        if (rentaUitGroup) rentaUitGroup.classList.remove('hidden');
        if (rentaDisplay) rentaDisplay.classList.remove('hidden');
        
        const pct = parseFloat(rentaPorcentaje ? rentaPorcentaje.value : 8) || 8;
        const uit = parseFloat(rentaUit ? rentaUit.value : UIT) || UIT;
        
        const ingresoAnual = (basico * 12) + (basico * 2);
        const baseDisponible = ingresoAnual - uit;
        
        if (baseDisponible <= 0) {
            if (rentaAnualDisplay) rentaAnualDisplay.textContent = ingresoAnual.toFixed(2);
            if (rentaBaseDisplay) rentaBaseDisplay.textContent = '0 (exonerado)';
            if (rentaDisplay) rentaDisplay.textContent = 'S/. 0.00';
            return;
        }
        
        const tasa = baseDisponible * (pct / 100);
        const quinta = tasa / 12;
        
        if (rentaAnualDisplay) rentaAnualDisplay.textContent = ingresoAnual.toFixed(2);
        if (rentaBaseDisplay) rentaBaseDisplay.textContent = baseDisponible.toFixed(2);
        if (rentaDisplay) rentaDisplay.textContent = 'S/. ' + quinta.toFixed(2);
    }
    
    // Event listeners - Pension
    if (regimenSelect) regimenSelect.addEventListener('change', calcularAFP);
    if (afpEspecifica) afpEspecifica.addEventListener('change', calcularAFP);
    if (afpTipoComision) afpTipoComision.addEventListener('change', calcularAFP);
    if (afpTieneJubilacion) afpTieneJubilacion.addEventListener('change', calcularAFP);
    
    // Event listeners - Asignacion Familiar
    if (tieneAsignacion) tieneAsignacion.addEventListener('change', calcularAsignacionFamiliar);
    if (asignacionPorcentaje) asignacionPorcentaje.addEventListener('input', calcularAsignacionFamiliar);
    if (asignacionMonto) asignacionMonto.addEventListener('input', calcularAsignacionFamiliar);
    
    // Event listeners - Renta Quinta
    if (sueloBasico) {
        sueloBasico.addEventListener('input', calcularRentaQuinta);
        sueloBasico.addEventListener('change', calcularRentaQuinta);
    }
    if (aplicaRentaQuinta) aplicaRentaQuinta.addEventListener('change', calcularRentaQuinta);
    if (rentaPorcentaje) rentaPorcentaje.addEventListener('input', calcularRentaQuinta);
    if (rentaUit) rentaUit.addEventListener('input', calcularRentaQuinta);
    
    // Initial calculations
    calcularAFP();
    calcularAsignacionFamiliar();
    calcularRentaQuinta();
});