import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import './CartPage.css';

const CartPage = () => {
  const [cartItems, setCartItems] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  const userId = 1; // Hardcoded for demonstration

  useEffect(() => {
    fetchCartItems();
  }, []);

  const fetchCartItems = async () => {
    setIsLoading(true);
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/cart?user_id=${userId}`);
      
      if (!response.ok) {
        throw new Error('Failed to fetch cart items');
      }
      
      const data = await response.json();
      setCartItems(data.data || []);
      setError(null);
    } catch (err) {
      setError('Error loading cart items. Please try again later.');
      console.error('Error fetching cart:', err);
    } finally {
      setIsLoading(false);
    }
  };

  const handleQuantityChange = async (itemId, newQuantity) => {
    if (newQuantity < 1) return;
    
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/cart/update`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          user_id: userId,
          cart_id: itemId,
          quantity: newQuantity
        }),
      });
      
      if (!response.ok) {
        throw new Error('Failed to update quantity');
      }
      
      // Update local state
      setCartItems(cartItems.map(item => 
        item.id === itemId ? { ...item, quantity: newQuantity } : item
      ));
    } catch (err) {
      setError('Error updating quantity. Please try again.');
      console.error('Error updating quantity:', err);
    }
  };

  const handleRemoveItem = async (itemId) => {
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/cart/remove`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          user_id: userId,
          cart_id: itemId
        }),
      });
      
      if (!response.ok) {
        throw new Error('Failed to remove item');
      }
      
      // Update local state
      setCartItems(cartItems.filter(item => item.id !== itemId));
    } catch (err) {
      setError('Error removing item. Please try again.');
      console.error('Error removing item:', err);
    }
  };

  const calculateTotal = () => {
    return cartItems.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2);
  };

  const handleCheckout = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/checkout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          user_id: userId,
          amount: calculateTotal(),
          items: cartItems
        }),
      });
      
      if (!response.ok) {
        throw new Error('Checkout failed');
      }
      
      const data = await response.json();
      // Redirect to payment page
      window.location.href = data.payment_url;
    } catch (err) {
      setError('Checkout failed. Please try again later.');
      console.error('Error during checkout:', err);
    }
  };

  if (isLoading) {
    return (
      <div className="cart-container">
        <h1>Your Cart</h1>
        <div className="loading-spinner">Loading cart...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="cart-container">
        <h1>Your Cart</h1>
        <div className="error-message">{error}</div>
        <button onClick={fetchCartItems} className="retry-button">Try Again</button>
      </div>
    );
  }

  if (cartItems.length === 0) {
    return (
      <div className="cart-container">
        <h1>Your Cart</h1>
        <div className="empty-cart">
          <div className="empty-cart-icon">🛒</div>
          <p>Your cart is empty</p>
          <Link to="/" className="continue-shopping">Continue Shopping</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="cart-container">
      <h1>Your Cart</h1>
      
      <div className="cart-items">
        {cartItems.map((item) => (
          <div key={item.id} className="cart-item">
            <div className="item-image">
              {item.image_url ? (
                <img src={item.image_url} alt={item.product_name} />
              ) : (
                <div className="placeholder-image">No Image</div>
              )}
            </div>
            
            <div className="item-details">
              <h3>{item.product_name}</h3>
              <p className="item-price">${item.price.toFixed(2)}</p>
            </div>
            
            <div className="quantity-controls">
              <button 
                onClick={() => handleQuantityChange(item.id, item.quantity - 1)}
                disabled={item.quantity <= 1}
              >
                -
              </button>
              <span>{item.quantity}</span>
              <button onClick={() => handleQuantityChange(item.id, item.quantity + 1)}>
                +
              </button>
            </div>
            
            <div className="item-subtotal">
              ${(item.price * item.quantity).toFixed(2)}
            </div>
            
            <button 
              className="remove-item" 
              onClick={() => handleRemoveItem(item.id)}
              aria-label="Remove item"
            >
              ×
            </button>
          </div>
        ))}
      </div>
      
      <div className="cart-summary">
        <div className="summary-row">
          <span>Subtotal:</span>
          <span>${calculateTotal()}</span>
        </div>
        <div className="summary-row">
          <span>Shipping:</span>
          <span>Free</span>
        </div>
        <div className="summary-row total">
          <span>Total:</span>
          <span>${calculateTotal()}</span>
        </div>
        
        <button className="checkout-button" onClick={handleCheckout}>
          Proceed to Checkout
        </button>
        
        <Link to="/" className="continue-shopping">
          Continue Shopping
        </Link>
      </div>
    </div>
  );
};

export default CartPage; 