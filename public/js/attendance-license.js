document.addEventListener('DOMContentLoaded', function() {
    const tipoLicenciaSelect = document.querySelector('select[name="tipo_licencia"]');
    const fechaFinLicenciaInput = document.querySelector('input[name="fecha_fin_licencia"]');
    const fechaInput = document.querySelector('input[name="fecha"]');
    const turnoSelect = document.querySelector('select[name="turno"]');
    const horaEntradaInput = document.querySelector('input[name="hora_entrada"]');
    const horaSalidaInput = document.querySelector('input[name="hora_salida"]');

    function toggleLicenseFields() {
        const hasLicense = tipoLicenciaSelect && tipoLicenciaSelect.value !== '';

        if (turnoSelect) {
            turnoSelect.required = !hasLicense;
            turnoSelect.closest('div').style.opacity = hasLicense ? '0.5' : '1';
        }

        if (horaEntradaInput) {
            horaEntradaInput.required = !hasLicense;
            horaEntradaInput.closest('div').style.opacity = hasLicense ? '0.5' : '1';
            if (hasLicense) {
                horaEntradaInput.value = '';
            }
        }

        if (horaSalidaInput) {
            horaSalidaInput.required = false;
            horaSalidaInput.closest('div').style.opacity = hasLicense ? '0.5' : '1';
            if (hasLicense) {
                horaSalidaInput.value = '';
            }
        }

        if (fechaFinLicenciaInput) {
            fechaFinLicenciaInput.required = hasLicense;
            fechaFinLicenciaInput.closest('div').style.opacity = hasLicense ? '1' : '0.5';
            if (hasLicense && fechaInput) {
                fechaFinLicenciaInput.min = fechaInput.value;
            }
        }
    }

    if (tipoLicenciaSelect) {
        tipoLicenciaSelect.addEventListener('change', toggleLicenseFields);
    }

    if (fechaInput && fechaFinLicenciaInput) {
        fechaInput.addEventListener('change', function() {
            if (tipoLicenciaSelect && tipoLicenciaSelect.value !== '') {
                fechaFinLicenciaInput.min = fechaInput.value;
            }
        });
    }

    const form = document.getElementById('attendanceForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const hasLicense = tipoLicenciaSelect && tipoLicenciaSelect.value !== '';

            if (hasLicense && fechaFinLicenciaInput && fechaInput) {
                const fecha = new Date(fechaInput.value);
                const fechaFin = new Date(fechaFinLicenciaInput.value);

                if (fechaFin < fecha) {
                    e.preventDefault();
                    alert('La fecha fin de licencia debe ser igual o posterior a la fecha de inicio.');
                    fechaFinLicenciaInput.focus();
                    return;
                }
            }
        });
    }

    toggleLicenseFields();
});