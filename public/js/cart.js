// Handle add to cart without redirection
function addToCart(productId) {
    $.ajax({
        url: `/cart/add/${productId}`,
        type: "POST",
        success: function (response) {
            alert(response.success); // Display success message
            // Update cart icon or count if needed
        },
    });
}

function updateCartQuantity(cartItemId, quantity) {
    if (quantity <= 0) return;

    fetch(`/products/cart/update/${cartItemId}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ quantity: quantity }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json(); // Expect JSON response from server
        })
        .then((data) => {
            if (data.success) {
                document.querySelector(
                    `#item-total-${cartItemId}`
                ).textContent = (data.itemTotalPrice / 100).toFixed(2);
                updateCartTotal(data.totalPrice);
            } else {
                alert("Failed to update cart");
            }
        })
        .catch((error) => console.error("Error:", error));
}

function updateCartTotal(totalPrice) {
    document.querySelector("#cart-total").textContent =
        "Total: $" + (totalPrice / 100).toFixed(2);
}

function updateSelectedTotal() {
    let total = 0;

    document
        .querySelectorAll(".cart-item-checkbox:checked")
        .forEach((checkbox) => {
            let card = checkbox.closest(".card");
            let itemTotal = parseFloat(
                card.querySelector(".card-text span").textContent
            );
            total += itemTotal;
        });

    document.querySelector("#cart-total").textContent =
        "Total: $" + total.toFixed(2);
}

document.addEventListener("DOMContentLoaded", function () {
    updateSelectedTotal();
});
