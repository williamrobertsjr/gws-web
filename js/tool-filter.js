let toolTypeSelect = document.getElementById('tool-type-select')
let subTypeContainer = document.getElementById('sub-type-container')
let filtersContainer = document.getElementById('filters-container')
let resultsContainer = document.getElementById('results-container')
let table = null



let clearSubSubTypeSelect = () => {
    let subSubTypeSelect = document.getElementById('subSubTypeSelect');
    let subSubTypeLabel = document.getElementById('subSubTypeLabel')
    if (subSubTypeSelect) {
        subSubTypeSelect.remove();
        subSubTypeLabel.remove();
    }
}


// let fetchTableData = (toolType, subType, subSubType, selectedValues) => {
//     let queryParams = new URLSearchParams({
//         toolType: toolType,
//         // subType: subType,
//     });
//     if (subType) queryParams.set('subType', subType);
//     if (subSubType) queryParams.set('subSubType', subSubType);
//     // Add the selected filter values to query parameters, excluding any 'default' values
//     if (selectedValues) {
//         Object.entries(selectedValues).forEach(([fieldName, value]) => {
//             if (value !== 'default') {
//                 queryParams.set(fieldName, value);
//             }
//         });
//     }
   
    
//     let fetchUrl = `/wp-content/themes/gws-web/page-tool-filter.php?ajax=getTableData&${queryParams}`;
//     console.log(`Fetching with this url: ${fetchUrl}`)
//     fetch(fetchUrl)
//         .then(response => response.json())
//         .then(response => {
//             const data = response.data;
//             buildDataTable(data, response.toolType, response.subType, response.subSubType);
//         })
//         .catch(error => console.error('Error:', error));
// };

let fetchTableData = (toolType, subType, subSubType, selectedValues) => {
    // Before starting the fetch, show the loading animation
    resultsContainer.innerHTML = '<div id="loading-animation" class="ps-10"><h3 class="text-2xl font-semibold uppercase">Searching For Parts...</h3><img src="/wp-content/uploads/2024/02/box_loader.gif" alt="Loading..."></div>';

    let queryParams = new URLSearchParams({
        toolType: toolType,
    });
    if (subType) queryParams.set('subType', subType);
    if (subSubType) queryParams.set('subSubType', subSubType);
    if (selectedValues) {
        Object.entries(selectedValues).forEach(([fieldName, value]) => {
            if (value !== 'default') {
                queryParams.set(fieldName, value);
            }
        });
    }

    let fetchUrl = `/wp-content/themes/gws-web/page-tool-filter.php?ajax=getTableData&${queryParams}`;
    console.log(`Fetching with this url: ${fetchUrl}`);
    fetch(fetchUrl)
        .then(response => response.json())
        .then(response => {
            // Once the data is ready, hide the loading animation before building the table
            const loadingAnimation = document.getElementById('loading-animation');
            if (loadingAnimation) {
                loadingAnimation.style.display = 'none'; // Or use loadingAnimation.remove(); to remove it from the DOM
            }
            const data = response.data;
            buildDataTable(data, response.toolType, response.subType, response.subSubType);
        })
        .catch(error => {
            console.error('Error:', error);
            // Hide the loading animation even if there's an error
            const loadingAnimation = document.getElementById('loading-animation');
            if (loadingAnimation) {
                loadingAnimation.style.display = 'none'; // Or use loadingAnimation.remove(); to remove it from the DOM
            }
        });
};


// Function to build DataTable with received data
let buildDataTable = (data, toolType, subType, subSubType) => {
    if (table) {
        table.destroy();
        resultsContainer.innerHTML = '<p>Select your criteria and press search to find a tool.</p>';

    }

    // Check if data is empty or not structured correctly
    if (!data || !data.length || typeof data[0] !== 'object') {
        console.error('Invalid or empty data received for DataTable');
        // Display a custom message in the results container
        resultsContainer.innerHTML = `<div><p class="no-data-message text-2xl">Can't find what you're looking for? Let us make it for you.</p><div class="mt-6"><button class="btn light-blue"><a href="/custom-quote">Request a Custom Tool</a></button></div></div>`;
        return;
    }

    // Dynamically create columns based on the keys of the first object in the data array
    // Generate columns dynamically and add custom rendering for 'part' and 'series'
    let columns = Object.keys(data[0]).map(key => {
        // Common render function to handle empty/null values
        const renderWithEmptyCheck = (data, type, row) => {
            if (!data) return '-'; // If data is null or empty, return '-'
    
            // Specific formatting for 'Part' and 'Series' columns
            if (key === 'Part') {
                return `<a class="font-bold text-dark-blue" href="/product/?part=${data}" target="_blank">${data}</a>`;
            } else if (key === 'Series') {
                return `<a class="font-bold text-dark-blue" href="/series-${data}" target="_blank">${data}</a>`;
            }
    
            // Return data as is for other columns
            return data;
        };
    
        // Return column definition
        return { title: key, data: key, render: renderWithEmptyCheck };
});
    

    const newTable = document.createElement('table');
    newTable.id = 'results-table';
    newTable.classList.add('stripe', 'row-border', 'cell-border', 'hover');

    // Function to format class name by replacing spaces with hyphens
    function formatClassName(name) {
        return name.replace(/\s+/g, '-');
    }

    // Add toolType, subType, and subSubType as class names if they exist
    if (toolType) newTable.classList.add(formatClassName(toolType));
    if (subType) newTable.classList.add(formatClassName(subType));
    if (subSubType) newTable.classList.add(formatClassName(subSubType));

    resultsContainer.appendChild(newTable);

    // Initialize DataTable with dynamic columns
    table = new DataTable('#results-table', {
        columns: columns,
        autoWidth: false,
        searching: false,
        lengthChange: false,
        pageLength: 20,
        paging: true,
        ordering: false,
        data: data // Pass the data to DataTable
    });
    console.log(data)
    console.log('building table')
    updateDataNames(); // update table headings
}


let fetchFilters = (toolType, subType, subSubType) => {
    console.log(`fetched ${subType} filters!`)
    filtersContainer.innerHTML = '';
    // Check if subSubType is provided and not null or undefined
    let hasSubSubType = subSubType !== null && subSubType !== undefined;

    // Construct the URL with conditional subSubType
    let fetchUrl = `/wp-content/themes/gws-web/page-tool-filter.php?ajax=getFilters&toolType=${toolType}&subType=${subType}`;
    if (hasSubSubType) {
        fetchUrl += `&subSubType=${subSubType}`;
    }

    console.log(`Fetching filters with URL: ${fetchUrl}`);

    // Fetch request
    fetch(fetchUrl)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            
            Object.entries(data.distinctValues).forEach(([field, values]) => {
                // Create a select element for the field
                const div = document.createElement('div')
                div.id = `${field}-select-div`
                const select = document.createElement('select');
                const label = document.createElement('label')
                select.id = `${field}-select`;
                label.id = `${field}-label`
                label.htmlFor = `${field}-select`
                label.textContent = `${field}`
                select.classList.add('mt-2', 'block', 'w-full', 'rounded-md','border-0','py-1.5','pl-3','pr-10','text-gray-900','ring-1','ring-inset','ring-gray-300','focus:ring-2','focus:ring-indigo-600','sm:text-sm','sm:leading-6');
                label.classList.add('block', 'text-sm', 'font-semibold', 'leading-6', 'text-pale-blue', 'mt-2')

                // Add a default option
                const defaultOption = document.createElement('option');
                defaultOption.value = 'default';
                defaultOption.textContent = 'Any';
                select.appendChild(defaultOption);

                // Add options to the select element
                values.forEach(valueObject => {
                    const option = document.createElement('option');
                    const value = valueObject[field]; // Extract the value from the valueObject
                    option.value = value;
                    option.textContent = value;
                    select.appendChild(option);
                });
            
                // Append the select element to your filters container
                div.appendChild(label)
                div.appendChild(select);
                filtersContainer.appendChild(div)
            })
        })
        .then(data => {
            // Function to format class name by replacing spaces with hyphens
            function formatClassName(name) {
                return name.replace(/\s+/g, '-');
            }

            // Add toolType, subType, and subSubType as class names if they exist
            if (toolType) filtersContainer.classList.add(formatClassName(toolType));
            if (subType) filtersContainer.classList.add(formatClassName(subType));
            if (subSubType) filtersContainer.classList.add(formatClassName(subSubType));
            
            updateFilterNames();
            const searchButton = document.createElement('button')
            searchButton.id = 'filter-search-btn'
            searchButton.innerHTML = '<a class="text-md" href="">Search</a>'
            searchButton.classList.add('btn', 'dark-blue', 'me-2', 'mt-4')
            filtersContainer.appendChild(searchButton)

            searchButton.addEventListener('click', (event) => {
                event.preventDefault(); // Prevent default form submission behavior
            
                // Capture selected values and corresponding field names
                const selectedValues = {};
                const selects = filtersContainer.querySelectorAll('select');
                selects.forEach(select => {
                    let fieldName = select.id.replace('-select', '');
                    if (select.value !== 'default') {
                        selectedValues[fieldName] = select.value;
                    }
                });

                // Call the fetchTableData function
                fetchTableData(toolType, subType, subSubType, selectedValues);
            });
            
                 

        })
        .catch(error => console.error('Error:', error));
        
}

let fetchSubSubTypes = (toolType, subType) => {
    console.log(toolType, subType)
    
    // Fetch request to get the next set of options based on the tool type and sub type
    fetch(`/wp-content/themes/gws-web/page-tool-filter.php?ajax=getSubSubType&toolType=${toolType}&subType=${subType}`)
        .then(response => response.json())
        .then(data => {
            
            for(let i=0; i < data.length; i++) {
                if ( data.length <= 1) {
                    
                    fetchFilters(toolType, subType);
                    return;
                }
            }
            
            // clear container 
            filtersContainer.innerHTML = ''
           
            // create new select element
            const label = document.createElement('label')
            label.id = 'subSubTypeLabel'
            label.htmlFor = 'subSubTypeSelect'
            label.textContent = `${subType} Sub Type`
            label.classList.add('block', 'text-sm', 'font-semibold', 'leading-6', 'text-pale-blue', 'mt-2')
            subTypeContainer.appendChild(label)
            // create sub_sub_type select
            const select = document.createElement('select')
            select.label = `Sub Sub Type`
            select.id = 'subSubTypeSelect'
            select.name = 'subSubTypeSelect'
            select.classList.add('mt-2', 'block', 'w-full', 'rounded-md','border-0','py-1.5','pl-3','pr-10','text-gray-900','ring-1','ring-inset','ring-gray-300','focus:ring-2','focus:ring-indigo-600','sm:text-sm','sm:leading-6')
            subTypeContainer.appendChild(select)

            // Add a default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Choose one';
            select.appendChild(defaultOption);

            // fill the select with the sub sub types found in the DB table (filters_config)
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.sub_sub_type
                option.textContent = item.sub_sub_type
                select.appendChild(option)
                console.log(item.sub_sub_type)
            })

            // List to the sub sub type select
            select.addEventListener('change', function() {
                let subSubType = this.value
                
                fetchFilters(toolType, subType, subSubType);          
            })
           

        })
        .catch(error => console.error('Error:', error));

}

let fetchSubTypes = toolType => {
    
    fetch(`/wp-content/themes/gws-web/page-tool-filter.php?ajax=getSubTypes&toolType=${toolType}`)
        .then(response => response.json())
        .then(subTypes => {
            let subTypeSelect = document.getElementById('subTypeSelect')
            console.log(subTypes)
            // clear previous content
            subTypeContainer.innerHTML = ''
            filtersContainer.innerHTML = ''
            
            // create new select element
            const label = document.createElement('label')
            label.htmlFor = 'subTypeSelect'
            label.textContent = `Sub Type`
            label.classList.add('block', 'text-sm', 'font-semibold', 'leading-6', 'text-pale-blue', 'mt-2')
            subTypeContainer.appendChild(label)
            const select = document.createElement('select')
            select.label = `${toolType} Sub Type`
            select.id = 'subTypeSelect'
            select.name = 'subTypeSelect'
            select.classList.add('mt-2', 'block', 'w-full', 'rounded-md','border-0','py-1.5','pl-3','pr-10','text-gray-900','ring-1','ring-inset','ring-gray-300','focus:ring-2','focus:ring-indigo-600','sm:text-sm','sm:leading-6')
            subTypeContainer.appendChild(select)

            // Add a default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Choose one';
            select.appendChild(defaultOption);

            // Populate the select with options from subTypes
            subTypes.forEach(subType => {
                const option = document.createElement('option');
                option.value = subType.sub_type; // Replace 'value' with the actual property name
                option.textContent = subType.sub_type // Replace 'label' with the actual property name
                select.appendChild(option);
            });

            // Listen to the sub type select
            select.addEventListener('change', function() {
                let subType = this.value
                clearSubSubTypeSelect()
                fetchSubSubTypes(toolType, subType);
                
            })

        })
        .catch(error => console.error('Error: ', error))
    

}

// Listen to selected tool type value 
toolTypeSelect.addEventListener('change', function() {
    let toolType = this.value // get tool type selected
    resultsContainer.innerHTML = '<p>Select your criteria and press search to find a tool.</p>'
    if (toolType === 'Inserts') {
        subTypeContainer.innerHTML = '' // clear sub type container if there's anything in there
        filtersContainer.innerHTML = '' // clear filters container
        fetchTableData(toolType)// build inserts tool type
    } else {
        subTypeContainer.innerHTML = ''
        fetchSubTypes(toolType); // fetch the sub types and display
    }
})


// Data table heading renaming function

let updateDataNames = () => {
    
    let dataTable = document.getElementById('results-table')
    let dataHeadings = document.querySelectorAll('#results-table th')
    for ( heading of dataHeadings ) {
    
        // Use the textContent or innerHTML of the heading for the switch
        switch (heading.textContent) { // or heading.innerHTML if you need HTML content
           case 'cut_dia_in_display':
           case 'cut_dia_m_display':
           case 'outer_dimension':
               if(dataTable.classList.contains('Threading')) {
                   heading.innerHTML = "Cutter Dia.";
                   break;
               } else {
                   heading.innerHTML = "Diameter";
                   break;
               }
               break;
           case 'shank_dia_in_display':
           case 'shank_dia_m_display':
               heading.innerText = "Shank";
               break;
           case 'oal_in_display':
           case 'oal_m_display':
               heading.innerText = "OAL";
               break;
           case 'loc_in_display':
           case 'loc_m_display':
           case 'flutelength':
               // loc_in_display title is Flute Length for Holemaking products
               if(dataTable.classList.contains('Holemaking')) {
                   heading.innerHTML = "Flute Length";
                   break;
               } else if (dataTable.classList.contains('187') || dataTable.classList.contains('189') || dataTable.classList.contains('189M')) {
                   console.log('hello');
                   heading.innerText= "Neck Len.";
                   break;
               } else {
                   
                   heading.innerHTML = "LOC";
                   break;
               }
               break;
           case 'taps_thread_size':
               if(dataTable.classList.contains('187')) {
                   heading.innerText = "TPI Range";
                   break;
               } else {
                   heading.innerText = "Thread Size";
                   break;
               }
               break;
           case 'taps_thread_limit':
               heading.innerText = "Thread Limit";
               break;
           case 'radius_in_display':
           case 'radius_m_display':
               heading.innerText = "Radius";
               break;
           case 'flutes':
               heading.innerText = "Flutes";
               break;
           case 'tap_taper':
               heading.innerText = "Taper";
               break;
           case 'c2':
               heading.innerText = "Cut Type";
               break;
           case 'tool':
               heading.innerText = "Tool";
               break;
           case 'torx_type':
               heading.innerText = "Torx Type";
               break;
           case 'l1':
               heading.innerText = "L1";
               break;
           case 'l2':
               heading.innerText = "L2";
               break;
           case 'ldr':
               heading.innerText = "LDR";
               break;
           case 'chamfer':
               heading.innerText = "Chamfer";
               break;
           case 'weldon':
               heading.innerText = "Weldon";
               break;
           case 'reach_in_display':
               if(dataTable.classList.contains('Threading')) {
                   heading.innerHTML = "Neck Dia.";
                   break;  
               } else {
                   heading.innerHTML = "LBS";
                   break;
               }
               break;
           case 'step_dia':
               heading.innerHTML = "D";
               break;
           case 'none':
               heading.innerText = "Bright";
               break;
           case 'nf1':
               heading.innerText = "NF1";
               break;
           case 'fx2':
               heading.innerText = "FX2";
               break;
           case 'fx3':
               heading.innerText = "FX3";
               break;
           case 'altin':
               heading.innerText = "AlTiN";
               break;
           case 'tin':
               heading.innerText = "TiN";
               break;
           case 'ticn':
               heading.innerText = "TiCN";
               break;
           case 'coating':
               heading.innerText = "Coating";
               break;
           case 'angle_display':
               heading.innerText = "Incl. Angle";
               break;
           case 'split':
               heading.innerHTML = "Split Length";
               break;
           case 'style':
               heading.innerText = "Style";
               break;
           case 'pilot_dia':
               heading.innerText = "Pilot";
               break;
           case 'thickness':
               heading.innerHTML = "Thickness";
               break;
           case 'width':
               heading.innerText = "Width";
               break;
           case 'grade':
               heading.innerText = "Grade";
               break;
           case 'eco':
               heading.innerText = "PAC Drill Size";
               break;
           // diasize title is Pilot(D1) for some series, while Diameter(D1) for others
           case 'diasize':
               if(dataTable.classList.contains('300')) {
                   heading.innerHTML = 'Pilot <span class="small">Size</span>';
                   break;
               } else {
                   heading.innerHTML = 'Diameter <span class="small">Size</span>';
                   break;
               }
               break;
           case 'diadec':
               if(dataTable.classList.contains('300')) {
                   heading.innerHTML = 'Pilot <span class="small">Dec.</span>';
                   break;
               } else {
                   heading.innerHTML = 'Diameter <span class="small">Dec.</span>';
                   break;
               }
               break;
           case 'eco':
               heading.innerText = "PAC Drill Size";
               break;
           case 'drillpoint':
               heading.innerText = "Drill Point";
               break;
           case 'min_bore':
               heading.innerText = "Minimum Bore";
               break;
           case 'max_bore':
               heading.innerText = "Maximum Bore";
               break;
           case 'projection':
               heading.innerText = "Projection";
               break;
           case 'taps_standard':
               heading.innerText = "Standard";
               break;
           case 'neck_length':
               heading.innerHTML = "Neck Len.";
               break;
           case 'neck_dia_in_display':
               heading.innerHTML = "Neck Dia.";
               break;
           case 'helix':
               heading.innerText = "Helix";
               break;
           case 'taps_chamfer_type':
           case 'taps_go_nogo':
               heading.innerText = "Type";
               break;
           case 'taps_min_tap_drill_size':
               heading.innerText = "Min Tap/Drill Size";
               break;
           case 'taps_max_tap_drill_size':
               heading.innerText = "Max Tap/Drill Size ";
               break;
           case 'taps_pipe_size':
               heading.innerText = "Pipe Size";
               break;
           case 'taps_class_of_fit':
               heading.innerText = "Class of Fit";
               break;
           case 'taps_drill_length':
               heading.innerHTML = "Drill Len.)";
               break;
           case 'taps_thread_length':
               heading.innerHTML = "Thread Len.";
               break;
           case 'taps_od1':
               heading.innerText = "Outside Dia.";
               break;
           case 'taps_tap_size':
           case 'taps_size':
               heading.innerText = "Tap Size";
               break;
           case 'taps_dia':
               heading.innerText = "Tap Dia.(A)";
               break;
           case 'taps_body':
               heading.innerText = "Body Dia.(B)";
               break;
           case 'taps_shank':
               heading.innerText = "Shank(C)";
               break;
           case 'taps_square':
               heading.innerText = "Square Size";
               break;
           case 'taps_depth':
               heading.innerText = "Depth Tap Enters";
               break;
           case 'taps_taps_handle':
               heading.innerText = "Handle";
               break;
           case 'description':
           case 'part_description':
               heading.innerText = "Description";
               break;
           case 'taps_amount':
               heading.innerText = "Amount";
               break;
           case 'pitch_classification':
               heading.innerText = 'Classification';
               break;
           case 'sub_sub_type':
               heading.innerText = 'Type';
               break;
           case 'iso_code':
               heading.innerText = 'ISO Code';
               break;
           default:
               console.log('Unknown heading:', heading.textContent);
       }
   
   }

}

let updateFilterNames = () => {
    let filterHeadings = document.querySelectorAll('#filters-container label')
    
    for ( heading of filterHeadings ) {
    
        // Use the textContent or innerHTML of the heading for the switch
        switch (heading.textContent) { // or heading.innerHTML if you need HTML content
           case 'cut_dia_in_display':
           case 'cut_dia_m_display':
           case 'outer_dimension':
               if(filtersContainer.classList.contains('Threading')) {
                   heading.innerHTML = "Cutter Dia.";
                   break;
               } else {
                   heading.innerHTML = "Diameter";
                   break;
               }
               break;
           case 'shank_dia_in_display':
           case 'shank_dia_m_display':
               heading.innerText = "Shank";
               break;
           case 'oal_in_display':
           case 'oal_m_display':
               heading.innerText = "OAL";
               break;
           case 'loc_in_display':
           case 'loc_m_display':
           case 'flutelength':
               // loc_in_display title is Flute Length for Holemaking products
               if(filtersContainer.classList.contains('Holemaking')) {
                   heading.innerHTML = "Flute Length";
                   break;
               } else if (filtersContainer.classList.contains('187') || filtersContainer.classList.contains('189') || filtersContainer.classList.contains('189M')) {
                   console.log('hello');
                   heading.innerText= "Neck Len.";
                   break;
               } else {
                   
                   heading.innerHTML = "LOC";
                   break;
               }
               break;
           case 'taps_thread_size':
               if(filtersContainer.classList.contains('187')) {
                   heading.innerText = "TPI Range";
                   break;
               } else {
                   heading.innerText = "Thread Size";
                   break;
               }
               break;
           case 'taps_thread_limit':
               heading.innerText = "Thread Limit";
               break;
           case 'radius_in_display':
           case 'radius_m_display':
               heading.innerText = "Radius";
               break;
           case 'flutes':
               heading.innerText = "Flutes";
               break;
           case 'tap_taper':
               heading.innerText = "Taper";
               break;
           case 'c2':
               heading.innerText = "Cut Type";
               break;
           case 'tool':
               heading.innerText = "Tool";
               break;
           case 'torx_type':
               heading.innerText = "Torx Type";
               break;
           case 'l1':
               heading.innerText = "L1";
               break;
           case 'l2':
               heading.innerText = "L2";
               break;
           case 'ldr':
               heading.innerText = "LDR";
               break;
           case 'chamfer':
               heading.innerText = "Chamfer";
               break;
           case 'weldon':
               heading.innerText = "Weldon";
               break;
           case 'reach_in_display':
               if(filtersContainer.classList.contains('Threading')) {
                   heading.innerHTML = "Neck Dia.";
                   break;  
               } else {
                   heading.innerHTML = "LBS";
                   break;
               }
               break;
           case 'step_dia':
               heading.innerHTML = "D";
               break;
           case 'none':
               heading.innerText = "Bright";
               break;
           case 'nf1':
               heading.innerText = "NF1";
               break;
           case 'fx2':
               heading.innerText = "FX2";
               break;
           case 'fx3':
               heading.innerText = "FX3";
               break;
           case 'altin':
               heading.innerText = "AlTiN";
               break;
           case 'tin':
               heading.innerText = "TiN";
               break;
           case 'ticn':
               heading.innerText = "TiCN";
               break;
           case 'coating':
               heading.innerText = "Coating";
               break;
           case 'angle_display':
               heading.innerText = "Incl. Angle";
               break;
           case 'split':
               heading.innerHTML = "Split Length";
               break;
           case 'style':
               heading.innerText = "Style";
               break;
           case 'pilot_dia':
               heading.innerText = "Pilot";
               break;
           case 'thickness':
               heading.innerHTML = "Thickness";
               break;
           case 'width':
               heading.innerText = "Width";
               break;
           case 'grade':
               heading.innerText = "Grade";
               break;
           case 'eco':
               heading.innerText = "PAC Drill Size";
               break;
           // diasize title is Pilot(D1) for some series, while Diameter(D1) for others
           case 'diasize':
               if(filtersContainer.classList.contains('300')) {
                   heading.innerHTML = 'Pilot <span class="small">Size</span>';
                   break;
               } else {
                   heading.innerHTML = 'Diameter <span class="small">Size</span>';
                   break;
               }
               break;
           case 'diadec':
               if(filtersContainer.classList.contains('300')) {
                   heading.innerHTML = 'Pilot <span class="small">Dec.</span>';
                   break;
               } else {
                   heading.innerHTML = 'Diameter <span class="small">Dec.</span>';
                   break;
               }
               break;
           case 'eco':
               heading.innerText = "PAC Drill Size";
               break;
           case 'drillpoint':
               heading.innerText = "Drill Point";
               break;
           case 'min_bore':
               heading.innerText = "Minimum Bore";
               break;
           case 'max_bore':
               heading.innerText = "Maximum Bore";
               break;
           case 'projection':
               heading.innerText = "Projection";
               break;
           case 'taps_standard':
               heading.innerText = "Standard";
               break;
           case 'neck_length':
               heading.innerHTML = "Neck Len.";
               break;
           case 'neck_dia_in_display':
               heading.innerHTML = "Neck Dia.";
               break;
           case 'helix':
               heading.innerText = "Helix";
               break;
           case 'taps_chamfer_type':
           case 'taps_go_nogo':
               heading.innerText = "Type";
               break;
           case 'taps_min_tap_drill_size':
               heading.innerText = "Min Tap/Drill Size";
               break;
           case 'taps_max_tap_drill_size':
               heading.innerText = "Max Tap/Drill Size ";
               break;
           case 'taps_pipe_size':
               heading.innerText = "Pipe Size";
               break;
           case 'taps_class_of_fit':
               heading.innerText = "Class of Fit";
               break;
           case 'taps_drill_length':
               heading.innerHTML = "Drill Len.)";
               break;
           case 'taps_thread_length':
               heading.innerHTML = "Thread Len.";
               break;
           case 'taps_od1':
               heading.innerText = "Outside Dia.";
               break;
           case 'taps_tap_size':
           case 'taps_size':
               heading.innerText = "Tap Size";
               break;
           case 'taps_dia':
               heading.innerText = "Tap Dia.(A)";
               break;
           case 'taps_body':
               heading.innerText = "Body Dia.(B)";
               break;
           case 'taps_shank':
               heading.innerText = "Shank(C)";
               break;
           case 'taps_square':
               heading.innerText = "Square Size";
               break;
           case 'taps_depth':
               heading.innerText = "Depth Tap Enters";
               break;
           case 'taps_taps_handle':
               heading.innerText = "Handle";
               break;
           case 'description':
           case 'part_description':
               heading.innerText = "Description";
               break;
           case 'taps_amount':
               heading.innerText = "Amount";
               break;
           case 'pitch_classification':
               heading.innerText = 'Classification';
               break;
           case 'sub_sub_type':
               heading.innerText = 'Type';
               break;
           case 'iso_code':
               heading.innerText = 'ISO Code';
               break;
           default:
               console.log('Unknown heading:', heading.textContent);
       }
   
   }
}




