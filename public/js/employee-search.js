document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('employeeSearch');
    const searchResults = document.getElementById('searchResults');
    const employeeIdInput = document.getElementById('employee_id');
    const selectedEmployee = document.getElementById('selectedEmployee');
    const selectedEmployeeName = document.getElementById('selectedEmployeeName');
    const selectedEmployeeDni = document.getElementById('selectedEmployeeDni');
    
    if (!searchInput) return;
    
    let debounceTimer;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value;
        
        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }
        
        debounceTimer = setTimeout(() => {
            fetch('/attendances/search?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(employees => {
                    searchResults.innerHTML = '';
                    if (employees.length === 0) {
                        searchResults.innerHTML = '<div class="p-2 text-stoneLight">No se encontraron empleados</div>';
                        searchResults.classList.remove('hidden');
                        return;
                    }
                    
                    employees.forEach(emp => {
                        const div = document.createElement('div');
                        div.className = 'p-2 hover:bg-stone/10 cursor-pointer';
                        div.innerHTML = '<div class="font-medium">' + emp.nombres + ' ' + emp.apellidos + '</div><div class="text-xs text-stoneLight">DNI: ' + emp.dni + '</div>';
                        div.addEventListener('click', function() {
                            employeeIdInput.value = emp.id;
                            selectedEmployeeName.textContent = emp.nombres + ' ' + emp.apellidos;
                            selectedEmployeeDni.textContent = 'DNI: ' + emp.dni;
                            selectedEmployee.classList.remove('hidden');
                            searchResults.classList.add('hidden');
                            searchInput.value = '';
                        });
                        searchResults.appendChild(div);
                    });
                    searchResults.classList.remove('hidden');
                });
        }, 300);
    });
    
    document.addEventListener('click', function(e) {
        if (searchInput && searchResults && !searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
});