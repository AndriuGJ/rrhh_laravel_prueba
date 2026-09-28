document.addEventListener('DOMContentLoaded', function() {
    const selectAllVisible = document.getElementById('selectAllVisible');
    const regimenFilter = document.getElementById('regimenFilter');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const clearAllBtn = document.getElementById('clearAll');
    const selectedCount = document.getElementById('selectedCount');
    
    let employees = [];
    
    function getCheckboxes() {
        return document.querySelectorAll('.employee-checkbox');
    }
    
    function getRows() {
        return document.querySelectorAll('.employee-row');
    }
    
    function updateCount() {
        const checked = document.querySelectorAll('.employee-checkbox:checked').length;
        if (selectedCount) {
            selectedCount.textContent = checked;
        }
    }
    
    function selectVisibleOnly() {
        const regimen = regimenFilter ? regimenFilter.value : '';
        const rows = getRows();
        
        rows.forEach(row => {
            const checkbox = row.querySelector('.employee-checkbox');
            if (regimen === '' || row.dataset.regimen === regimen) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        });
        
        updateCount();
    }
    
    function markEmployee(id) {
        const checkbox = document.querySelector(`.employee-checkbox[value="${id}"]`);
        if (checkbox) {
            checkbox.checked = true;
            checkbox.closest('.employee-row').scrollIntoView({ behavior: 'smooth', block: 'center' });
            updateCount();
        }
    }
    
    if (regimenFilter) {
        regimenFilter.addEventListener('change', function() {
            selectVisibleOnly();
        });
    }
    
    if (selectAllVisible) {
        selectAllVisible.addEventListener('change', function() {
            selectVisibleOnly();
        });
    }
    
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            getCheckboxes().forEach(cb => cb.checked = false);
            if (regimenFilter) regimenFilter.value = '';
            if (searchInput) searchInput.value = '';
            updateCount();
        });
    }
    
    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }
            
            const rows = getRows();
            const matches = [];
            
            rows.forEach(row => {
                const name = row.dataset.name || '';
                const dni = row.dataset.dni || '';
                
                if (name.includes(query) || dni.includes(query)) {
                    matches.push({
                        id: row.querySelector('.employee-checkbox').value,
                        name: row.cells[1].textContent,
                        dni: dni,
                        regimen: row.dataset.regimen
                    });
                }
            });
            
            if (matches.length > 0 && matches.length <= 10) {
                searchResults.innerHTML = matches.map(m => 
                    `<div class="px-3 py-2 hover:bg-stone/10 cursor-pointer border-b border-stone/10 last:border-0" data-id="${m.id}">
                        <div class="font-medium text-sm">${m.name}</div>
                        <div class="text-xs text-stoneLight">DNI: ${m.dni} - ${m.regimen}</div>
                    </div>`
                ).join('');
                searchResults.classList.remove('hidden');
            } else {
                searchResults.classList.add('hidden');
            }
        });
        
        searchResults.addEventListener('click', function(e) {
            const item = e.target.closest('[data-id]');
            if (item) {
                markEmployee(item.dataset.id);
                searchInput.value = '';
                searchResults.classList.add('hidden');
            }
        });
        
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    }
    
    getCheckboxes().forEach(cb => {
        cb.addEventListener('change', updateCount);
    });
    
    updateCount();
});