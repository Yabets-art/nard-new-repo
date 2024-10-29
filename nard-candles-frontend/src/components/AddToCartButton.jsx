// src/components/AddToCartButton.jsx
import PropTypes from 'prop-types';

const AddToCartButton = ({ productId }) => {
    const handleAddToCart = () => {
        fetch('http://127.0.0.1:8000/api/user-cart', { // Ensure the URL matches the backend API
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Ensure CSRF token is correct
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1 // or get this from user input
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Handle success
            alert('Product added to cart!');
        })
        .catch(error => console.error('Error:', error));
    };

    return (
        <button onClick={handleAddToCart}>Add to Cart</button>
    );
};

AddToCartButton.propTypes = {
    productId: PropTypes.number.isRequired // Ensure productId is a number and is required
};

export default AddToCartButton;
