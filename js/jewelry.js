// Enhanced version with localStorage example
document.addEventListener('DOMContentLoaded', function() {
    // Load cart count from storage
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const savedCount = localStorage.getItem('cartCount') || 0;
        cartCount.textContent = savedCount;
    }

    // Add to cart functionalityf
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productCard = this.closest('.product-card');
            const product = {
                name: productCard.querySelector('h2').textContent,
                price: productCard.querySelector('.price').textContent,
                image: productCard.querySelector('img').src
            };
            
            // Save to localStorage
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.push(product);
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Update count
            const currentCount = parseInt(localStorage.getItem('cartCount')) || 0;
            const newCount = currentCount + 1;
            localStorage.setItem('cartCount', newCount);
            if (cartCount) cartCount.textContent = newCount;
            
            // Visual feedback
            this.textContent = 'Added!';
            setTimeout(() => this.textContent = 'Add to Cart', 2000);
        });
    });
});