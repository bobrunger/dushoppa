async function fetchCart(url, productId) {
  const response = await fetch(`${url}?productId=${productId}`);
  if (!response.ok) {
    throw new Error("Network response was not ok");
  }
  return await response.json();
}

function createToast(message, backgroundColor) {
  const toast = document.createElement("div");
  toast.classList.add(
    "toast",
    "text-white",
    "p-3",
    "position-fixed",
    "bottom-0",
    "start-0",
    "m-3"
  );
  toast.style.backgroundColor = backgroundColor;
  toast.setAttribute("role", "alert");
  toast.setAttribute("aria-live", "assertive");
  toast.setAttribute("aria-atomic", "true");
  toast.setAttribute("data-bs-delay", "2000");
  toast.innerHTML = `
    <div class="toast-body">
      ${message}
    </div>
  `;

  document.body.appendChild(toast);

  const bsToast = new bootstrap.Toast(toast);
  bsToast.show();
  toast.addEventListener("hidden.bs.toast", function () {
    document.body.removeChild(toast);
  });
}

function updateCartTable(miniCart) {
  const tbody = document.querySelector("#cart-table tbody");
  const element = document.getElementById("deleteOnRefresh");
  if (element) {
    element.remove();
  }

  tbody.innerHTML = "";

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
}

async function addToCart(productId) {
  console.log("Adding to cart ", productId);
  try {
    const data = await fetchCart("/cart/add", productId);
    updateCartTable(data.cart);
    console.log(data.cart);
    console.log(`ajax done  Product with id ${productId} added to cart`);

    const counter = document.getElementById("cart-counter");
    counter.textContent = parseInt(counter.textContent) + 1;

    createToast(data.msg, "rgba(40, 167, 69, 0.75)");
  } catch (error) {
    console.error("Error adding product to cart: ", error);
  }
}

async function removeOne(productId) {
  console.log("removing one from cart ", productId);
  try {
    const data = await fetchCart("/cart/removeOne", productId);
    updateCartTable(data.cart);
    console.log(`remove one done  Product with id ${productId} added to cart`);

    const counter = document.getElementById("cart-counter");
    counter.textContent = parseInt(counter.textContent) - 1;

    createToast(data.msg, "rgba(255, 0, 0, 0.75)");
  } catch (error) {
    console.error("Error removing product from cart: ", error);
  }
}

async function removeCartItem(productId) {
  console.log("Removing from cart ", productId);
  try {
    await fetchCart("/cart/remove", productId);
    console.log(
      `remove whole done  Product with id ${productId} removed from cart`
    );
    location.reload();
  } catch (error) {
    console.error("Error removing product from cart: ", error);
  }
}
