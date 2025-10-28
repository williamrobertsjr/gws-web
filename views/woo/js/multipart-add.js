document.addEventListener("DOMContentLoaded", () => {
  const bulkForm = document.getElementById("bulk-add-form");
  const feedback = document.getElementById("bulk-add-feedback");
  const loadingBar = document.getElementById("loading-bar");
  const emptyCartMessage = document.querySelector(".empty-cart-message");

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

    if (emptyCartMessage) emptyCartMessage.style.display = "none";
    if (loadingBar) loadingBar.classList.add("loading");

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

      if (data.success) {
        const result = data.data || data;

        let messageHtml = "";
        if (result.added?.length) {
          // messageHtml += `<span style="color:green;">Added: ${result.added.join(", ")}</span><br>`;
        }
        if (result.not_found?.length) {
          messageHtml += `<span style="color:#fd978d; font-style:italic; font-size: 12px;">Products not found: ${result.not_found.join(", ")}. Please verify these part numbers.</span>`;
        }

        if (!messageHtml) {
          messageHtml = result.message || "No valid parts found.";
        }

        await refreshCartTable();
        await refreshCartCount(); // new line here

        async function refreshCartCount() {
          try {
            const res = await fetch("/wp-admin/admin-ajax.php?action=get_cart_count");
            const data = await res.json();
            if (data.success && data.data?.count !== undefined) {
              document.getElementById("cart-count").textContent = data.data.count;
            }
          } catch (err) {
            console.error("Error refreshing cart count", err);
          }
        }

        // Display feedback after table updates
        feedback.innerHTML = messageHtml;
        textarea.value = "";
      } else {
        feedback.innerHTML = data.message || "Something went wrong.";
      }
    } catch (err) {
      console.error(err);
      feedback.textContent = "Error adding products. Try again.";
    } finally {
      if (loadingBar) loadingBar.classList.remove("loading");
    }
  });

  // Clear cart functionality
  const clearBtn = document.getElementById("clear-cart");
  if (clearBtn) {
    clearBtn.addEventListener("click", async () => {
      if (!confirm("Are you sure you want to clear all items from the quote?")) return;

      feedback.textContent = "Clearing quote...";

      try {
        const res = await fetch("/wp-admin/admin-ajax.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams({ action: "clear_cart" }),
        });

        const data = await res.json();

        if (data.success) {
          feedback.textContent = "Quote cleared.";
          await refreshCartTable();
        } else {
          feedback.textContent = "Could not clear quote.";
        }
      } catch (err) {
        console.error(err);
        feedback.textContent = "Error clearing quote.";
      }
    });
  }
});