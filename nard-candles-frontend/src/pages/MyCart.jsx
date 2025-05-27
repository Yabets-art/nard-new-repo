// src/pages/MyCart.jsx
import { useState, useEffect, useCallback } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import PaymentDebug from '../components/PaymentDebug';
import { motion, AnimatePresence } from 'framer-motion';
import './MyCart.css';

const MyCart = () => {
    const [cartItems, setCartItems] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);
    const [total, setTotal] = useState(0);
    const [checkoutStatus, setCheckoutStatus] = useState('');
    const [showProfileWarning, setShowProfileWarning] = useState(false);
    const [debugPayload, setDebugPayload] = useState(null);
    
    const { isAuthenticated, user } = useAuth();
    const navigate = useNavigate();
    const location = useLocation();

    // Animation variants
    const containerVariants = {
        hidden: { opacity: 0, y: 20 },
        visible: { 
            opacity: 1, 
            y: 0,
            transition: { duration: 0.6, ease: "easeOut" }
        },
        exit: { 
            opacity: 0, 
            y: -20,
            transition: { duration: 0.4 }
        }
    };

    const itemVariants = {
        hidden: { opacity: 0, x: -20 },
        visible: { 
            opacity: 1, 
            x: 0,
            transition: { duration: 0.5, ease: "easeOut" }
        },
        exit: { 
            opacity: 0, 
            x: 20,
            transition: { duration: 0.3 }
        }
    };

    // Fetch cart items
    useEffect(() => {
        if (isAuthenticated) {
            fetchCartItems();
        }
    }, [isAuthenticated]);

    // Check if we should proceed to payment automatically (when coming from Buy Now)
    useEffect(() => {
        if (location.state?.proceedToPayment && cartItems.length > 0 && !isLoading) {
            // A longer delay to ensure UI is rendered before proceeding
            const timer = setTimeout(() => {
                initiatePayment();
            }, 1500);
            return () => clearTimeout(timer);
        }
    }, [location.state, cartItems, isLoading]);

    // Calculate total whenever cart items change
    useEffect(() => {
        calculateTotal();
    }, [cartItems]);

    const fetchCartItems = async () => {
        setIsLoading(true);
        try {
            console.log('Fetching cart items');
            const token = localStorage.getItem('token');
            const response = await fetch(`http://127.0.0.1:8000/api/cart`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            if (!response.ok) {
                throw new Error('Failed to fetch cart items');
            }
            const data = await response.json();
            console.log('Cart data received:', data);
            setCartItems(data);
            setError(null);
        } catch (err) {
            setError('Error loading cart: ' + err.message);
            console.error('Error fetching cart:', err);
        } finally {
            setIsLoading(false);
        }
    };

    const calculateTotal = () => {
        const sum = cartItems.reduce((acc, item) => {
            return acc + (parseFloat(item.price) * item.quantity);
        }, 0);
        setTotal(sum);
    };

    const updateQuantity = async (itemId, newQuantity) => {
        if (newQuantity < 1) return;
        
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`http://127.0.0.1:8000/api/cart/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    item_id: itemId,
                    quantity: newQuantity
                })
            });
            
            if (!response.ok) {
                throw new Error('Failed to update quantity');
            }
            
            setCartItems(prevItems =>
                prevItems.map(item =>
                    item.id === itemId ? { ...item, quantity: newQuantity } : item
                )
            );
        } catch (err) {
            setError('Error updating quantity: ' + err.message);
            console.error('Error updating quantity:', err);
        }
    };

    const removeItem = async (itemId) => {
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`http://127.0.0.1:8000/api/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to remove item');
            }
            
            setCartItems(prevItems => prevItems.filter(item => item.id !== itemId));
        } catch (err) {
            setError('Error removing item: ' + err.message);
            console.error('Error removing item:', err);
        }
    };

    const clearCart = async () => {
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`http://127.0.0.1:8000/api/cart/clear`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to clear cart');
            }
            
            setCartItems([]);
        } catch (err) {
            setError('Error clearing cart: ' + err.message);
            console.error('Error clearing cart:', err);
        }
    };

    // Validate user profile before payment
    const validateUserProfile = () => {
        if (!user) return false;
        
        // Check for valid email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!user.email || !emailRegex.test(user.email)) {
            setCheckoutStatus('Your email address is missing or invalid. Please update your profile.');
            setShowProfileWarning(true);
            return false;
        }
        
        return true;
    };
    
    const goToProfile = () => {
        navigate('/profile');
    };
    
    const initiatePayment = async () => {
        if (cartItems.length === 0) {
            setCheckoutStatus('Your cart is empty');
            return;
        }
        
        // First validate user profile
        if (!validateUserProfile()) {
            return;
        }

        setCheckoutStatus('Processing...');
        setShowProfileWarning(false);
        setDebugPayload(null); // Reset any previous debug data
        
        try {
            const token = localStorage.getItem('token');
            logDebug('payment', { action: 'start', token_exists: !!token });
            
            // Create request configuration to log
            const requestConfig = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            };
            
            // Save debug data to display
            const userInfo = {
                first_name: user?.first_name || 'Missing',
                last_name: user?.last_name || 'Missing',
                email: user?.email || 'Missing',
                phone_number: user?.phone_number || 'Missing'
            };
            
            setDebugPayload({
                requestInfo: {
                    url: 'http://127.0.0.1:8000/api/initiate-payment',
                    ...requestConfig
                },
                userInfo
            });
            
            const response = await fetch('http://127.0.0.1:8000/api/initiate-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });
            
            const data = await response.json();
            logDebug('payment', { action: 'response', status: response.status, data });
            
            // Update debug data with server response
            setDebugPayload(prev => ({
                ...prev,
                serverResponse: data
            }));
            
            if (data.success) {
                setCheckoutStatus('Payment initiated! Opening payment gateway...');
                
                // Store the transaction reference for later lookup
                localStorage.setItem('last_payment_tx_ref', data.tx_ref);
                localStorage.setItem('current_tx_ref', data.tx_ref);
                
                // Get the payment URL
                const paymentUrl = data.payment_url;
                
                // Use window.open for direct user activation which is more reliable
                const paymentTab = window.open(paymentUrl, '_blank');
                
                if (!paymentTab || paymentTab.closed || typeof paymentTab.closed === 'undefined') {
                    // If direct window.open failed (blocked), create a direct link for the user to click
                    setCheckoutStatus('Please click the button below to open the payment page:');
                    
                    // Create a clickable link for the user
                    const paymentLinkContainer = document.createElement('div');
                    paymentLinkContainer.style.margin = '20px auto';
                    paymentLinkContainer.style.textAlign = 'center';
                    
                    const paymentButton = document.createElement('a');
                    paymentButton.href = paymentUrl;
                    paymentButton.target = '_blank';
                    paymentButton.rel = 'noopener noreferrer';
                    paymentButton.textContent = 'Open Payment Gateway';
                    paymentButton.style.padding = '10px 20px';
                    paymentButton.style.backgroundColor = '#f57c00';
                    paymentButton.style.color = 'white';
                    paymentButton.style.borderRadius = '4px';
                    paymentButton.style.textDecoration = 'none';
                    paymentButton.style.fontWeight = 'bold';
                    paymentButton.style.display = 'inline-block';
                    
                    paymentLinkContainer.appendChild(paymentButton);
                    
                    // Find the checkout status element and append the button after it
                    setTimeout(() => {
                        const statusElement = document.querySelector('.checkout-status');
                        if (statusElement) {
                            statusElement.appendChild(paymentLinkContainer);
                        }
                    }, 100);
                } else {
                    // Payment tab was successfully opened
                    setCheckoutStatus('Payment page opened in a new tab. You can continue shopping while completing your payment.');
                    
                    // Only redirect to orders page after a longer delay to allow user to see the message
                    setTimeout(() => {
                        navigate('/my-orders');
                    }, 5000);
                }
            } else {
                setCheckoutStatus(`Payment failed: ${data.error || 'Unknown error'}`);
                
                // If we need to redirect to profile, show warning
                if (data.redirect_to_profile) {
                    setShowProfileWarning(true);
                }
            }
        } catch (err) {
            logDebug('payment', { action: 'error', message: err.message });
            setCheckoutStatus(`Error: ${err.message}`);
        }
    };

    if (isLoading) {
        return (
            <motion.div 
                className="cart-container"
                initial="hidden"
                animate="visible"
                exit="exit"
                variants={containerVariants}
            >
                <div className="loading-spinner">Loading your cart...</div>
            </motion.div>
        );
    }

    return (
        <motion.div 
            className="cart-container"
            initial="hidden"
            animate="visible"
            exit="exit"
            variants={containerVariants}
        >
            <h1>My Shopping Cart</h1>
            
            {error && (
                <motion.div 
                    className="error-message"
                    initial={{ opacity: 0, y: -10 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: 10 }}
                >
                    {error}
                </motion.div>
            )}
            
            <AnimatePresence mode="wait">
                {cartItems.length === 0 ? (
                    <motion.div 
                        className="empty-cart"
                        key="empty"
                        variants={containerVariants}
                        initial="hidden"
                        animate="visible"
                        exit="exit"
                    >
                        <p>Your cart is empty</p>
                        <Link to="/products" className="btn btn-primary">Continue Shopping</Link>
                    </motion.div>
                ) : (
                    <motion.div
                        key="cart"
                        variants={containerVariants}
                        initial="hidden"
                        animate="visible"
                        exit="exit"
                    >
                        <div className="cart-items">
                            <AnimatePresence>
                                {cartItems.map((item) => (
                                    <motion.div 
                                        key={item.id} 
                                        className="cart-item"
                                        variants={itemVariants}
                                        initial="hidden"
                                        animate="visible"
                                        exit="exit"
                                        layout
                                    >
                                        <div className="cart-item-details">
                                            <h3>{item.product_name}</h3>
                                            <p className="price">${parseFloat(item.price).toFixed(2)}</p>
                                        </div>
                                        <div className="cart-item-actions">
                                            <div className="quantity-control">
                                                <button 
                                                    onClick={() => updateQuantity(item.id, item.quantity - 1)}
                                                    disabled={item.quantity <= 1}
                                                >
                                                    -
                                                </button>
                                                <span>{item.quantity}</span>
                                                <button onClick={() => updateQuantity(item.id, item.quantity + 1)}>
                                                    +
                                                </button>
                                            </div>
                                            <p className="item-total">
                                                ${(parseFloat(item.price) * item.quantity).toFixed(2)}
                                            </p>
                                            <button 
                                                className="remove-btn"
                                                onClick={() => removeItem(item.id)}
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </motion.div>
                                ))}
                            </AnimatePresence>
                        </div>
                        
                        <motion.div 
                            className="cart-summary"
                            variants={containerVariants}
                        >
                            <div className="cart-total">
                                <span>Total:</span>
                                <span>${total.toFixed(2)}</span>
                            </div>
                            
                            <div className="cart-actions">
                                <button className="clear-btn" onClick={clearCart}>
                                    Clear Cart
                                </button>
                                <button className="checkout-btn" onClick={initiatePayment}>
                                    Proceed to Payment
                                </button>
                            </div>
                            
                            <AnimatePresence>
                                {checkoutStatus && (
                                    <motion.div 
                                        className="checkout-status"
                                        initial={{ opacity: 0, y: -10 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        exit={{ opacity: 0, y: 10 }}
                                    >
                                        {checkoutStatus}
                                        {checkoutStatus.includes('completing the payment') && (
                                            <div className="order-link">
                                                <Link to="/my-orders">View My Orders</Link>
                                            </div>
                                        )}
                                    </motion.div>
                                )}
                                
                                {showProfileWarning && (
                                    <motion.div 
                                        className="profile-warning"
                                        initial={{ opacity: 0, y: -10 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        exit={{ opacity: 0, y: 10 }}
                                    >
                                        <p>Your profile information is incomplete or invalid.</p>
                                        <button onClick={goToProfile} className="update-profile-btn">
                                            Update Profile
                                        </button>
                                    </motion.div>
                                )}
                            </AnimatePresence>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
            
            <motion.div 
                className="cart-footer"
                variants={containerVariants}
            >
                <Link to="/my-orders" className="view-orders-link">View My Order History</Link>
            </motion.div>
            
            {/* Replace this debug payload display with PaymentDebug component */}
            {/* {debugPayload && debugPayload.serverResponse && (
                <PaymentDebug 
                    payload={debugPayload.serverResponse.debug_info?.payload}
                    response={debugPayload.serverResponse}
                />
            )} */}
            
            {/* Debug Section - Remove in production */}
            {/* <div className="debug-section">
                ... (all debug section JSX removed) ...
            </div> */}
        </motion.div>
    );
};

// Add a utility function to log debug information
function logDebug(category, data) {
    const logs = JSON.parse(localStorage.getItem('debugLogs') || '[]');
    logs.push({
        timestamp: new Date().toISOString(),
        category,
        data
    });
    localStorage.setItem('debugLogs', JSON.stringify(logs));
    console.log(`[DEBUG:${category}]`, data);
}

export default MyCart;
