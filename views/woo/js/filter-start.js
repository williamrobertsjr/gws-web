
const toolFilterStart = document.getElementById('tool-filter-start');
const toolFilterSelect = document.getElementById('tool-filter-select'); 

// Show select when Tool Filter is clicked
    document.getElementById('tool-filter-btn').addEventListener('click', function(e) {
        console.log('tool filter button selected')
        e.preventDefault();
        toolFilterStart.classList.remove('hidden');
        
        // Smooth scroll to the select
        toolFilterStart.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

toolFilterSelect.addEventListener('change', function() {
        console.log('select changed')
        const selectedValue = this.value;
        console.log(selectedValue)
        // Don't navigate if the default option is selected
        if (selectedValue && selectedValue.startsWith('/')) {
            // Show spinner
            document.getElementById('tool-filter-spinner').classList.remove('hidden');
            
            // Disable select to prevent multiple clicks
            this.disabled = true;
            this.classList.add('opacity-75');
            
            // Navigate to the selected page
            window.location.href = selectedValue;
        }
    });