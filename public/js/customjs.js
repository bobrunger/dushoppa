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
      console.log(data);
      console.log("ajax done  Product with id " + productId + " added to cart");

      let counter = document.getElementById("cart-counter");
      counter.textContent = parseInt(counter.textContent) + 1;
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
        "ajax done  Product with id " + productId + " removed from cart"
      );
      location.reload();
    })
    .catch((error) => {
      console.error("Error removing product from cart: ", error);
    });
}

function removeOne(productId) {
  console.log("removing from minicart ", productId);
  fetch("/cart/removeOne?productId=" + productId)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }

      return response.json();
    })
    .then((data) => {
      console.log(data);
      console.log(
        "ajax done  Product with id " + productId + " removed from cart"
      );

      let counter = document.getElementById("cart-counter");
      counter.textContent = parseInt(counter.textContent) - 1;
    })
    .catch((error) => {
      console.error("Error removing one product from cart: ", error);
    });
}

function viewMiniCart() {
  const miniCartTable = document.querySelector(".minicart-table tbody");
  if (!miniCartTable) {
    console.error("Mini cart table not found");
    return;
  }

  // Create spinner
  const spinner = document.createElement("div");
  spinner.className = "spinner-border";
  spinner.role = "status";
  const spinnerText = document.createElement("span");
  spinnerText.className = "sr-only";
  spinnerText.textContent = "Loading...";
  spinner.appendChild(spinnerText);

  // Append spinner to miniCartTable
  miniCartTable.appendChild(spinner);

  fetch("/cart/minicart")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      miniCartTable.innerHTML = ""; // Clear the existing table content

      if (data.length === 0) {
        const emptyCartMessage = document.createElement("li");
        emptyCartMessage.classList.add("dropdown-item");
        emptyCartMessage.textContent = "Your cart is empty";
        miniCartTable.parentNode.insertBefore(emptyCartMessage, miniCartTable);
      } else {
        data.forEach((product) => {
          if (product.miniQty > 0) {
            const row = document.createElement("tr");
            row.classList.add("border-bottom");

            const imgCell = document.createElement("td");
            const img = document.createElement("img");
            img.src = product.miniImage;
            img.alt = product.miniName;
            img.style.width = "50px";
            img.style.height = "50px";
            imgCell.appendChild(img);

            const nameCell = document.createElement("td");
            nameCell.classList.add("text-capitalize");
            nameCell.textContent = product.miniName;

            const qtyCell = document.createElement("td");
            qtyCell.textContent = product.miniQty;
            // Create "+" button
            const addButton = document.createElement("button");
            addButton.textContent = "+";
            addButton.classList.add("px-2", "btn", "text-danger");
            addButton.addEventListener("click", function () {
              addToCart(product.miniId);
            });
            // Create "-" button
            const removeButton = document.createElement("button");
            removeButton.textContent = "-";
            removeButton.classList.add("px-2", "btn", "text-danger");
            removeButton.addEventListener("click", function () {
              removeOne(product.miniId);
            });

            // Append buttons to qtyCell
            $(qtyCell).prepend(addButton);
            qtyCell.appendChild(removeButton);

            const priceCell = document.createElement("td");
            priceCell.textContent = `${product.miniPrice} kr`;

            row.appendChild(imgCell);
            row.appendChild(nameCell);
            row.appendChild(qtyCell);
            row.appendChild(priceCell);

            miniCartTable.appendChild(row);
          }
        });
      }
    })
    .catch((error) => {
      console.error("error loading mini cart: ", error);
    })
    .finally(() => {
      // Remove spinner
      spinner.remove();
    });
}
