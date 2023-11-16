function searchProducts() {
    const searchInput = document.getElementById('search-input').value;

    fetch('search.php?query=' + encodeURIComponent(searchInput))
        .then(response => response.text())
        .then(html => {
            const productContainer = document.getElementById('product-list');
            productContainer.innerHTML = html;
        })
        .catch(error => console.error('Eroare la căutare: ', error));
}