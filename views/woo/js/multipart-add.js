document.addEventListener("DOMContentLoaded", () => {
  const bulkForm = document.getElementById("bulk-add-form");
  const feedback = document.getElementById("bulk-add-feedback");

  if (!bulkForm) return;

  bulkForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const textarea = document.getElementById("bulk-parts");
    const partNumbers = textarea.value
      .split("\n")
      .map((p) => p.trim())
      .filter(Boolean);

    if (partNumbers.length === 0) {
      feedback.textContent = "Please enter at least one part number.";
      return;
    }

    feedback.textContent = "Adding parts…";

    try {
      const res = await fetch("/wp-admin/admin-ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          action: "bulk_add_to_cart",
          parts: JSON.stringify(partNumbers),
        }),
      });

      const data = await res.json();

      feedback.innerHTML = data.message || "Something went wrong.";

      if (data.success) {
        feedback.innerHTML = data.message || "Parts added to quote.";
        await refreshCartTable();
        document.getElementById('bulk-parts').value = '';
      } else {
        feedback.innerHTML = data.message || "Something went wrong.";
      }
    } catch (err) {
      console.error(err);
      feedback.textContent = "Error adding products. Try again.";
    }
  });
});

// Custom refresh for your cart table
async function refreshCartTable() {
  try {
    const res = await fetch(window.location.href, { method: "GET" });
    const html = await res.text();

    const parser = new DOMParser();
    const doc = parser.parseFromString(html, "text/html");

    // replace cart table body
    const newTbody = doc.querySelector("#cart-table tbody");
    const currentTbody = document.querySelector("#cart-table tbody");
   
    currentTbody.replaceWith(newTbody);
   

    // replace cart totals if you have a separate section
    const newTotals = doc.querySelector(".cart_totals");
    const currentTotals = document.querySelector(".cart_totals");
    if (newTotals && currentTotals) {
      currentTotals.replaceWith(newTotals);
    }
  } catch (err) {
    console.error("Error refreshing cart table:", err);
  }
}