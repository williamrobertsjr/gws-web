const tools = [
    {
      id: 1,
      tool_type: "HOLEMAKING",
      sub_type: "Boring Bar",
      sub_sub_type: "Lock-Down Flat",
      data_filters:
        "min_bore,max_bore,projection,shank_dia_in_display,radius_m_display",
      has_metric: null,
    },
    {
      id: 2,
      tool_type: "HOLEMAKING",
      sub_type: "Chucking Reamers",
      sub_sub_type: "Slow Spiral",
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display,helix",
      has_metric: null,
    },
    {
      id: 3,
      tool_type: "HOLEMAKING",
      sub_type: "Chucking Reamers",
      sub_sub_type: "Straight",
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 4,
      tool_type: "HOLEMAKING",
      sub_type: "Countersinks",
      sub_sub_type: null,
      data_filters: "cut_dia_in_display,shank_dia_in_display,angle_display,style",
      has_metric: null,
    },
    {
      id: 5,
      tool_type: "HOLEMAKING",
      sub_type: "Countersinks - Aerospace",
      sub_sub_type: "Piloted",
      data_filters: "shank_dia_in_display,pilot_dia,angle_display",
      has_metric: null,
    },
    {
      id: 6,
      tool_type: "HOLEMAKING",
      sub_type: "Drills",
      sub_sub_type: "Carbide Drills",
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display",
      has_metric: "yes",
    },
    {
      id: 7,
      tool_type: "HOLEMAKING",
      sub_type: "Drills",
      sub_sub_type: "Screw Machine Drills",
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display,coating",
      has_metric: null,
    },
    {
      id: 8,
      tool_type: "HOLEMAKING",
      sub_type: "Drills",
      sub_sub_type: "Spade Drills",
      data_filters:
        "cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 9,
      tool_type: "HOLEMAKING",
      sub_type: "Drills",
      sub_sub_type: "Spot Drills",
      data_filters:
        "cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,angle_display,coating",
      has_metric: null,
    },
    {
      id: 10,
      tool_type: "HOLEMAKING",
      sub_type: "Drills",
      sub_sub_type: "Step Drills",
      data_filters:
        "torx_type,outer_dimension,l1,step_dia,l2,shank_dia_m_display,oal_m_display",
      has_metric: null,
    },
    {
      id: 11,
      tool_type: "HOLEMAKING",
      sub_type: "Drills",
      sub_sub_type: "Straight Drills",
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display,coating",
      has_metric: null,
    },
    {
      id: 12,
      tool_type: "HOLEMAKING",
      sub_type: "Drills",
      sub_sub_type: "Twist Drills",
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 13,
      tool_type: "HOLEMAKING",
      sub_type: "Drills - Aerospace",
      sub_sub_type: null,
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 14,
      tool_type: "HOLEMAKING",
      sub_type: "Drills - Aerospace",
      sub_sub_type: "4-Facet",
      data_filters:
        "diasize,diadec,flutelength,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 15,
      tool_type: "HOLEMAKING",
      sub_type: "Drills - Aerospace",
      sub_sub_type: "8-Facet",
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 16,
      tool_type: "HOLEMAKING",
      sub_type: "Drills - Aerospace",
      sub_sub_type: "Dagger",
      data_filters: "diasize,diadec,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 17,
      tool_type: "HOLEMAKING",
      sub_type: "Drills - Aerospace",
      sub_sub_type: "Dreamer",
      data_filters:
        "diasize,diadec,loc_in_display,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 18,
      tool_type: "HOLEMAKING",
      sub_type: "Drills/Countersinks",
      sub_sub_type: null,
      data_filters:
        "diasize,diadec,oal_in_display,shank_dia_in_display,angle_display,coating",
      has_metric: null,
    },
    {
      id: 19,
      tool_type: "HOLEMAKING",
      sub_type: "Reamers",
      sub_sub_type: null,
      data_filters:
        "diasize,diadec,loc_in_display,reach_in_display,oal_in_display,shank_dia_in_display,pac_drill_size",
      has_metric: null,
    },
    {
      id: 20,
      tool_type: "HOLEMAKING",
      sub_type: "Reverse Spot Facers - Aerospace",
      sub_sub_type: "Reverse Spot Facers",
      data_filters: "cut_dia_in_display,radius_in_display,pilot_dia",
      has_metric: null,
    },
    {
      id: 21,
      tool_type: "HOLEMAKING",
      sub_type: "Rivet Shavers - Aerospace",
      sub_sub_type: "Rivet Shavers",
      data_filters: "shank_dia_in_display,thread",
      has_metric: null,
    },
    {
      id: 22,
      tool_type: "INSERTS",
      sub_type: "Inserts",
      sub_sub_type: null,
      data_filters: "thickness,width,ic,radius_in_display,edge_prep,iso_code",
      has_metric: null,
    },
    {
      id: 23,
      tool_type: "MILLING",
      sub_type: "Ball Nose",
      sub_sub_type: null,
      data_filters:
        "flutes,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,coating",
      has_metric: "yes",
    },
    {
      id: 24,
      tool_type: "MILLING",
      sub_type: "Chamfer Mills",
      sub_sub_type: null,
      data_filters:
        "flutes,cut_dia_in_display,oal_in_display,shank_dia_in_display,angle_display,flutes,coating",
      has_metric: null,
    },
    {
      id: 25,
      tool_type: "MILLING",
      sub_type: "Corner Chamfer",
      sub_sub_type: null,
      data_filters:
        "flutes,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,coating",
      has_metric: null,
    },
    {
      id: 26,
      tool_type: "MILLING",
      sub_type: "Corner Radius",
      sub_sub_type: null,
      data_filters:
        "flutes,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,radius_in_display,coating",
      has_metric: null,
    },
    {
      id: 27,
      tool_type: "MILLING",
      sub_type: "Drill/Mills",
      sub_sub_type: null,
      data_filters:
        "flutes,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,angle_display,coating",
      has_metric: null,
    },
    {
      id: 28,
      tool_type: "MILLING",
      sub_type: "Micro End Mills",
      sub_sub_type: null,
      data_filters:
        "flutes,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,coating",
      has_metric: null,
    },
    {
      id: 29,
      tool_type: "MILLING",
      sub_type: "Radius",
      sub_sub_type: null,
      data_filters:
        "flutes,cut_dia_in_display,loc_in_display,reach_in_display,oal_in_display,shank_dia_in_display,radius_in_display",
      has_metric: null,
    },
    {
      id: 30,
      tool_type: "MILLING",
      sub_type: "Square",
      sub_sub_type: null,
      data_filters:
        "flutes,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,chamfer",
      has_metric: null,
    },
    {
      id: 31,
      tool_type: "SPECIALTY",
      sub_type: "Armory",
      sub_sub_type: null,
      data_filters: "description",
      has_metric: null,
    },
    {
      id: 32,
      tool_type: "SPECIALTY",
      sub_type: "Blanks",
      sub_sub_type: "Carbide Strip",
      data_filters: "style,thickness,width,oal_in_display",
      has_metric: null,
    },
    {
      id: 33,
      tool_type: "SPECIALTY",
      sub_type: "Blanks",
      sub_sub_type: "Center Tip",
      data_filters: "style,cut_dia_in_display,height",
      has_metric: null,
    },
    {
      id: 34,
      tool_type: "SPECIALTY",
      sub_type: "Blanks",
      sub_sub_type: "Radius",
      data_filters: "style,thickness,width,oal_in_display,grade",
      has_metric: null,
    },
    {
      id: 35,
      tool_type: "SPECIALTY",
      sub_type: "Blanks",
      sub_sub_type: "Round",
      data_filters: "cut_dia_in_display,oal_in_display,ground,unground",
      has_metric: null,
    },
    {
      id: 36,
      tool_type: "SPECIALTY",
      sub_type: "Blanks",
      sub_sub_type: "Split End",
      data_filters: "cut_dia_in_display,oal_in_display,split,c2",
      has_metric: null,
    },
    {
      id: 37,
      tool_type: "SPECIALTY",
      sub_type: "Blanks",
      sub_sub_type: "Square",
      data_filters: "style,thickness,width,oal_in_display,grade",
      has_metric: null,
    },
    {
      id: 38,
      tool_type: "SPECIALTY",
      sub_type: "Burrs",
      sub_sub_type: "Ball Shape",
      data_filters:
        "tool,cut_dia_in_display,loc_in_display,shank_dia_in_display,c2",
      has_metric: null,
    },
    {
      id: 39,
      tool_type: "SPECIALTY",
      sub_type: "Burrs",
      sub_sub_type: "Cone Shape",
      data_filters:
        "tool,cut_dia_in_display,loc_in_display,shank_dia_in_display,c2",
      has_metric: null,
    },
    {
      id: 40,
      tool_type: "SPECIALTY",
      sub_type: "Burrs",
      sub_sub_type: "Cylinder Shape",
      data_filters:
        "tool,cut_dia_in_display,loc_in_display,shank_dia_in_display,c2",
      has_metric: null,
    },
    {
      id: 41,
      tool_type: "SPECIALTY",
      sub_type: "Burrs",
      sub_sub_type: "Flame Shape",
      data_filters:
        "tool,cut_dia_in_display,loc_in_display,shank_dia_in_display,c2",
      has_metric: null,
    },
    {
      id: 42,
      tool_type: "SPECIALTY",
      sub_type: "Burrs",
      sub_sub_type: "Oval/Egg Shape",
      data_filters:
        "tool,cut_dia_in_display,loc_in_display,shank_dia_in_display,c2",
      has_metric: null,
    },
    {
      id: 43,
      tool_type: "SPECIALTY",
      sub_type: "Burrs",
      sub_sub_type: "Taper Shape",
      data_filters:
        "tool,cut_dia_in_display,loc_in_display,shank_dia_in_display,c2",
      has_metric: null,
    },
    {
      id: 44,
      tool_type: "SPECIALTY",
      sub_type: "Burrs",
      sub_sub_type: "Tree Shape",
      data_filters:
        "tool,cut_dia_in_display,loc_in_display,shank_dia_in_display,c2",
      has_metric: null,
    },
    {
      id: 45,
      tool_type: "SPECIALTY",
      sub_type: "Engraving",
      sub_sub_type: "Single End/Double End",
      data_filters: "cut_dia_in_display,oal_in_display,split,style,angle_display",
      has_metric: null,
    },
    {
      id: 46,
      tool_type: "SPECIALTY",
      sub_type: "ID Grinding",
      sub_sub_type: "Internal Grind Tool",
      data_filters:
        "cut_dia_in_display,loc_in_display,reach_in_display,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
    {
      id: 47,
      tool_type: "SPECIALTY",
      sub_type: "ID Grinding",
      sub_sub_type: "Piloted Die Trimmer",
      data_filters:
        "cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,pilot_dia",
      has_metric: null,
    },
    {
      id: 48,
      tool_type: "SPECIALTY",
      sub_type: "Routers",
      sub_sub_type: "Straight Flute",
      data_filters:
        "cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,flutes",
      has_metric: null,
    },
    {
      id: 49,
      tool_type: "SPECIALTY",
      sub_type: "Routers",
      sub_sub_type: "Style A-F Diamond Cut",
      data_filters:
        "cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,customimage",
      has_metric: null,
    },
    {
      id: 50,
      tool_type: "THREADING",
      sub_type: "Dies",
      sub_sub_type: "Round",
      data_filters: "taps_thread_size,taps_od1",
      has_metric: null,
    },
    {
      id: 51,
      tool_type: "THREADING",
      sub_type: "Drill/Taps",
      sub_sub_type: null,
      data_filters:
        "taps_thread_size,cut_dia_in_display,oal_in_display,taps_drill_length,taps_thread_length",
      has_metric: "yes",
    },
    {
      id: 52,
      tool_type: "THREADING",
      sub_type: "Fluids",
      sub_sub_type: "SmartCut",
      data_filters: "part_description,taps_amount",
      has_metric: null,
    },
    {
      id: 53,
      tool_type: "THREADING",
      sub_type: "Gages",
      sub_sub_type: "Thread Plug Gage",
      data_filters:
        "taps_thread_size,pitch_classification,taps_class_of_fit,sub_sub_type",
      has_metric: "yes",
    },
    {
      id: 54,
      tool_type: "THREADING",
      sub_type: "Gages",
      sub_sub_type: "Thread Ring Gage",
      data_filters:
        "taps_thread_size,taps_class_of_fit,taps_taps_handle,taps_go_nogo",
      has_metric: null,
    },
    {
      id: 55,
      tool_type: "THREADING",
      sub_type: "Tap Extensions",
      sub_sub_type: "ANSI",
      data_filters:
        "taps_pipe_size,taps_dia,taps_body,oal_in_display,taps_shank,taps_square,taps_depth",
      has_metric: null,
    },
    {
      id: 56,
      tool_type: "THREADING",
      sub_type: "Tap Extensions",
      sub_sub_type: "DIN",
      data_filters:
        "taps_dia,taps_body,oal_in_display,taps_size,taps_shank,taps_square,taps_depth",
      has_metric: null,
    },
    {
      id: 57,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Carbide Insert",
      data_filters:
        "taps_thread_size,taps_thread_limit,taps_min_tap_drill_size,taps_max_tap_drill_size,oal_in_display,coating",
      has_metric: null,
    },
    {
      id: 58,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Cleanout Tap",
      data_filters:
        "taps_thread_size,pitch_classification,flutes,taps_shank,taps_square,oal_in_display,coating",
      has_metric: null,
    },
    {
      id: 59,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Conduit Tap",
      data_filters: "taps_thread_size,taps_pipe_size,flutes,taps_chamfer_type",
      has_metric: null,
    },
    {
      id: 60,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Forming Tap",
      data_filters:
        "taps_thread_size,taps_thread_limit,taps_min_tap_drill_size,taps_max_tap_drill_size,taps_chamfer_type,coating",
      has_metric: null,
    },
    {
      id: 61,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Hand Tap",
      data_filters: "taps_thread_size,taps_class_of_fit,flutes,taps_chamfer_type",
      has_metric: "yes",
    },
    {
      id: 62,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Pipe Tap",
      data_filters:
        "taps_thread_size,pitch_classification,flutes,helix,taps_shank,taps_square,oal_in_display,coating",
      has_metric: null,
    },
    {
      id: 63,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Spiral Flute Tap",
      data_filters:
        "taps_thread_size,pitch_classification,taps_thread_limit,flutes,helix,taps_shank,taps_square,oal_in_display,coating",
      has_metric: "yes",
    },
    {
      id: 64,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Spiral Point Tap",
      data_filters:
        "taps_thread_size,pitch_classification,taps_thread_limit,flutes,taps_shank,taps_square,oal_in_display,coating",
      has_metric: null,
    },
    {
      id: 65,
      tool_type: "THREADING",
      sub_type: "Taps",
      sub_sub_type: "Straight Flute Tap",
      data_filters:
        "taps_thread_size,pitch_classification,taps_thread_limit,flutes,taps_shank,taps_square,oal_in_display,coating",
      has_metric: null,
    },
    {
      id: 66,
      tool_type: "THREADING",
      sub_type: "Thread Mills",
      sub_sub_type: "Helical Flute",
      data_filters:
        "taps_thread_size,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,flutes,coating",
      has_metric: null,
    },
    {
      id: 67,
      tool_type: "THREADING",
      sub_type: "Thread Mills",
      sub_sub_type: "LHC/LHS",
      data_filters:
        "taps_thread_size,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display,flutes",
      has_metric: null,
    },
    {
      id: 68,
      tool_type: "THREADING",
      sub_type: "Thread Mills",
      sub_sub_type: "Single Point",
      data_filters:
        "taps_thread_size,cut_dia_in_display,loc_in_display,neck_dia_in_display,oal_in_display,shank_dia_in_display,flutes",
      has_metric: null,
    },
    {
      id: 69,
      tool_type: "THREADING",
      sub_type: "Thread Mills",
      sub_sub_type: "Thread Mill",
      data_filters:
        "taps_thread_size,cut_dia_in_display,loc_in_display,oal_in_display,shank_dia_in_display",
      has_metric: null,
    },
];












// Flag to prevent multiple listeners
// let millingListenerAdded = false;

// document.addEventListener("facetwp-refresh", function () {
//   const millingFilter = document.querySelector(".facetwp-facet-milling_type select.facetwp-dropdown");
//   const selectedValue = millingFilter?.value || null;

//   showFilters(selectedValue); // Hide/show before FacetWP replaces DOM
// });


// document.addEventListener("facetwp-loaded", function () {
//   const millingFilter = document.querySelector(".facetwp-facet-milling_type select.facetwp-dropdown");

//   if (millingFilter) {
//     // Show/hide filters immediately based on current selection
//     showFilters(millingFilter.value);

//     // Prevent duplicate event listeners
//     if (!millingFilter.dataset.listenerAdded) {
//       millingFilter.addEventListener("change", function () {
//         const value = this.value;
//         showFilters(value);
//         FWP.refresh(); // Trigger FacetWP to reload dependent filters/results
//       });

//       millingFilter.dataset.listenerAdded = "true";
//     }
//   }
// });

// function showFilters(sub_type) {
//   // Normalize sub_type
//   const normalized = sub_type
//     ? sub_type.charAt(0).toUpperCase() + sub_type.slice(1).toLowerCase()
//     : "";

//   const facets = document.querySelectorAll("#data-filters .facetwp-facet");
//   let data_filters = [];

//   if (normalized) {
//     const match = tools.find(tool => tool.sub_type === normalized);
//     if (match) {
//       data_filters = match.data_filters.split(",");
//     }
//   }

//   facets.forEach(facet => {
//     const facetName = facet.getAttribute("data-name");
//     const wrapper = facet.closest(".filter-div");
//     if (!wrapper) return;

//     if (facetName === "material" || data_filters.includes(facetName)) {
//       wrapper.style.display = "block";
//     } else {
//       wrapper.style.display = "none";
//     }
//   });

//   // Optional: fade in/out the whole section if needed
//   const dataFiltersEl = document.getElementById("data-filters");
//   if (normalized) {
//     dataFiltersEl.classList.remove("opacity-0");
//   } else {
//     dataFiltersEl.classList.add("opacity-0");
//   }
// }

// function startProgressBar() {
//   const bar = document.getElementById('facetwp-progress-bar');
//   const container = document.getElementById('facetwp-progress-bar-container');
//   const percent = document.getElementById('facetwp-progress-percent');

//   // Show progress bar and reset
//   container.classList.remove('hidden');
//   bar.style.width = '1%';
//   percent.textContent = '1%';

//   // Hide filters and loop
//   document.getElementById('data-filters')?.classList.add('opacity-0', 'pointer-events-none');
//   document.querySelector('.facetwp-template')?.classList.add('opacity-0', 'pointer-events-none');

//   let width = 1;
//   window.fwpProgressInterval = setInterval(() => {
//     if (width >= 90) return;
//     width += 1;
//     bar.style.width = `${width}%`;
//     percent.textContent = `${width}%`;
//   }, 10);
// }

// let fwpProgressInterval;
// let fwpProgressWidth = 1; // define it in outer scope so it's accessible
// function startProgressBar() {
//   const bar = document.getElementById('facetwp-progress-bar');
//   const container = document.getElementById('facetwp-progress-bar-container');
//   const percent = document.getElementById('facetwp-progress-percent');

//   container.classList.remove('hidden');
//   bar.style.width = '1%';
//   percent.textContent = '1%';
//   fwpProgressWidth = 1;

//   // Hide filters and loop
//   document.getElementById('data-filters')?.classList.add('opacity-0', 'pointer-events-none');
//   document.querySelector('.facetwp-template')?.classList.add('opacity-0', 'pointer-events-none');

//   fwpProgressInterval = setInterval(() => {
//     if (fwpProgressWidth >= 90) return;
//     fwpProgressWidth += 1;
//     bar.style.width = `${fwpProgressWidth}%`;
//     percent.textContent = `${fwpProgressWidth}%`;
//       // Change text color based on progress
//     if (fwpProgressWidth >= 50) {
//       percent.classList.replace('text-black', 'text-white');
//     } else {
//       percent.classList.replace('text-white', 'text-black');
//     }
//   }, 10);
// }

// function completeProgressBar() {
//   const bar = document.getElementById('facetwp-progress-bar');
//   const container = document.getElementById('facetwp-progress-bar-container');
//   const percent = document.getElementById('facetwp-progress-percent');

//   clearInterval(fwpProgressInterval);
//   bar.style.width = '100%';
//   percent.textContent = '100%';

//   setTimeout(() => {
//     bar.style.width = '0%';
//     percent.textContent = '';
//     container.classList.add('hidden');

//     // Show filters and loop
//     document.getElementById('data-filters')?.classList.remove('opacity-0', 'pointer-events-none');
//     document.querySelector('.facetwp-template')?.classList.remove('opacity-0', 'pointer-events-none');
//   }, 300);
// }

// document.addEventListener('facetwp-refresh', startProgressBar);
// document.addEventListener('facetwp-loaded', completeProgressBar);









  



