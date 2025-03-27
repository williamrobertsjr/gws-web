document.addEventListener("DOMContentLoaded", function () {
  partsForm = document.getElementById("partsForm");
  resultsDiv = document.getElementById("results-div");

  // Define Data Table options for Rapid Quote Results Table (datatables.net)
  let table = new DataTable("#rq-table", {
    // options
    columns: [
      //Define Rapid Quote table columns
      { title: "EDP", className: "rq-edp" },
      { title: "Description", className: "rq-description", width: "50%" },
      { title: "Needed", className: "rq-needed", width: "100px" },
      { title: "Stock", className: "rq-stock" },
      { title: "List", className: "rq-list-price" },
      { title: "Net", className: "rq-net-price" },
      { title: "Total", className: "rq-total-price" },
    ],
    buttons: [
      {
        extend: "print",
        text: "Print",
        customize: function (win) {
          let rows = win.document.querySelectorAll("table tbody tr");

          rows.forEach(function (row, index) {
            let neededCell = row.querySelector("td.rq-needed");
            if (neededCell) {
              // Extract the value from the HTML string
              let htmlString = table.cell(index, 2).data();
              let tempDiv = document.createElement("div");
              tempDiv.innerHTML = htmlString;
              let input = tempDiv.querySelector("input");
              let value = input ? input.value : "";

              // Update the print view
              neededCell.textContent = value;
            }
          });
        },
      },
    ],
    responsive: true,
    autoWidth: false,
    searching: false,
    lengthChange: false,
    paging: false,
    ordering: false,
    data: [], //Initialize with empty dataset
  });

  let userRole = window.userRole || "none"; // Replace 'defaultRole' with an appropriate default
  if (userRole === "administrator" || userRole === "sales") {
    document
      .getElementById("distributor-level-message")
      .classList.add("hidden");
  } else if (userRole === "none") {
    document.getElementById("distributor-level-select").classList.add("hidden");
  } else {
    document.getElementById("distributor-level-select").classList.add("hidden");
  }

  // Get users tier
  const getUserTier = () => {
    if (userRole === "administrator" || userRole === "sales") {
      let distributorTierSelect = document.getElementById("distributor-level");
      let distributorTier = distributorTierSelect.value;
      
      return distributorTier;
    } else {
    
      return userRole;
    }
  };

  // Updated calculateDiscount function
  const calculateDiscount = (tier, isAdvancedPerformance) => {
    let discountRate;

    switch (tier) {
      case "t1":
        discountRate = 0.55;
        break;
      case "t2":
        discountRate = 0.525;
        break;
      case "t3":
        discountRate = 0.50;
        break;
      case "57_5":
        discountRate = 0.575;
        break;
      case "direct":
        discountRate = 0.30;
        break;
      case "none":
        discountRate = isAdvancedPerformance = 0;
        break;
      default:
        discountRate = isAdvancedPerformance = 0;
    }

    return discountRate;
  };

  // Function to update prices based on user tier and quantity
  function updatePrices() {
    let userTier = getUserTier();
    const distributorLevel = document.getElementById('distributor-level').value;
    const seriesWithCallPrice = ["110","115","120","125","129","150","162","170","171","172","176","290","295","296","297"]; // Define series requiring "Call" price

    table.rows().every(function() {
        const row = this.data();
        const qtyInput = this.node().querySelector('input[name="neededQty"]').value;
        const listPrice = parseFloat(row[4]); // Assuming this index for list price
        const partNumber = $(this.node()).attr('data-part-number'); // Get part number from row attribute
        const partData = partsData.found_parts.find(part => part.PN === partNumber);
        console.log(`Part Data:`, partData)
        if (partData && seriesWithCallPrice.includes(partData.SERIES)) {
            // Set price fields to "Call" for specific series
            row[4] = "Call for Price";
            row[5] = "Call for Price";
            row[6] = "Call for Price";
        } else {
            // Compute new prices based on the level and quantity
            const discount = calculateDiscount(userTier); // Assume this function returns a discount rate
            const netPrice = listPrice * (1 - discount);
            const totalPrice = netPrice * qtyInput;

            // Update the row with new prices
            row[5] = netPrice.toFixed(2);
            row[6] = totalPrice.toFixed(2);
        }

        this.invalidate(); // Notify DataTables of the data change
    });
    table.draw(false); // Redraw the table without resetting paging
}

  // Function to check and enforce minimum quantity for specific series
  function checkMinimumQuantity(part, qty) {
    console.log("Checking minimum quantity for part:", part);
    console.log("Current quantity:", qty);
    
    const seriesWithMinQty = {
      "6005": 10,
      "6006": 10,
      "6007": 10,
      // Add more series and their minimum quantities as needed
    };
    console.log("Part series:", seriesWithMinQty[part.SERIES]);
    // console.log(`Checking minimum quantity for part: ${part.PN}, series: ${part.SERIES}, current qty: ${qty}`);
    if (seriesWithMinQty[part.SERIES] && qty < seriesWithMinQty[part.SERIES]) {
      console.log(`Minimum quantity for series ${part.SERIES} is ${seriesWithMinQty[part.SERIES]}. Setting qty to minimum.`);
      // alert(`Minimum quantity for series ${part.SERIES} is ${seriesWithMinQty[part.SERIES]}.`);
      return seriesWithMinQty[part.SERIES];
    }
    return qty;
  }
  let partsData = {}; // Variable to store the JSON data for each part
  // Debounce function for the parts form submission 
  function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }
  partsForm.addEventListener("submit", function (event) {
      event.preventDefault();
      // console.log("parts form intercepted");
      // console.log("missing parts div:", document.getElementById("missing-parts"));
      const data = new FormData(partsForm);
      
      if (!data.has("part")) {
          console.error("The 'partsInput' field is not found in the form data");
          return;
      }

      fetch("https://www.gwstoolgroup.com/wp-json/rapid-quote/v1/submit-quote", {
          method: "POST",
          body: data,
      })
      .then(res => res.json())
      .then(data => {
          
          // Clear the current table data
          table.clear();
          partsData = data; // Store the JSON data for each part
          const partPromises = data.found_parts.map(part => {
            console.log("Part from found_parts:", part); // <--- Log each part individually
          
              // Check if the part is listed as obsolete and fetch replacement details if necessary
              return fetchReplacementDetails(part.PN).then(replacementData => {
                  if (replacementData && replacementData.replacement_pn) {
                      part.qtyOnHand = parseInt(replacementData.qty_on_hand, 10); // Update quantity with replacement
                      part.FULL_DESCRIPTION = replacementData.obsolete_description; // Update description with replacement
                      part.LIST_PRICE = parseFloat(replacementData.list_price); // Update price with replacement
                  } else {
                    // If no replacement, set defaults for non-obsolete part
                    part.qtyOnHand = parseInt(part.QTY_ON_HAND, 10) || 0;
                    part.LIST_PRICE = parseFloat(part.LIST_PRICE) || 0;
                  }
                  return part; // Return the updated or original part information
              });
          });

          Promise.all(partPromises).then(updatedParts => {
            updatedParts.forEach(part => {
                let neededQty = 1;
                neededQty = checkMinimumQuantity(part, neededQty);
                const asterisk = neededQty > 1 ? '<span class="minQty" style="color:#d30000;">*</span>' : '';
                const partLink = `<a class="${part.FAMILY} font-bold text-dark-blue" href="/product/?part=${part.PN}" target="_blank">${part.PN}${asterisk}</a>`;
                const neededQtyInput = `<input type="number" id="neededQty" name="neededQty" min="${neededQty}" max="1000" value="${neededQty}" class="flex w-full px-2"></input>`;
                
                // Add the row with the data-part-number attribute
                let rowNode = table.row.add([
                    partLink,
                    part.FULL_DESCRIPTION,
                    neededQtyInput,
                    part.qtyOnHand,
                    part.LIST_PRICE,
                    part.LIST_PRICE,
                    part.LIST_PRICE * neededQty
                ]).node();
                
                // Set the data-part-number attribute
                $(rowNode).attr('data-part-number', part.PN);
            });
            table.draw();
            updatePrices();
        
            let missingPartsDiv = document.getElementById("missing-parts");
            let minQtyPart = document.querySelectorAll('.minQty');
            let minQtyParts = document.getElementById('min-qty-parts');
            if (data.missing_parts && data.missing_parts.length > 0) {
                missingPartsDiv.innerHTML = `<p class="text-white text-sm italic">These parts were not found: ${data.missing_parts}. <span>Please verify part numbers.</span></p>`;
            } else {
                missingPartsDiv.innerHTML = '';
            }
            if (minQtyPart.length > 0) {
                minQtyParts.innerHTML = `<p class="text-white text-sm italic">*Minimum quantity required.</p>`;
            } else {
              console.log('No minimum quantity parts found');
                minQtyParts.innerHTML = '';
            }
        });
      })
      .catch(err => {
          console.error("Error during fetch:", err);
      });
  });

  async function fetchReplacementDetails(partNumber) {
      const response = await fetch(`/wp-content/themes/gws-web/rapid-quote/replacement_qty.php?partNumber=${encodeURIComponent(partNumber)}`);
      if (!response.ok) {
          console.error("Failed to fetch replacement details for part: " + partNumber);
          return {};
      }
      const data = await response.json();
      // Log the entire JSON object:
      // console.log('Replacement API response for:', partNumber, data);

      return data;
  }


  // Update prices any time Distributor level is changed by admin or sales user
  document.addEventListener("change", function (event) {
    if (event.target && event.target.id === "distributor-level") {
      updatePrices();
    }
  });



  document.querySelector("#rq-table").addEventListener("input", debounce(function (event) {
    if (event.target.closest(".rq-needed")) {
        let rowElement = event.target.closest("tr");
        if (!rowElement) {
            console.error("No table row found for the event target");
            return;
        }

        let partNumber = rowElement.getAttribute('data-part-number');
        if (!partNumber) {
            console.error("Part number not found in the row data attribute");
            return;
        }

        // Find the part data in the partsData object
        let partData = partsData.found_parts.find(part => part.PN === partNumber);
        if (!partData) {
            console.error("Part data not found for part number:", partNumber);
            return;
        }

        console.log("Part data:", partData); // Log the part data

        let row = table.row(rowElement);
        let data = row.data();

        let neededQtyColumnIndex = 2;
        let inputCell = rowElement.cells[neededQtyColumnIndex];
        if (inputCell) {
            let neededQty = parseInt(event.target.value, 10);
            let minQty = checkMinimumQuantity(partData, neededQty); // Get the minimum quantity

            if (neededQty < minQty) {
                // Display a warning message if the entered quantity is below the minimum
                alert(`Minimum quantity for series ${partData.SERIES} is ${minQty}.`);
                event.target.value = minQty; // Set the input value to the minimum quantity
            }

            inputCell.innerHTML = inputCell.innerHTML.replace(
                /value=".*?"/,
                `value="${event.target.value}"`
            );
            data[neededQtyColumnIndex] = inputCell.innerHTML;

            row.data(data).invalidate();
            updatePrices();
        } else {
            console.error("Cell not found at the specified index in the row");
        }
    }
  }, 500)); // Adjust the debounce delay as needed (300ms in this example)

  
  // Email / Test Tool Modals
  let modal = document.getElementById("modal-container");
  let emailModal = document.getElementById("emailModal");
  let testToolsModal = document.getElementById("testToolsModal");
  let emailBtn = document.getElementById("emailBtn");
  let closeBtn = document.querySelectorAll(".close-btn");
  let emailForm = document.getElementById("emailForm");
  let testToolsBtn = document.getElementById("testToolsBtn");
  let testToolsForm = document.getElementById("testToolsForm");
  let tablePreview = document.querySelectorAll(".tablePreview");

  let buildTableData = () => {
    let htmlContent = '<table class="emailTable">';

    // Add headers
    htmlContent += "<tr>";
    table.columns().every(function (index) {
      let title = table.column(index).header();
      htmlContent += `<th>${title.textContent}</th>`;
    });
    htmlContent += "</tr>";

    // Add data rows
    table.rows({ search: "applied" }).every(function () {
      let rowData = this.data();
      htmlContent += "<tr>";
      rowData.forEach(function (cellData, index) {
        // If the cell contains an input, extract its value
        if (index === 2) {
          // Adjust index as per your table structure
          let parser = new DOMParser();
          let doc = parser.parseFromString(cellData, "text/html");
          let input = doc.querySelector("input");
          let value = input ? input.value : ""; // Use input value if it exists
          htmlContent += `<td>${value}</td>`;
        } else {
          htmlContent += `<td>${cellData}</td>`;
        }
      });
      htmlContent += "</tr>";
    });

    htmlContent += "</table>";
    return htmlContent;
  };

  let openModal = () => {
    let tableHTML = buildTableData(); // capture the latest state of the DataTable
    tablePreview.forEach((table) => (table.innerHTML = tableHTML));
    // document.getElementById('tablePreview').innerHTML = tableHTML;
    modal.classList.remove("hidden");
  };

  let closeModal = () => {
    modal.classList.add("hidden");
    emailModal.classList.add("hidden");
    testToolsModal.classList.add("hidden");
  };
  emailBtn?.addEventListener("click", function () {
    emailModal.classList.remove("hidden");
    openModal();
  });
  closeBtn.forEach((btn) => btn.addEventListener("click", closeModal));

  emailForm.addEventListener("submit", function (event) {
    event.preventDefault(); // prevent default action
    // Collect form data
    let formData = new FormData(event.target);

    // Collect and serialize table data
    let tableHTML = buildTableData(); // get the table HTML
    formData.append("tableHTML", tableHTML); // append the table HTML to formData
    // console.log("FormData values:", Array.from(formData.entries()));
    fetch("/wp-content/themes/gws-web/rapid-quote/emailForm.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        // console.log(response);
        if (!response.ok) {
          throw new Error("Newwork response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        // console.log("Response Data:", data);
        // Check if the email was sent successfully
        if (data.message === "Email sent successfully.") {
            // Redirect to the thank you page
            window.location.href = '/rapid-quote-success';
        } else {
            // Handle the case where the email wasn't sent successfully
            console.error("Email sending failed:", data.error);
        }
      })
      .catch((error) => console.error("Error: ", error));

    closeModal();
  });
  testToolsForm.addEventListener("submit", function (event) {
    event.preventDefault(); // prevent default action
    // Collect form data
    let formData = new FormData(event.target);

    // Collect and serialize table data
    let tableHTML = buildTableData(); // get the table HTML
    formData.append("tableHTML", tableHTML); // append the table HTML to formData
    // console.log("FormData values:", Array.from(formData.entries()));
    fetch("/wp-content/themes/gws-web/rapid-quote/emailTestTools.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        // console.log(response);
        if (!response.ok) {
          throw new Error("Newwork response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        // console.log("Response Data:", data);
        // Check if the email was sent successfully
        if (data.message === "Email sent successfully.") {
            // Redirect to the thank you page
            window.location.href = '/test-tools-success';
        } else {
            // Handle the case where the email wasn't sent successfully
            console.error("Email sending failed:", data.error);
        }
      })
      .catch((error) => console.error("Error: ", error));

    closeModal();
  });

  testToolsBtn?.addEventListener("click", function () {
    testToolsModal.classList.remove("hidden");
    openModal();
  });

  let printBtn = document.getElementById("printBtn");

  printBtn.addEventListener("click", function () {
    table.button(".buttons-print").trigger();
  });
});
