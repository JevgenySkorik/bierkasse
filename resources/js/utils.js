
document.enableRemoving = function() {
    const checkboxes = document.querySelectorAll('input[type=checkbox]');
    const isDisabled= checkboxes[0].disabled;
    checkboxes.forEach((el) => {
        if(isDisabled) {
            el.disabled = false;
        }
        else {
            el.checked = false;
            el.disabled = true;
        }
    });
}

document.sortDebts = function() {
    const detailsList = Array.from(document.querySelectorAll('details'));
        const sortDirection = localStorage.getItem('sortDirection');
        detailsList.sort((a, b) => {
            const aText = a.querySelector('.debt-value')?.textContent || '';
            const bText = b.querySelector('.debt-value')?.textContent || '';

            const aValue = parseFloat(aText.split('€')[1]?.trim() || 0);
            const bValue = parseFloat(bText.split('€')[1]?.trim() || 0);
            
            if(sortDirection === 'asc') {
                
                return bValue - aValue;
            }
            else {

                return aValue - bValue;
            }
        });
        localStorage.setItem('sortDirection', sortDirection === 'asc' ? 'desc' : 'asc');
        // Re-insert sorted elements
        const parent = detailsList[0].parentElement;
        detailsList.forEach(el => parent.appendChild(el));
}