
let tableHeading = document.querySelectorAll('th');
const productTable = document.getElementById('product-table')
console.log(tableHeading)
if(productTable) {
    let table = new DataTable('#product-table', {
        pageLength: 25,
    }); 
} 



for ( heading of tableHeading ) {
    
     // Use the textContent or innerHTML of the heading for the switch
     switch (heading.textContent) { // or heading.innerHTML if you need HTML content
        case 'cut_dia_in_display':
        case 'cut_dia_m_display':
        case 'outer_dimension':
            if(productTable.classList.contains('THREADING')) {
                heading.innerHTML = "Cutter Dia.(D<sub>1</sub>)";
                break;
            } else {
                heading.innerHTML = "Diameter(D<sub>1</sub>)";
                break;
            }
            break;
        case 'shank_dia_in_display':
        case 'shank_dia_m_display':
            heading.innerText = "Shank(D)";
            break;
        case 'oal_in_display':
        case 'oal_m_display':
            heading.innerText = "OAL(L)";
            break;
        case 'loc_in_display':
        case 'loc_m_display':
        case 'flutelength':
            // loc_in_display title is Flute Length for Holemaking products
            if(productTable.classList.contains('HOLEMAKING')) {
                heading.innerHTML = "Flute Length(L<sub>1</sub>)";
                break;
            } else if (productTable.classList.contains('187') || productTable.classList.contains('189') || productTable.classList.contains('189M')) {
                console.log('hello');
                heading.innerText= "Neck Len.(L)";
                break;
            } else {
                console.log('did not work');
                heading.innerHTML = "LOC(L<sub>1</sub>)";
                break;
            }
            break;
        case 'taps_thread_size':
            if(productTable.classList.contains('187')) {
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
            heading.innerText = "Radius(R)";
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
            if(productTable.classList.contains('THREADING')) {
                heading.innerHTML = "Neck Dia.(D<sub>2</sub>)";
                break;  
            } else {
                heading.innerHTML = "LBS(L<sub>2</sub>)";
                break;
            }
            break;
        case 'step_dia':
            heading.innerHTML = "D<sub>2</sub>";
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
            heading.innerHTML = "Split Length(L<sub>1</sub>)";
            break;
        case 'style':
            heading.innerText = "Style";
            break;
        case 'pilot_dia':
            heading.innerText = "Pilot(D)";
            break;
        case 'thickness':
            heading.innerHTML = "Thickness(D<sub>1</sub>)";
            break;
        case 'width':
            heading.innerText = "Width(D)";
            break;
        case 'grade':
            heading.innerText = "Grade";
            break;
        case 'eco':
            heading.innerText = "PAC Drill Size";
            break;
        // diasize title is Pilot(D1) for some series, while Diameter(D1) for others
        case 'diasize':
            if(productTable.classList.contains('300')) {
                heading.innerHTML = 'Pilot(D<sub>1</sub>) <span class="small">Size</span>';
                break;
            } else {
                heading.innerHTML = 'Diameter(D<sub>1</sub>) <span class="small">Size</span>';
                break;
            }
            break;
        case 'diadec':
            if(productTable.classList.contains('300')) {
                heading.innerHTML = 'Pilot(D<sub>1</sub>) <span class="small">Dec.</span>';
                break;
            } else {
                heading.innerHTML = 'Diameter(D<sub>1</sub>) <span class="small">Dec.</span>';
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
            heading.innerHTML = "Neck Len.(L<sub>2</sub>)";
            break;
        case 'neck_dia_in_display':
            heading.innerHTML = "Neck Dia.(L<sub>2</sub>)";
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
            heading.innerHTML = "Drill Len.(L<sub>1</sub>)";
            break;
        case 'taps_thread_length':
            heading.innerHTML = "Thread Len.(L<sub>2</sub>)";
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
            heading.innerText = "Square Size(D)";
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
        case 'edge_prep':
            heading.innerText = 'Edge Prep';
            break;
        case 'ic':
            heading.innerText = 'I.C.';
            break;
        case 'iso_code':
            heading.innerText = 'ISO Code';
            break;
        default:
            console.log('Unknown heading:', heading.textContent);
    }

}

const attributes = document.querySelectorAll('#attributes-list span');
const attributesList = document.getElementById('attributes-list');
console.log(attributes)
for ( att of attributes ) {
    console.log(att.textContent)
     // Use the textContent or innerHTML of the heading for the switch
     switch (att.textContent) { // or att.innerHTML if you need HTML content
        case 'cut_dia_in_display':
        case 'cut_dia_m_display':
        case 'outer_dimension':
            if(attributesList.classList.contains('THREADING')) {
                att.innerHTML = "Cutter Dia.(D<sub>1</sub>)";
                break;
            } else {
                att.innerHTML = "Diameter(D<sub>1</sub>)";
                break;
            }
            break;
        case 'shank_dia_in_display':
        case 'shank_dia_m_display':
            att.innerText = "Shank(D)";
            break;
        case 'oal_in_display':
        case 'oal_m_display':
            att.innerText = "OAL(L)";
            break;
        case 'loc_in_display':
        case 'loc_m_display':
        case 'flutelength':
            // loc_in_display title is Flute Length for Holemaking products
            if(attributesList.classList.contains('HOLEMAKING')) {
                att.innerHTML = "Flute Length(L<sub>1</sub>)";
                break;
            } else if (attributesList.classList.contains('187') || attributesList.classList.contains('189') || attributesList.classList.contains('189M')) {
                console.log('hello');
                att.innerText= "Neck Len.(L)";
                break;
            } else {
                console.log('did not work');
                att.innerHTML = "LOC(L<sub>1</sub>)";
                break;
            }
            break;
        case 'taps_thread_size':
            if(attributesList.classList.contains('187')) {
                att.innerText = "TPI Range";
                break;
            } else {
                att.innerText = "Thread Size";
                break;
            }
            break;
        case 'taps_thread_limit':
            att.innerText = "Thread Limit";
            break;
        case 'radius_in_display':
        case 'radius_m_display':
            att.innerText = "Radius(R)";
            break;
        case 'flutes':
            att.innerText = "Flutes";
            break;
        case 'tap_taper':
            att.innerText = "Taper";
            break;
        case 'c2':
            att.innerText = "Cut Type";
            break;
        case 'tool':
            att.innerText = "Tool";
            break;
        case 'torx_type':
            att.innerText = "Torx Type";
            break;
        case 'l1':
            att.innerText = "L1";
            break;
        case 'l2':
            att.innerText = "L2";
            break;
        case 'ldr':
            att.innerText = "LDR";
            break;
        case 'chamfer':
            att.innerText = "Chamfer";
            break;
        case 'weldon':
            att.innerText = "Weldon";
            break;
        case 'reach_in_display':
            if(attributesList.classList.contains('THREADING')) {
                att.innerHTML = "Neck Dia.(D<sub>2</sub>)";
                break;  
            } else {
                att.innerHTML = "LBS(L<sub>2</sub>)";
                break;
            }
            break;
        case 'step_dia':
            att.innerHTML = "D<sub>2</sub>";
            break;
        case 'none':
            att.innerText = "Bright";
            break;
        case 'nf1':
            att.innerText = "NF1";
            break;
        case 'fx2':
            att.innerText = "FX2";
            break;
        case 'fx3':
            att.innerText = "FX3";
            break;
        case 'altin':
            att.innerText = "AlTiN";
            break;
        case 'tin':
            att.innerText = "TiN";
            break;
        case 'ticn':
            att.innerText = "TiCN";
            break;
        case 'coating':
            att.innerText = "Coating";
            break;
        case 'angle_display':
            att.innerText = "Incl. Angle";
            break;
        case 'split':
            att.innerHTML = "Split Length(L<sub>1</sub>)";
            break;
        case 'style':
            att.innerText = "Style";
            break;
        case 'pilot_dia':
            att.innerText = "Pilot(D)";
            break;
        case 'thickness':
            console.log('thickness')
            att.innerHTML = "Thickness(D<sub>1</sub>)";
            break;
        case 'width':
            att.innerText = "Width(D)";
            break;
        case 'grade':
            att.innerText = "Grade";
            break;
        case 'eco':
            att.innerText = "PAC Drill Size";
            break;
        // diasize title is Pilot(D1) for some series, while Diameter(D1) for others
        case 'diasize':
            if(attributesList.classList.contains('300')) {
                att.innerHTML = 'Pilot(D<sub>1</sub>) <span class="small">Size</span>';
                break;
            } else {
                att.innerHTML = 'Diameter(D<sub>1</sub>) <span class="small">Size</span>';
                break;
            }
            break;
        case 'diadec':
            if(attributesList.classList.contains('300')) {
                att.innerHTML = 'Pilot(D<sub>1</sub>) <span class="small">Dec.</span>';
                break;
            } else {
                att.innerHTML = 'Diameter(D<sub>1</sub>) <span class="small">Dec.</span>';
                break;
            }
            break;
        case 'eco':
            att.innerText = "PAC Drill Size";
            break;
        case 'drillpoint':
            att.innerText = "Drill Point";
            break;
        case 'min_bore':
            att.innerText = "Minimum Bore";
            break;
        case 'max_bore':
            att.innerText = "Maximum Bore";
            break;
        case 'projection':
            att.innerText = "Projection";
            break;
        case 'taps_standard':
            att.innerText = "Standard";
            break;
        case 'neck_length':
            att.innerHTML = "Neck Len.(L<sub>2</sub>)";
            break;
        case 'neck_dia_in_display':
            att.innerHTML = "Neck Dia.(L<sub>2</sub>)";
            break;
        case 'helix':
            att.innerText = "Helix";
            break;
        case 'taps_chamfer_type':
        case 'taps_go_nogo':
            att.innerText = "Type";
            break;
        case 'taps_min_tap_drill_size':
            att.innerText = "Min Tap/Drill Size";
            break;
        case 'taps_max_tap_drill_size':
            att.innerText = "Max Tap/Drill Size ";
            break;
        case 'taps_pipe_size':
            att.innerText = "Pipe Size";
            break;
        case 'taps_class_of_fit':
            att.innerText = "Class of Fit";
            break;
        case 'taps_drill_length':
            att.innerHTML = "Drill Len.(L<sub>1</sub>)";
            break;
        case 'taps_thread_length':
            att.innerHTML = "Thread Len.(L<sub>2</sub>)";
            break;
        case 'taps_od1':
            att.innerText = "Outside Dia.";
            break;
        case 'taps_tap_size':
        case 'taps_size':
            att.innerText = "Tap Size";
            break;
        case 'taps_dia':
            att.innerText = "Tap Dia.(A)";
            break;
        case 'taps_body':
            att.innerText = "Body Dia.(B)";
            break;
        case 'taps_shank':
            att.innerText = "Shank(C)";
            break;
        case 'taps_square':
            att.innerText = "Square Size(D)";
            break;
        case 'taps_depth':
            att.innerText = "Depth Tap Enters";
            break;
        case 'taps_taps_handle':
            att.innerText = "Handle";
            break;
        case 'description':
        case 'part_description':
            att.innerText = "Description";
            break;
        case 'taps_amount':
            att.innerText = "Amount";
            break;
        case 'pitch_classification':
            att.innerText = 'Classification';
            break;
        case 'sub_sub_type':
            att.innerText = 'Type';
            break;
        case 'thread':
            att.innerText = 'Thread';
            break;
        case 'edge_prep':
            att.innerText = 'Edge Prep';
            break;
        case 'ic':
            att.innerText = 'I.C.';
            break;
        case 'iso_code':
            att.innerText = 'ISO Code';
            break;

        default:
            console.log('Unknown att:', att.textContent);
    }

}