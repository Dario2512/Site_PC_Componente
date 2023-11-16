function displayProducts(products) {
    const productContainer = document.getElementById('product-list');
    productContainer.innerHTML = ''; 

    products.forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';

        const title = document.createElement('h2');
        title.textContent = product.name;

        const image = document.createElement('img');
        image.src = product.picture;

        const tags = document.createElement('p');
        tags.textContent = 'Tags: ' + product.tags.join(', '); 

        const buyButton = document.createElement('button');
        buyButton.textContent = 'Cumpără';
        buyButton.className = 'buy-button';

        buyButton.addEventListener('click', function () {
            alert('Produs cumpărat: ' + product.name);
        });

        card.appendChild(title);
        card.appendChild(image);
        card.appendChild(tags);
        card.appendChild(buyButton);

        productContainer.appendChild(card);
    });
}

displayProducts(products);

document.addEventListener('DOMContentLoaded', function () {
    const tagDropdown = document.getElementById('tag-dropdown');
    const allTags = Array.from(new Set(products.flatMap(product => product.tags)));

    allTags.forEach(tag => {
        const option = document.createElement('option');
        option.value = tag;
        option.textContent = tag;
        tagDropdown.appendChild(option);
    });

    tagDropdown.addEventListener('change', function () {
        const selectedTag = tagDropdown.value;
        const filteredProducts = products.filter(product => product.tags.includes(selectedTag) || selectedTag === 'All');
        displayProducts(filteredProducts);
    });
});
