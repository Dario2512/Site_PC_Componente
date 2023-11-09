document.addEventListener('DOMContentLoaded', function () {
    // Obtine containerul produselor
    const productContainer = document.getElementById('product-list');

    // Obtine toate  produsele
    const productCards = productContainer.querySelectorAll('.product-card');

    // Itereaza prin fiecare produs și personalizează aspectul
    productCards.forEach(function(card) {
        // Stil card
        card.style.border = '1px solid #ddd';
        card.style.margin = '10px';
        card.style.padding = '10px';
        card.style.borderRadius = '10px'; // Adaugă colțuri rotunjite

        // Creează un buton "Cumpără" si adauga la card
        const buyButton = document.createElement('button');
        buyButton.textContent = 'Cumpără';
        buyButton.className = 'buy-button'; // adauga o clasa css

        // Adauga un eveniment la butonul "Cumpără"
        buyButton.addEventListener('click', function() {
            //logica pt cumparare, mai adaugi chesti daca vrei sa mai face cv
            alert('Produs cumpărat: ' + card.querySelector('h2').textContent);
        });

        // Adaugă butonul la card
        card.appendChild(buyButton);
    });
});
