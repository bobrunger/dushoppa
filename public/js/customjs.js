function addToCart(productId) {
  console.log("Adding to cart ", productId);
  fetch("/cart/add?productId=" + productId)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.text();
    })
    .then((data) => {
      console.log("ajax done  Product with id " + productId + " added to cart");
    })
    .catch((error) => {
      console.error("Error adding product to cart: ", error);
    });
}
