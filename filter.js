document.addEventListener('DOMContentLoaded', function () {
    const tagDropdown = document.getElementById('tag-dropdown');

    function loadTags() {
        fetch('load_tags.php')
            .then(response => response.json())
            .then(tags => {
                tags.forEach(tag => {
                    const option = document.createElement('option');
                    option.value = tag;
                    option.textContent = tag;
                    tagDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Eroare încărcare etichete: ', error));
    }

    loadTags(); 

    function applyFilter() {
        const selectedTag = tagDropdown.value;
        if (selectedTag === 'All') {
            loadProducts();
        } else {
            fetch('filter_products.php?tag=' + encodeURIComponent(selectedTag))
                .then(response => response.text())
                .then(html => {
                    const productContainer = document.getElementById('product-list');
                    productContainer.innerHTML = html;
                })
                .catch(error => console.error('Eroare filtru produse: ', error));
        }
    }
    tagDropdown.addEventListener('change', applyFilter);

    function loadProducts() {
        fetch('products.php')
            .then(response => response.text())
            .then(html => {
                const productContainer = document.getElementById('product-list');
                productContainer.innerHTML = html;
            })
            .catch(error => console.error('Eroare încărcare produse: ', error));
    }
});
