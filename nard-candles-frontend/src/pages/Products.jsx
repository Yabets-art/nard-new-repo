import { useState, useEffect, useCallback, useMemo, useRef } from 'react';
import axios from 'axios';
import { useAuth } from '../context/AuthContext';
import AddToCartButton from '../components/AddToCartButton';
import BuyNowButton from '../components/BuyNowButton';
import LoginModal from '../components/auth/LoginModal';
import './Products.css';

const Products = () => {
    const [searchTerm, setSearchTerm] = useState('');
    const [sortOption, setSortOption] = useState('default');
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedProduct, setSelectedProduct] = useState(null);
    const [showLoginModal, setShowLoginModal] = useState(false);
    const [currentPage, setCurrentPage] = useState(1);
    const [productsPerPage] = useState(12);
    const popupRef = useRef(null);

    const { isAuthenticated } = useAuth();

    // Close popup when clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (selectedProduct && popupRef.current && !popupRef.current.contains(event.target)) {
                setSelectedProduct(null);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [selectedProduct]);

    useEffect(() => {
        const fetchProducts = async () => {
            try {
                setLoading(true);
                const response = await axios.get('http://127.0.0.1:8000/api/products');
                
                if (!response.data || !Array.isArray(response.data)) {
                    throw new Error('Invalid response format from server');
                }

                if (response.data.length === 0) {
                    setError('No products found. The API returned an empty result.');
                    return;
                }

                const processedProducts = response.data.map(product => {
                    let imagePath = product.image;
                    if (imagePath === 'images/mostSold1.jpg' || imagePath === 'images/mostliked3.jpg') {
                        imagePath = imagePath + '.jpg';
                    }
                    return {
                        ...product,
                        image: imagePath.startsWith('images/') ? imagePath : `${imagePath}`
                    };
                });
                setProducts(processedProducts);
            } catch (err) {
                console.error('Error fetching products:', err);
                setError(err.response 
                    ? `Server error: ${err.response.status} - ${err.response.data.message || 'No additional info'}`
                    : err.message || 'Network error');
            } finally {
                setLoading(false);
            }
        };

        fetchProducts();
    }, []);

    const filteredProducts = useMemo(() => {
        let updatedProducts = [...products];
        
        if (searchTerm) {
            updatedProducts = updatedProducts.filter((product) =>
                product.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }

        if (sortOption === 'priceLowToHigh') {
            updatedProducts.sort((a, b) => a.price - b.price);
        } else if (sortOption === 'priceHighToLow') {
            updatedProducts.sort((a, b) => b.price - a.price);
        }

        return updatedProducts;
    }, [products, searchTerm, sortOption]);

    const indexOfLastProduct = currentPage * productsPerPage;
    const indexOfFirstProduct = indexOfLastProduct - productsPerPage;
    const currentProducts = filteredProducts.slice(indexOfFirstProduct, indexOfLastProduct);

    const paginate = (pageNumber) => setCurrentPage(pageNumber);

    const handleSearchChange = (event) => {
        setSearchTerm(event.target.value);
        setCurrentPage(1);
    };

    const handleSortChange = (event) => {
        setSortOption(event.target.value);
    };

    const handleProductSelect = (product) => {
        setSelectedProduct(product);
    };

    const handleBuyNow = (product) => {
        if (!isAuthenticated) {
            setShowLoginModal(true);
            return;
        }

        const cartItem = {
            product_id: product.id,
            product_name: product.name,
            quantity: 1,
            price: product.price,
            total_price: product.price
        };

        axios.post('http://127.0.0.1:8000/api/cart/add', cartItem, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(() => {
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
                        aria-label="Search products"
                    />
                </div>
                <div className="sort-select-wrapper">
                    <i className="fas fa-sort sort-select-icon"></i>
                    <select 
                        value={sortOption} 
                        onChange={handleSortChange} 
                        className="sort-select"
                        aria-label="Sort products"
                    >
                        <option value="default">Sort by</option>
                        <option value="priceLowToHigh">Price: Low to High</option>
                        <option value="priceHighToLow">Price: High to Low</option>
                    </select>
                </div>
            </div>

            {loading ? (
                <div className="products-grid">
                    {[...Array(6)].map((_, index) => (
                        <div key={index} className="product-card loading">
                            <div className="skeleton skeleton-image"></div>
                            <div className="product-details">
                                <div className="skeleton skeleton-text" style={{width: '80%'}}></div>
                                <div className="skeleton skeleton-text" style={{width: '40%'}}></div>
                            </div>
                        </div>
                    ))}
                </div>
            ) : error ? (
                <div className="error-container">
                    <p className="error-message">{error}</p>
                    <button className="reload-button" onClick={() => window.location.reload()}>
                        Reload Page
                    </button>
                </div>
            ) : (
                <>
                    <div className="products-grid">
                        {currentProducts.map((product) => (
                            <div 
                                key={product.id} 
                                className="product-card"
                                onClick={() => handleProductSelect(product)}
                                tabIndex="0"
                                role="button"
                                aria-label={`View details for ${product.name}`}
                            >
                                <div className="product-image-container">
                                    <img 
                                        src={`http://127.0.0.1:8000/${product.image}`}
                                        alt={product.name}
                                        loading="lazy"
                                    />
                                </div>
                                <div className="product-details">
                                    <h2>{product.name}</h2>
                                    <p className="product-price">${parseFloat(product.price).toFixed(2)}</p>
                                </div>
                            </div>
                        ))}
                    </div>

                    {filteredProducts.length > productsPerPage && (
                        <div className="pagination">
                            {Array.from({ length: Math.ceil(filteredProducts.length / productsPerPage) }, (_, i) => (
                                <button
                                    key={i + 1}
                                    onClick={() => paginate(i + 1)}
                                    className={`page-number ${currentPage === i + 1 ? 'active' : ''}`}
                                >
                                    {i + 1}
                                </button>
                            ))}
                        </div>
                    )}
                </>
            )}

            {selectedProduct && (
                <div className={`product-popup ${selectedProduct ? 'active' : ''}`}>
                    <div className="popup-content" ref={popupRef}>
                        <span 
                            className="close-popup" 
                            onClick={() => setSelectedProduct(null)}
                            aria-label="Close product details"
                            role="button"
                            tabIndex="0"
                        >
                            &times;
                        </span>
                        <div className="popup-image-container">
                            <img 
                                src={`http://127.0.0.1:8000/${selectedProduct.image}`}
                                alt={selectedProduct.name}
                                className="popup-product-image"
                            />
                        </div>
                        <div className="popup-text-content">
                            <h2>{selectedProduct.name}</h2>
                            <p className="product-description">{selectedProduct.description}</p>
                            <p className="product-price">${parseFloat(selectedProduct.price).toFixed(2)}</p>
                            <div className="product-buttons">
                                <AddToCartButton product={selectedProduct} />
                                <BuyNowButton product={selectedProduct} />
                            </div>
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