document.addEventListener('DOMContentLoaded', function () {
    function openCartModal() {
        document.getElementById('cartModal').style.display = 'block';

        fetch('view_cart.php')
            .then(response => response.text())
            .then(html => {
                document.getElementById('cartContent').innerHTML = html;
            })
            .catch(error => console.error('Eroare: ', error));
        let closeCartButton = document.getElementById('closeCartModalButton') || document.querySelector('.modal .close');
        if (closeCartButton) {
            closeCartButton.addEventListener('click', closeCartModal);
        } else {
            console.error('Butonul de închidere nu a putut fi găsit sau este null.');
        }
    }

    function closeCartModal() {
        document.getElementById('cartModal').style.display = 'none';
    }

    function emptyCart() {
        fetch('empty_cart.php')
            .then(response => response.text())
            .then(html => {
                document.getElementById('cartContent').innerHTML = html;

                fetch('view_cart.php')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('cartContent').innerHTML = html;
                    })
                    .catch(error => console.error('Eroare: ', error));
            })
            .catch(error => console.error('Eroare: ', error));
    }

    document.getElementById('viewCartButton').addEventListener('click', openCartModal);

    document.getElementById('cartModalBuyButton').addEventListener('click', function () {
        closeCartModal();
    });
    let closeButton = document.querySelector('.modal .close');
    if (closeButton) {
        closeButton.addEventListener('click', function () {
            closeCartModal();
        });
    } else {
        console.error('Butonul de închidere nu a putut fi găsit sau este null.');
    }
});
