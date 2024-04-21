function addToCart(productId) {
  console.log("Adding to cart ", productId);
  fetch("/cart/add?productId=" + productId)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }

      return response.json();
    })
    .then((data) => {
      // Get the table body
      let tbody = document.querySelector("#cart-table tbody");
      let element = document.getElementById("deleteOnRefresh");
      if (element) {
        element.remove();
      }

      tbody.innerHTML = "";
      let miniCart = data.cart;

      // Add the updated rows
      for (let id in miniCart) {
        let product = miniCart[id];
        let tr = document.createElement("tr");
        tr.classList.add("border-bottom");

        tr.innerHTML = `
          <td><img src="${product.productImage}" alt="${product.productName}" style="width: 50px; height: 50px;"></td>
          <td class="text-capitalize">${product.productName}</td>
          <td><span class='mini-add' onclick='addToCart(${product.productId})'>+</span>${product.productQty}<span class='mini-add' onclick='removeOne(${product.productId})'>-</span></td>
          <td>${product.productTotalRowPrice} kr</td>
        `;

        tbody.appendChild(tr);
      }

      console.log(data.cart);
      console.log("ajax done  Product with id " + productId + " added to cart");

      let counter = document.getElementById("cart-counter");
      counter.textContent = parseInt(counter.textContent) + 1;

      // Create a Bootstrap Toast
      let toast = document.createElement("div");
      toast.classList.add(
        "toast",
        "text-white",
        "p-3",
        "position-fixed",
        "bottom-0",
        "start-0",
        "m-3"
      );
      toast.style.backgroundColor = "rgba(40, 167, 69, 0.75)"; // bg-success color with 75% opacity
      toast.setAttribute("role", "alert");
      toast.setAttribute("aria-live", "assertive");
      toast.setAttribute("aria-atomic", "true");
      toast.setAttribute("data-bs-delay", "2000");
      toast.innerHTML = `
        <div class="toast-body">
          ${data.msg}
        </div>
      `;

      document.body.appendChild(toast);

      // Show the toast and remove it after it hides
      let bsToast = new bootstrap.Toast(toast);
      bsToast.show();
      toast.addEventListener("hidden.bs.toast", function () {
        document.body.removeChild(toast);
      });
    })
    .catch((error) => {
      console.error("Error adding product to cart: ", error);
    });
}

function removeCartItem(productId) {
  console.log("Removing from cart ", productId);

  fetch("/cart/remove?productId=" + productId)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.text();
    })
    .then((data) => {
      console.log(
        "remove whole done  Product with id " + productId + " removed from cart"
      );
      location.reload();
    })
    .catch((error) => {
      console.error("Error removing product from cart: ", error);
    });
}

function removeOne(productId) {
  console.log("removing one from cart ", productId);
  fetch("/cart/removeOne?productId=" + productId)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }

      return response.json();
    })
    .then((data) => {
      console.log(data.cart);

      let tbody = document.querySelector("#cart-table tbody");
      let element = document.getElementById("deleteOnRefresh");
      if (element) {
        element.style.display = "none !important";
      }

      tbody.innerHTML = "";
      let miniCart = data.cart;

      // Add the updated rows
      for (let id in miniCart) {
        let product = miniCart[id];
        let tr = document.createElement("tr");
        tr.classList.add("border-bottom");

        tr.innerHTML = `
          <td><img src="${product.productImage}" alt="${product.productName}" style="width: 50px; height: 50px;"></td>
          <td class="text-capitalize">${product.productName}</td>
          <td><span class='mini-add' onclick='addToCart(${product.productId})'>+</span>${product.productQty}<span class='mini-add' onclick='removeOne(${product.productId})'>-</span></td>
          <td>${product.productTotalRowPrice} kr</td>
        `;

        tbody.appendChild(tr);
      }

      console.log(
        "remove one done  Product with id " + productId + " added to cart"
      );

      let counter = document.getElementById("cart-counter");
      counter.textContent = parseInt(counter.textContent) - 1;

      // Create a Bootstrap Toast
      let toast = document.createElement("div");
      toast.classList.add(
        "toast",
        "text-white",
        "p-3",
        "position-fixed",
        "bottom-0",
        "start-0",
        "m-3"
      );
      toast.style.backgroundColor = "rgba(255, 0, 0, 0.75)";
      toast.setAttribute("role", "alert");
      toast.setAttribute("aria-live", "assertive");
      toast.setAttribute("aria-atomic", "true");
      toast.setAttribute("data-bs-delay", "2000");
      toast.innerHTML = `
        <div class="toast-body">
          ${data.msg}
        </div>
      `;

      document.body.appendChild(toast);

      // Show the toast and remove it after it hides
      let bsToast = new bootstrap.Toast(toast);
      bsToast.show();
      toast.addEventListener("hidden.bs.toast", function () {
        document.body.removeChild(toast);
      });
    })
    .catch((error) => {
      console.error("Error removing product from cart: ", error);
    });
}
