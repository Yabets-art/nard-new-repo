import { useState, useCallback } from 'react';
import PropTypes from 'prop-types';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import LoginModal from './auth/LoginModal';
import './AddToCartButton.css'; // Reuse the same styles

const BuyNowButton = ({ product }) => {
    const [isProcessing, setIsProcessing] = useState(false);
    const [message, setMessage] = useState(null);
    const [showLoginModal, setShowLoginModal] = useState(false);
    
    const navigate = useNavigate();
    const { isAuthenticated } = useAuth();

    const handleBuyNow = useCallback(async (e) => {
        // Stop event propagation to prevent the product popup from closing
        e.stopPropagation();
        
        if (!isAuthenticated) {
            setShowLoginModal(true);
            return;
        }
        
        setIsProcessing(true);
        setMessage(null);
        
        try {
            // Step 1: Add product to cart
            const response = await fetch('http://127.0.0.1:8000/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                },
                body: JSON.stringify({
                    product_id: product.id,
                    product_name: product.name,
                    price: product.price,
                    quantity: 1
                })
            });

            if (!response.ok) {
                throw new Error('Failed to add to cart');
            }

            setMessage({ type: 'success', text: 'Product added! Proceeding to checkout...' });
            
            // Step 2: Redirect to cart for payment processing
            setTimeout(() => {
                // Use navigate with state to trigger automatic payment in MyCart
                navigate('/my-cart', { state: { proceedToPayment: true } });
            }, 1500);
            
        } catch (error) {
            console.error('Error:', error);
            setMessage({ type: 'error', text: 'Failed to process. Please try again.' });
            setIsProcessing(false);
        }
    }, [isAuthenticated, product, navigate]);

    return (
        <div className="add-to-cart-container">
            <button 
                className="buy-now-button"
                onClick={handleBuyNow}
                disabled={isProcessing}
                style={{ 
                    backgroundColor: '#f57c00', 
                    marginLeft: '8px'
                }}
            >
                {isProcessing ? 'Processing...' : 'Buy Now'}
            </button>
            
            {message && (
                <div className={`cart-message ${message.type}`}>
                    {message.text}
                </div>
            )}
            
            <LoginModal 
                isOpen={showLoginModal}
                onClose={() => setShowLoginModal(false)}
            />
        </div>
    );
};

BuyNowButton.propTypes = {
    product: PropTypes.shape({
        id: PropTypes.number,
        name: PropTypes.string.isRequired,
        price: PropTypes.number.isRequired
    }).isRequired
};

export default BuyNowButton; 