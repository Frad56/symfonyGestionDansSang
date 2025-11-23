class DonsTable {
    constructor(tableElement) {
        this.table = tableElement;
        this.init();
    }

    init() {
        this.addBadges();
        this.addSearch();
    }

    addBadges() {
        const cells = this.table.querySelectorAll('td:nth-child(4)');
        cells.forEach(cell => {
            const isApte = cell.textContent.trim().toLowerCase() === 'true' || 
                           cell.textContent.trim().toLowerCase() === 'oui';
            cell.innerHTML = `<span class="apte-badge ${isApte}">${isApte ? 'Apte' : 'Inapte'}</span>`;
        });
    }

    addSearch() {
        const container = this.table.closest('.dons-container');
        const searchDiv = document.createElement('div');
        searchDiv.innerHTML = `
            <input type="text" placeholder="Rechercher..." class="search-input" style="margin:10px 0; padding:8px; border-radius:5px; border:1px solid #ffcccc;">
        `;
        container.insertBefore(searchDiv, this.table);

        const searchInput = searchDiv.querySelector('.search-input');
        searchInput.addEventListener('input', (e) => {
            const search = e.target.value.toLowerCase();
            this.table.querySelectorAll('tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.dons-table');
    if (table) new DonsTable(table);
});
