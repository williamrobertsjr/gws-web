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
  console.log(window.userRole)
  let userRole = window.userRole || "none"; // Replace 'defaultRole' with an appropriate default
  console.log(userRole)
  if (userRole === "administrator" || userRole === "sales") {
    document
      .getElementById("distributor-level-message")
      .classList.add("hidden");
  } else if (userRole === "none") {
    document.getElementById("distributor-level-select").classList.add("hidden");
    // document.getElementById("testToolsBtn").classList.add("hidden");
  } else {
    document.getElementById("distributor-level-select").classList.add("hidden");
  }

  // get users tier
  const getUserTier = () => {
    if (userRole === "administrator" || userRole === "sales") {
      let distributorTierSelect = document.getElementById("distributor-level");
      let distributorTier = distributorTierSelect.value;
      console.log(distributorTier);
      return distributorTier;
    } else {
      console.log(userRole);
      return userRole;
    }
  };

  // // Updated calculateDiscount function
  // const calculateDiscount = (tier, isAdvancedPerformance) => {
  //   let discountRate;

  //   switch (tier) {
  //     case "elite":
  //       discountRate = isAdvancedPerformance ? 0.575 : 0.55;
  //       break;
  //     case "t1":
  //       discountRate = isAdvancedPerformance ? 0.55 : 0.54;
  //       break;
  //     case "t2":
  //       discountRate = isAdvancedPerformance ? 0.5 : 0.52;
  //       break;
  //     case "t3":
  //       discountRate = isAdvancedPerformance ? 0.45 : 0.51;
  //       break;
  //     case "ta":
  //       discountRate = isAdvancedPerformance ? 0.4 : 0.5;
  //       break;
  //     case "none":
  //       discountRate = isAdvancedPerformance ? 0 : 0;
  //       break;
  //     default:
  //       discountRate = isAdvancedPerformance ? 0 : 0;
  //   }

  //   return discountRate;
  // };
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

  // Updated updatePrices function to change data tables data not just dom
  const updatePrices = () => {
    let userTier = getUserTier();

    document.querySelectorAll("td.rq-net-price").forEach((netPriceCell) => {
      let rowElement = netPriceCell.closest("tr");
      let row = table.row(rowElement);
      let rowData = row.data();

      let partFamilyCell = rowElement.querySelector("td.rq-edp > a");
      let isAdvancedPerformance =
        partFamilyCell && partFamilyCell.classList.contains("Advanced");

      let discount = calculateDiscount(userTier, isAdvancedPerformance);

      let listPriceCell = rowElement.querySelector("td.rq-list-price");
      let listPrice = parseFloat(listPriceCell.innerText);

      let qtyInput = rowElement.querySelector("#neededQty");
      let quantity = parseInt(qtyInput.value, 10);

      let discountedPrice = listPrice * (1 - discount);
      let totalPrice = discountedPrice * quantity;

      // Update the DOM
      netPriceCell.innerText = discountedPrice.toFixed(2);
      let totalPriceCell = rowElement.querySelector("td.rq-total-price");
      totalPriceCell.innerText = "$" + totalPrice.toFixed(2);

      // Update the DataTables internal data
      rowData[5] = discountedPrice.toFixed(2); // use row index of net price to update data table
      rowData[6] = "$" + totalPrice.toFixed(2); // use row index of total price to update data table

      // Invalidate the row to ensure the internal cache is updated
      row.invalidate();
    });

    // Redraw the table after updating prices
    table.draw(false); // 'false' keeps the current paging position
  };

  partsForm.addEventListener("submit", function (event) {
    console.log("parts form intercepted");
    event.preventDefault();

    const data = new FormData(partsForm);
    // console.log([...data])
    if (data.has("part")) {
      let partsString = data.get("part");
      if (partsString) {
        let partsArray = partsString
          .split(/[\s,]+/)
          .filter((part) => part.trim() !== "");
        // console.log(partsArray);
      } else {
        console.log("No parts input found");
      }
    } else {
      console.error("The 'partsInput' field is not found in the form data");
    }

    fetch("https://dev.gwstoolgroup.com/wp-json/rapid-quote/v1/submit-quote", {
      method: "POST",
      body: data,
    })
      .then((res) => {
        if (!res.ok) {
          throw new Error("Network response was not ok");
        }
        return res.json();
      })
      .then((data) => {
        // Update your page based on the response
        // Clear the current table data
        table.clear();
        if (data.found_parts && Array.isArray(data.found_parts)) {
          data.found_parts.forEach((part) => {
            partLink =
              '<a class="' +
              part.FAMILY +
              ' font-bold text-dark-blue"' +
              'href="https://staging.gwstoolgroup.com/product/?part=' +
              part.PN +
              ' "' +
              'target="_blank">' +
              part.PN +
              "</a>";
            let qtyOnHand = Math.floor(part.QTY_ON_HAND, 10);
            let neededQty =
              '<input type="number" id="neededQty" name="neededQty" min="1" max="1000" value="1" class="flex w-full px-2"></input>';
            let totalPrice = part.LIST_PRICE;
            let netPrice = part.LIST_PRICE;
            let partFamily = part.FAMILY;
            // Add rows to the table for each found part Modify as per your JSON structure
            table.row.add([
              partLink, // EDP with link
              part.FULL_DESCRIPTION, // FULL DESCRIPTION
              neededQty, // QTY NEEDED
              qtyOnHand, // STOCK
              part.LIST_PRICE, // LIST PRICE
              netPrice,
              totalPrice, // TOTAL PRICE
            ]);
          });
        }

        // Draw the table with the new data
        table.draw();
        updatePrices();

        document.addEventListener("change", function (event) {
          if (event.target && event.target.id === "distributor-level") {
            updatePrices();
          }
        });

        // Handle missing parts if any
        if (data.missing_parts) {
          let missingPartsDiv = document.getElementById("missing-parts");
          missingPartsDiv.innerHTML = `<p class="text-white text-sm italic">These parts were not found: ${data.missing_parts}. <span>Please verify part numbers.</span></p> `;
          console.log("Missing parts:", data.missing_parts);
        }
      })
      .catch((err) => {
        console.log(err);
        // Display error message to the user
      });
  });

  document
    .querySelector("#rq-table")
    .addEventListener("input", function (event) {
      // Check if the event target is within a cell with the 'rq-needed' class
      if (event.target.closest(".rq-needed")) {
        let rowElement = event.target.closest("tr");
        if (!rowElement) {
          console.error("No table row found for the event target");
          return;
        }

        let row = table.row(rowElement);
        let data = row.data();

        // Assuming the 'Needed' column is at a specific index
        let neededQtyColumnIndex = 2; // Adjust this index as necessary
        let inputCell = rowElement.cells[neededQtyColumnIndex];
        if (inputCell) {
          let inputCellHtml = inputCell.innerHTML;
          inputCellHtml = inputCellHtml.replace(
            /value=".*?"/,
            `value="${event.target.value}"`
          );
          data[neededQtyColumnIndex] = inputCellHtml;

          row.data(data).invalidate(); // Invalidate to ensure the update
          updatePrices(); // Update prices based on the new quantity
        } else {
          console.error("Cell not found at the specified index in the row");
        }
      }
    });

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
    console.log("FormData values:", Array.from(formData.entries()));
    fetch("/wp-content/themes/gws-web/rapid-quote/emailForm.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        console.log(response);
        if (!response.ok) {
          throw new Error("Newwork response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        console.log("Response Data:", data);
        // Check if the email was sent successfully
        if (data.message === "Email sent successfully.") {
            // Redirect to the thank you page
            window.location.href = 'https://staging.gwstoolgroup.com/rapid-quote-success';
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
    console.log("FormData values:", Array.from(formData.entries()));
    fetch("/wp-content/themes/gws-web/rapid-quote/emailTestTools.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        console.log(response);
        if (!response.ok) {
          throw new Error("Newwork response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        console.log("Response Data:", data);
        // Check if the email was sent successfully
        if (data.message === "Email sent successfully.") {
            // Redirect to the thank you page
            window.location.href = 'https://staging.gwstoolgroup.com/test-tools-success';
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
