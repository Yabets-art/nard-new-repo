import { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';
import AddToCartButton from '../components/AddToCartButton';
import LoginModal from '../components/auth/LoginModal';
import './Products.css';

const Products = () => {
    const [searchTerm, setSearchTerm] = useState('');
    const [sortOption, setSortOption] = useState('default');
    const [products, setProducts] = useState([]);
    const [filteredProducts, setFilteredProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedProduct, setSelectedProduct] = useState(null);
    const [showLoginModal, setShowLoginModal] = useState(false);
    const [loadedImages, setLoadedImages] = useState(new Set());
    
    const { isAuthenticated } = useAuth();

    const handleImageLoad = useCallback((productId) => {
        setLoadedImages(prev => new Set([...prev, productId]));
    }, []);

    useEffect(() => {
        const fetchProducts = async () => {
            try {
                setLoading(true);
                const response = await axios.get('http://127.0.0.1:8000/api/products');
                
                if (Array.isArray(response.data) && response.data.length > 0) {
                    // Process the products to ensure image paths are correct
                    const processedProducts = response.data.map(product => {
                        let imagePath = product.image;
                        // Fix specific image paths that have double .jpg extension
                        if (imagePath === 'images/mostSold1.jpg' || imagePath === 'images/mostliked3.jpg') {
                            imagePath = imagePath + '.jpg';
                        }
                        return {
                            ...product,
                            image: imagePath.startsWith('images/') ? imagePath : `images/${imagePath}`
                        };
                    });
                    setProducts(processedProducts);
                    setFilteredProducts(processedProducts);
                } else {
                    setError('No products found. The API returned an empty result.');
                }
            } catch (err) {
                console.error('Error fetching products:', err);
                setError(err.response ? `Server error ${err.response.status}` : 'Network error');
            } finally {
                setLoading(false);
            }
        };

        fetchProducts();
    }, []);

    const filterAndSortProducts = useCallback((searchTerm, sortOption) => {
        let updatedProducts = products.filter((product) =>
            product.name.toLowerCase().includes(searchTerm.toLowerCase())
        );

        if (sortOption === 'priceLowToHigh') {
            updatedProducts.sort((a, b) => a.price - b.price);
        } else if (sortOption === 'priceHighToLow') {
            updatedProducts.sort((a, b) => b.price - a.price);
        }

        setFilteredProducts(updatedProducts);
    }, [products]);

    const handleSearchChange = useCallback((event) => {
        const searchValue = event.target.value;
        setSearchTerm(searchValue);
        filterAndSortProducts(searchValue, sortOption);
    }, [filterAndSortProducts, sortOption]);

    const handleSortChange = useCallback((event) => {
        const sortValue = event.target.value;
        setSortOption(sortValue);
        filterAndSortProducts(searchTerm, sortValue);
    }, [filterAndSortProducts, searchTerm]);

    const handleProductSelect = (product) => {
        setSelectedProduct(product);
    };

    const handleBuyNow = (product) => {
        if (!isAuthenticated) {
            setShowLoginModal(true);
            return;
        }

        // Add the product to cart and proceed to checkout
        const cartItem = {
            product_id: product.id,
            product_name: product.name,
            quantity: 1,
            price: product.price,
            total_price: product.price
        };

        // Add to cart
        axios.post('http://127.0.0.1:8000/api/cart/add', cartItem, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(() => {
            // Redirect to checkout
            window.location.href = '/checkout';
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
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
                    <div className="loading-container">
                        <p>Loading products...</p>
                    </div>
                ) : error ? (
                    <div className="error-container">
                        <p className="error-message">{error}</p>
                        <button className="reload-button" onClick={() => window.location.reload()}>
                            Reload Page
                        </button>
                    </div>
                ) : (
                    filteredProducts.map((product) => (
                        <div 
                            key={product.id} 
                            className="product-card"
                            onClick={() => handleProductSelect(product)}
                        >
                            <div className="product-image-container">
                                <img 
                                    src={`http://127.0.0.1:8000/${product.image}`}
                                    alt={product.name}
                                    onError={(e) => {
                                        e.target.onerror = null;
                                        e.target.src = 'https://via.placeholder.com/150?text=Product+Image';
                                    }}
                                />
                            </div>
                            <div className="product-details">
                                <h2>{product.name}</h2>
                                <p className="product-price">${parseFloat(product.price).toFixed(2)}</p>
                            </div>
                        </div>
                    ))
                )}
            </div>

            {selectedProduct && (
                <div className={`product-popup ${selectedProduct ? 'active' : ''}`}>
                    <div className="popup-content">
                        <span className="close-popup" onClick={() => setSelectedProduct(null)}>&times;</span>
                        <img 
                            src={`http://127.0.0.1:8000/${selectedProduct.image}`}
                            alt={selectedProduct.name}
                            onError={(e) => {
                                e.target.onerror = null;
                                e.target.src = 'https://via.placeholder.com/300?text=Product+Image';
                            }}
                        />
                        <h2>{selectedProduct.name}</h2>
                        <p>{selectedProduct.description}</p>
                        <p className="product-price">${parseFloat(selectedProduct.price).toFixed(2)}</p>
                        <div className="product-buttons">
                            <AddToCartButton product={selectedProduct} />
                        </div>
                    </div>
                </div>
            )}
            
            <LoginModal 
                isOpen={showLoginModal}
                onClose={() => setShowLoginModal(false)}
                showMessage={() => {}}
            />
        </div>
    );
};

export default Products;
