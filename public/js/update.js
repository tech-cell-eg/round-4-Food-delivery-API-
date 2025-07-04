document.addEventListener("DOMContentLoaded", function () {
    fetch("http://www.food-delivery.local/updates/cart", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: "Bearer " + localStorage.getItem("token"),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.items.length > 0) {
                const cartCount = document.getElementById("cart-count");
                cartCount.textContent = data.items.length;
                cartCount.classList.remove("d-none");
            }
        })
        .catch((error) => {
            console.error("Error fetching cart:", error);
        });
});
