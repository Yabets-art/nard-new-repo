// src/pages/MyCart.jsx
import { useState, useEffect } from 'react';
import './MyCart.css';

const MyCart = () => {
    const [cartProducts, setCartProducts] = useState([]);

    useEffect(() => {
        const fetchCartData = async () => {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/user-cart');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                setCartProducts(data);
            } catch (error) {
                console.error('Error fetching cart data:', error);
            }
        };

        fetchCartData();
    }, []);

    return (
        <div className="cart-container">
            <h1>Your Cart</h1>
            {cartProducts.length > 0 ? (
                <ul>
                    {cartProducts.map((product) => (
                        <li key={product.id}>
                            <h2>{product.name}</h2>
                            <p>Price: ${parseFloat(product.price).toFixed(2)}</p>
                        </li>
                    ))}
                </ul>
            ) : (
                <p>Your cart is empty</p>
            )}
        </div>
    );
};

export default MyCart;
