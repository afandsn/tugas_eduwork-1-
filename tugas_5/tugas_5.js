document.addEventListener("DOMContentLoaded", function () {
  console.log("Halaman selesai dimuat, memuat produk...");
  displayProducts(products);
});

const products = [
  {
    name: "Laptop",
    category: "elektronik",
    description: "Laptop gaming terbaik!",
    image: "https://via.placeholder.com/200",
  },
  {
    name: "Smartphone",
    category: "elektronik",
    description: "Smartphone dengan kamera canggih.",
    image: "https://via.placeholder.com/200",
  },
  {
    name: "T-Shirt",
    category: "fashion",
    description: "Kaos nyaman dan keren.",
    image: "https://via.placeholder.com/200",
  },
  {
    name: "Jaket",
    category: "fashion",
    description: "Jaket hangat dan stylish.",
    image: "https://via.placeholder.com/200",
  },
  {
    name: "Burger",
    category: "makanan",
    description: "Burger lezat dengan daging sapi premium.",
    image: "https://via.placeholder.com/200",
  },
  {
    name: "Pizza",
    category: "makanan",
    description: "Pizza keju meleleh dengan topping pilihan.",
    image: "https://via.placeholder.com/200",
  },
];

function displayProducts(filteredProducts) {
  console.log("Menampilkan produk:", filteredProducts);
  const container = document.getElementById("productContainer");
  container.innerHTML = "";

  filteredProducts.forEach((product) => {
    console.log("Produk:", product.name);
    const productHTML = `
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="${product.image}" class="card-img-top" alt="${product.name}">
                <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text">${product.description}</p>
                </div>
            </div>
        </div>
      `;
    container.innerHTML += productHTML;
  });
}

document
  .getElementById("categoryFilter")
  .addEventListener("change", function () {
    const selectedCategory = this.value;
    if (selectedCategory === "all") {
      displayProducts(products);
    } else {
      const filteredProducts = products.filter(
        (product) => product.category === selectedCategory
      );
      displayProducts(filteredProducts);
    }
  });

// Tampilkan semua produk saat halaman dimuat
displayProducts(products);
