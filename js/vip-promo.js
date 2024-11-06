document.addEventListener('DOMContentLoaded', function() {
    // User tier and advanced performance flag (these should be dynamically set from the backend)
    const userTier = "t2"; // Example tier, replace with actual dynamic value
    const isAdvancedPerformance = false; // Example flag, replace with actual dynamic value

    // Function to calculate the base discount rate based on user tier
    const calculateDiscount = (tier, isAdvancedPerformance) => {
        let discountRate;
        switch (tier) {
            case "t1": discountRate = 0.55; break;
            case "t2": discountRate = 0.525; break;
            case "t3": discountRate = 0.50; break;
            case "57_5": discountRate = 0.575; break;
            case "direct": discountRate = 0.30; break;
            case "none": discountRate = isAdvancedPerformance ? 0 : 1; break;
            default: discountRate = isAdvancedPerformance ? 0 : 1;
        }
        return discountRate;
    };

    // Function to set initial net prices based on user tier
    function initializeNetPrices() {
        document.querySelectorAll('.quantity-input').forEach(input => {
            const listPrice = parseFloat(input.dataset.price);
            const discountRate = calculateDiscount(userTier, isAdvancedPerformance);
            const tierPrice = listPrice * (1 - discountRate);

            const netPriceCell = input.closest('tr').querySelector('.net-price');
            netPriceCell.innerHTML = `$${tierPrice.toFixed(2)}<br><span class="discount-percent text-xs italic" style="color:indianred;"></span>`;

            const totalPriceCell = input.closest('tr').querySelector('.total-price');
            totalPriceCell.textContent = `$0.00`;
        });
    }

    // Function to update the email preview with rows that have a non-zero quantity
    function updateEmailPreview() {
        const previewContainer = document.getElementById('emailPreview');
        previewContainer.innerHTML = ''; // Clear previous content
    
        // Define the table headers in HTML with inline styles to match the original table appearance
        const tableHeaders = `
            <div style="overflow-x:auto; box-shadow:0px 4px 8px rgba(0, 0, 0, 0.1); border-radius:0.5rem;">
                <table style="width:100%; font-size:0.875rem; text-align:left; color:#6B7280; border-collapse:collapse;">
                    <thead style="font-size:0.75rem; color:#374151; background-color:#F9FAFB;">
                        <tr>
                            <th style="padding:0.75rem; border-bottom:1px solid #E5E7EB; text-transform:uppercase;">Part</th>
                            <th style="padding:0.75rem; border-bottom:1px solid #E5E7EB; text-transform:uppercase;">Description</th>
                            <th style="padding:0.75rem; border-bottom:1px solid #E5E7EB; text-transform:uppercase;">Tool</th>
                            <th style="padding:0.75rem; border-bottom:1px solid #E5E7EB; text-transform:uppercase;">Stock</th>
                            <th style="padding:0.75rem; border-bottom:1px solid #E5E7EB; text-transform:uppercase;">Quantity</th>
                            <th style="padding:0.75rem; border-bottom:1px solid #E5E7EB; text-transform:uppercase;">Net Price Each</th>
                            <th style="padding:0.75rem; border-bottom:1px solid #E5E7EB; text-transform:uppercase;">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
    
        let tableContent = tableHeaders;
        let quoteTotal = 0; // Initialize Quote Total
    
        // Loop through each row with a non-zero quantity
        document.querySelectorAll('.quantity-input').forEach(input => {
            const quantity = parseInt(input.value) || 0;
            if (quantity > 0) {
                const row = input.closest('tr').cloneNode(true);
    
                // Calculate the total price for this row and add it to the quoteTotal
                const totalPriceCell = row.querySelector('.total-price').textContent.replace('$', '');
                const totalPrice = parseFloat(totalPriceCell) || 0;
                quoteTotal += totalPrice; // Add to the running Quote Total
    
                // Replace quantity input with static text and remove other inputs
                row.querySelector('.quantity-input').replaceWith(quantity);
                row.querySelectorAll('input').forEach(input => input.remove());
    
                // Construct the HTML for each row with similar styles
                tableContent += `
                    <tr style="background-color:#FFFFFF; border-bottom:1px solid #E5E7EB; color:#222;">
                        ${Array.from(row.children).map(cell => `
                            <td style="padding:0.5rem; border-bottom:1px solid #E5E7EB;">${cell.innerHTML}</td>
                        `).join('')}
                    </tr>
                `;
            }
        });
    
        // Close the table tags
        tableContent += `
            </tbody>
            </table>
            <p style="text-align:right; font-weight:bold; padding:0.5rem; color:#222;">
                Quote Total: $${quoteTotal.toFixed(2)}
            </p>
            </div>
        `;
    
        // Set the final table content with headers and Quote Total into the preview container
        previewContainer.innerHTML = tableContent;
    }
    

    // Event listener to update prices and email preview based on quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function() {
            const quantity = parseFloat(this.value) || 0;
            const listPrice = parseFloat(this.dataset.price);
            const discountRate = calculateDiscount(userTier, isAdvancedPerformance);
            const basePrice = listPrice * (1 - discountRate);
            const netPriceCell = this.closest('tr').querySelector('.net-price');
            const totalPriceCell = this.closest('tr').querySelector('.total-price');
            const discountPercentCell = this.closest('tr').querySelector('.discount-percent');

            let quantityDiscount = 0;
            let percentText = '';
            if (quantity >= 1 && quantity <= 50) {
                quantityDiscount = 0.05;
                percentText = '5% off';
            } else if (quantity >= 51 && quantity <= 100) {
                quantityDiscount = 0.10;
                percentText = '10% off';
            }

            const finalNetPrice = basePrice * (1 - quantityDiscount);
            const totalPrice = (quantity * finalNetPrice).toFixed(2);

            netPriceCell.innerHTML = `$${finalNetPrice.toFixed(2)}<br><span class="discount-percent text-xs italic" style="color:indianred;">${percentText}</span>`;
            totalPriceCell.textContent = `$${totalPrice}`;
            updateEmailPreview();
        });
    });

    const form = document.getElementById('emailForm');
    const modalContent = form.parentElement;
    const modalFooter = document.getElementById('modalFooter'); // Get modalFooter element

    // Create success message with a "Start New Quote" button
    const successMessage = document.createElement('div');
    successMessage.innerHTML = `
        <h3 class="text-dark-blue text-2xl font-bold">Success!</h3>
        <p class="text-black">Your quote request has been sent successfully.</p>
        <p class="text-black">A member of our customer service team will get back to you shortly.</p>
        <button id="startNewQuoteButton" class="btn dark-blue mt-4">Start New Quote</button>
    `;
    successMessage.style.display = 'none'; // Initially hide success message
    modalContent.appendChild(successMessage);

    // Trigger form submission when the Send Email button is clicked
    document.getElementById('sendEmailButton').addEventListener('click', function() {
        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
    });

    // Form submission event listener
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        const emailPreviewContent = document.getElementById('emailPreview').innerHTML;
        formData.append('tableHTML', emailPreviewContent);

        fetch('/wp-content/themes/gws-web/vip-promo-email.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                form.style.display = 'none';
                successMessage.style.display = 'block';
                // reset input values
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.value = 0;
                });
                document.querySelectorAll('.discount-percent').forEach(cell => {
                    cell.textContent = '';
                });
                document.querySelectorAll('.total-price').forEach(price => {
                    price.textContent = `$0.00`;
                });
                modalFooter.style.display = 'none'; // Hide modalFooter on success
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error sending your email.');
        });
    });

    // Event listener for "Start New Quote" button to reset the form
    document.addEventListener('click', function(event) {
        if (event.target.id === 'startNewQuoteButton') {
            form.reset();
            form.style.display = 'block';
            successMessage.style.display = 'none';
            modalFooter.style.display = 'flex'; // Show modalFooter again
            document.getElementById('emailPreview').innerHTML = ''; // Clear email preview
        }
    });

    // Reset modal when it’s closed or reopened
    document.addEventListener('click', function(event) {
        if (event.target.matches('[data-modal-hide]')) {
            form.reset();
            form.style.display = 'block';
            successMessage.style.display = 'none';
            modalFooter.style.display = 'flex'; // Show modalFooter again
            document.getElementById('emailPreview').innerHTML = '';
        }
    });

    initializeNetPrices();
});
