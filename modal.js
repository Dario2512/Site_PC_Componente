// Funcție pentru a deschide fereastra modala
function openCartModal() {
    document.getElementById('cartModal').style.display = 'block';
    
    // continut cart
    fetch('view_cart.php')
      .then(response => response.text())
      .then(html => {
        document.getElementById('cartContent').innerHTML = html;
      })
      .catch(error => console.error('Eroare: ', error));
  }
  
  // func pt inchiderea ferestrei modal
  function closeCartModal() {
    document.getElementById('cartModal').style.display = 'none';
  }
  
  // actiune pt apasare buton viewcartbutton
  document.getElementById('viewCartButton').addEventListener('click', openCartModal);
  