import { useState, useEffect } from 'react';
import axios from 'axios';
import './Products.css';

const Products = () => {
    const [searchTerm, setSearchTerm] = useState('');
    const [sortOption, setSortOption] = useState('default');
    const [products, setProducts] = useState([]);
    const [filteredProducts, setFilteredProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedProduct, setSelectedProduct] = useState(null);

    useEffect(() => {
        const fetchProducts = async () => {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/products');
                setProducts(response.data);
                setFilteredProducts(response.data);
                setLoading(false);
            } catch (err) {
                setError('Failed to fetch products');
                setLoading(false);
                console.error(err);
            }
        };

        fetchProducts();
    }, []);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (event.target.className.includes('product-popup')) {
                handleClosePopup();
            }
        };

        if (selectedProduct) {
            document.addEventListener('click', handleClickOutside);
        } else {
            document.removeEventListener('click', handleClickOutside);
        }

        return () => {
            document.removeEventListener('click', handleClickOutside);
        };
    }, [selectedProduct]);

    const handleSearchChange = (event) => {
        const searchValue = event.target.value;
        setSearchTerm(searchValue);
        filterAndSortProducts(searchValue, sortOption);
    };

    const handleSortChange = (event) => {
        const sortValue = event.target.value;
        setSortOption(sortValue);
        filterAndSortProducts(searchTerm, sortValue);
    };

    const filterAndSortProducts = (searchTerm, sortOption) => {
        let updatedProducts = products.filter((product) =>
            product.name.toLowerCase().includes(searchTerm.toLowerCase())
        );

        if (sortOption === 'priceLowToHigh') {
            updatedProducts.sort((a, b) => a.price - b.price);
        } else if (sortOption === 'priceHighToLow') {
            updatedProducts.sort((a, b) => b.price - a.price);
        }

        setFilteredProducts(updatedProducts);
    };

    const handleProductSelect = (product) => {
        setSelectedProduct(product);
    };

    const handleClosePopup = () => {
        setSelectedProduct(null);
    };

    const handleAddToCart = async () => {
        if (!selectedProduct) return;

        try {
            const response = await fetch("http://localhost:8000/api/user-cart", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    productId: selectedProduct.id,
                    quantity: 1,
                }),
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            const data = await response.json();
            console.log("Product added to cart:", data);
        } catch (error) {
            console.error("Error:", error);
        }
    };

    const handleBuyNow = () => {
        if (!selectedProduct) return;
    
        if (!window.Chapa) {
            console.error("Chapa SDK is not loaded.");
            return;
        }
    
        window.Chapa.open({
            public_key: "CHAPUBK_TEST-YJWurdzQrP6a9VXtowU7yYDybk6tZQ06",
            tx_ref: `tx_${Date.now()}`,
            amount: parseFloat(selectedProduct.price),
            currency: "ETB", // Change to ETB for Ethiopian currency
            email: "customer@example.com", // Get user's email dynamically if possible
            first_name: "John", // Optional
            last_name: "Ds",  // Optional
            phone_number: "+251912345678", // Optional
            callback_url: "http://your-callback-url.com",
            return_url: "http://your-website.com/payment-success",
            customization: {
                title: selectedProduct.name,
                description: selectedProduct.description,
                logo: "https://your-logo-url.com/logo.png",
            },
        });
    };
    

    return (
        <div className="products-container">
            <h1>Our Products</h1>
            <div className="search-sort-container">
                <div className="search-input-wrapper">
                    <i className="fas fa-search search-input-icon"></i>
                    <input
                        type="text"
                        placeholder="Search products..."
                        value={searchTerm}
                        onChange={handleSearchChange}
                        className="search-input"
                    />
                </div>
                <div className="sort-select-wrapper">
                    <i className="fas fa-sort sort-select-icon"></i>
                    <select value={sortOption} onChange={handleSortChange} className="sort-select">
                        <option value="default">Sort by</option>
                        <option value="priceLowToHigh">Price: Low to High</option>
                        <option value="priceHighToLow">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div className="products-grid">
                {loading ? (
                    <p>Loading products...</p>
                ) : error ? (
                    <p>{error}</p>
                ) : (
                    filteredProducts.length > 0 ? (
                        filteredProducts.map((product) => (
                            <div key={product.id} className="product-card" onClick={() => handleProductSelect(product)}>
                                <img src={`http://127.0.0.1:8000/storage/${product.image}`} alt={product.name} />
                                <h2>{product.name}</h2>
                                <p className="product-price">${parseFloat(product.price).toFixed(2)}</p>
                            </div>
                        ))
                    ) : (
                        <p>No products found</p>
                    )
                )}
            </div>

            {selectedProduct && (
                <div className="product-popup">
                    <div className="popup-content">
                        <span className="close-popup" onClick={handleClosePopup}>&times;</span>
                        <img src={`http://127.0.0.1:8000/storage/${selectedProduct.image}`} alt={selectedProduct.name} />
                        <h2>{selectedProduct.name}</h2>
                        <p>{selectedProduct.description}</p>
                        <p className="product-price">${parseFloat(selectedProduct.price).toFixed(2)}</p>
                        <div className="product-buttons">
                            <button className="add-to-cart-button" onClick={handleAddToCart}>
                                Add to Cart
                            </button>
                            <button className="buy-button" onClick={handleBuyNow}>Buy</button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Products;
