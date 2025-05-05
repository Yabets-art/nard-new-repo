import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import './CartStatus.css';

const CartStatus = () => {
    const [itemCount, setItemCount] = useState(0);
    const [isLoading, setIsLoading] = useState(true);
    
    // User ID - In a real app, this would come from authentication
    const userId = 1;

    useEffect(() => {
        const fetchCartCount = async () => {
            setIsLoading(true);
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/cart?user_id=${userId}`);
                if (!response.ok) {
                    throw new Error('Failed to fetch cart data');
                }
                const data = await response.json();
                
                // Calculate total items in cart
                const totalItems = data.reduce((total, item) => total + item.quantity, 0);
                setItemCount(totalItems);
            } catch (error) {
                console.error('Error fetching cart count:', error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchCartCount();
        
        // Set up an interval to regularly update the cart count
        const interval = setInterval(fetchCartCount, 30000); // Every 30 seconds
        
        return () => clearInterval(interval);
    }, [userId]);

    return (
        <Link to="/my-cart" className="cart-status">
            <div className="cart-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z"/>
                    <path d="M7 8V6a5 5 0 1 1 10 0v2h3a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h3zm0 2H5v10h14V10h-2v2h-2v-2H9v2H7v-2zm2-2h6V6a3 3 0 0 0-6 0v2z"/>
                </svg>
                {!isLoading && itemCount > 0 && (
                    <span className="cart-badge">{itemCount}</span>
                )}
            </div>
            <span className="cart-label">Cart</span>
        </Link>
    );
};

export default CartStatus; 