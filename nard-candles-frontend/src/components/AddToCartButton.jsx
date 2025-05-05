// src/components/AddToCartButton.jsx
import { useState, useCallback, memo } from 'react';
import PropTypes from 'prop-types';
import { useAuth } from '../context/AuthContext';
import './AddToCartButton.css';
import LoginModal from './auth/LoginModal';

const AddToCartButton = memo(({ product }) => {
    const [isAdding, setIsAdding] = useState(false);
    const [message, setMessage] = useState(null);
    const [showLoginModal, setShowLoginModal] = useState(false);
    
    const { user, isAuthenticated } = useAuth();

    const handleAddToCart = useCallback(async () => {
        if (!isAuthenticated) {
            setShowLoginModal(true);
            return;
        }
        
        setIsAdding(true);
        setMessage(null);
        
        try {
            const response = await fetch('http://127.0.0.1:8000/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
                body: JSON.stringify({
                    product_name: product.name,
                    price: product.price,
                    quantity: 1
                })
            });

            if (!response.ok) {
                throw new Error('Failed to add to cart');
            }

            setMessage({ type: 'success', text: 'Added to cart!' });
            setTimeout(() => setMessage(null), 3000);
        } catch (error) {
            console.error('Error:', error);
            setMessage({ type: 'error', text: 'Failed to add to cart. Please try again.' });
        } finally {
            setIsAdding(false);
        }
    }, [isAuthenticated, product]);

    const handleLoginSuccess = useCallback(() => {
        setMessage({ type: 'success', text: 'Login successful! You can now add items to cart.' });
        setTimeout(() => setMessage(null), 3000);
    }, []);

    return (
        <div className="add-to-cart-container">
            <button 
                className="add-to-cart-button" 
                onClick={handleAddToCart}
                disabled={isAdding}
            >
                {isAdding ? 'Adding...' : 'Add to Cart'}
            </button>
            
            {message && (
                <div className={`cart-message ${message.type}`}>
                    {message.text}
                </div>
            )}
            
            <LoginModal 
                isOpen={showLoginModal}
                onClose={() => setShowLoginModal(false)}
                showMessage={handleLoginSuccess}
            />
        </div>
    );
});

AddToCartButton.propTypes = {
    product: PropTypes.shape({
        id: PropTypes.number,
        name: PropTypes.string.isRequired,
        price: PropTypes.number.isRequired
    }).isRequired
};

export default AddToCartButton;
