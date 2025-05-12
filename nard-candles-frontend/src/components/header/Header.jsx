import { useState, useEffect } from 'react'; 
import { Link, useNavigate } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHome, faBoxOpen, faClipboardList, faBlog, faPhone, faInfoCircle, faUser, faSignInAlt, faShoppingCart } from '@fortawesome/free-solid-svg-icons';
import { useAuth } from '../../context/AuthContext';
import LoginModal from '../auth/LoginModal';
import './Header.css';
import logo from '../../assets/logo.png';
import PropTypes from 'prop-types';

const Header = ({ cartProducts = [] }) => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isProfileOpen, setIsProfileOpen] = useState(false);
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [cartCount, setCartCount] = useState(0);
  
  const { user, isAuthenticated, logout } = useAuth();
  const navigate = useNavigate();

  // Fetch cart count
  useEffect(() => {
    const fetchCartCount = async () => {
      if (!isAuthenticated) return;
      
      try {
        const token = localStorage.getItem('token');
        const response = await fetch(`http://127.0.0.1:8000/api/cart`, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        
        if (response.ok) {
          const cartItems = await response.json();
          // Calculate total items
          const totalItems = cartItems.reduce((total, item) => total + item.quantity, 0);
          setCartCount(totalItems);
        }
      } catch (error) {
        console.error("Error fetching cart count:", error);
      }
    };
    
    fetchCartCount();
    // Set interval to update cart count every 30 seconds
    const interval = setInterval(fetchCartCount, 30000);
    
    return () => clearInterval(interval);
  }, [isAuthenticated, user]);

  // Toggles the Hamburger Menu
  const toggleMenu = (e) => {
    e.stopPropagation();
    setIsMenuOpen(prev => !prev);
  };

  // Toggles the Profile Dropdown
  const toggleProfile = (e) => {
    e.stopPropagation();
    setIsProfileOpen(prev => !prev);
  };

  const handleLogout = () => {
    logout();
    setIsProfileOpen(false);
    navigate('/');
  };

  const handleCartClick = () => {
    setIsProfileOpen(false);
    navigate('/my-cart');
  };

  const handleProfileClick = () => {
    setIsProfileOpen(false);
    navigate('/profile');
  };

  const handleLoginClick = (e) => {
    e.preventDefault();
    setShowLoginModal(true);
  };

  // Close Menus if clicked outside
  const closeMenus = () => {
    setIsMenuOpen(false);
    setIsProfileOpen(false);
  };

  useEffect(() => {
    const closeOnClickOutside = (e) => {
      if (!e.target.closest('.nav') && !e.target.closest('.hamburger') && !e.target.closest('.profile-section')) {
        closeMenus();
      }
    };

    if (isMenuOpen || isProfileOpen) {
      document.addEventListener('click', closeOnClickOutside);
    } else {
      document.removeEventListener('click', closeOnClickOutside);
    }

    return () => document.removeEventListener('click', closeOnClickOutside);
  }, [isMenuOpen, isProfileOpen]);

  return (
    <header className="header">
      <div className="container">
        <div className="main-nav">
          <div className="logo-container">
            <img src={logo} alt="Nard Candles Logo" className="logo" />
            <h1 className="logo-text">Nard Candles</h1>
          </div>

          {/* Hamburger Menu */}
          <div className="hamburger" onClick={toggleMenu}>
            <span></span>
            <span></span>
            <span></span>
          </div>

          {/* Navigation Links */}
          <nav className={`nav ${isMenuOpen ? 'open' : ''}`} onClick={(e) => e.stopPropagation()}>
            <ul className="nav-links">
              <li><Link to="/" onClick={toggleMenu}><FontAwesomeIcon icon={faHome} /> Home</Link></li>
              <li><Link to="/products" onClick={toggleMenu}><FontAwesomeIcon icon={faBoxOpen} /> Products</Link></li>
              <li><Link to="/custom-orders" onClick={toggleMenu}><FontAwesomeIcon icon={faClipboardList} /> Custom Order</Link></li>
              <li><Link to="/blog" onClick={toggleMenu}><FontAwesomeIcon icon={faBlog} /> Posts</Link></li>
              <li><Link to="/contact" onClick={toggleMenu}><FontAwesomeIcon icon={faPhone} /> Contact Us</Link></li>
              <li><Link to="/about" onClick={toggleMenu}><FontAwesomeIcon icon={faInfoCircle} /> About Us</Link></li>
              <li className="nav-item">
                <Link to="/my-cart" className="nav-link cart-nav-link" onClick={toggleMenu}>
                  <FontAwesomeIcon icon={faShoppingCart} /> Cart
                  {cartCount > 0 && <span className="cart-badge">{cartCount}</span>}
                </Link>
              </li>
            </ul>
          </nav>

          {/* Cart Icon (visible on desktop) */}
          {/* <div className="cart-icon-container">
            <Link to="/my-cart" className="cart-icon-link">
              <FontAwesomeIcon icon={faShoppingCart} />
              {cartCount > 0 && <span className="cart-badge">{cartCount}</span>}
            </Link>
          </div> */}

          {/* Profile Section - Show only when authenticated */}
          {isAuthenticated ? (
            <div className="profile-section" onClick={(e) => e.stopPropagation()}>
              <div className="profile-icon" onClick={toggleProfile}>
                {user.avatar ? (
                  <img src={user.avatar} alt="Profile" className="profile-img" />
                ) : (
                  <FontAwesomeIcon icon={faUser} />
                )}
              </div>

              {isProfileOpen && (
                <div className="profile-dropdown">
                  <div className="profile-info">
                    <img src={user.avatar || logo} alt="Profile" className="profile-dropdown-img" />
                    <p>{user.first_name} {user.last_name}</p>
                  </div>
                  <ul className="profile-actions">
                    <li onClick={handleProfileClick}>Profile</li>
                    <li onClick={handleCartClick}>My Cart</li>
                    <li onClick={handleLogout}>Logout</li>
                  </ul>
                </div>
              )}
            </div>
          ) : (
            <div className="login-section">
              <button className="login-btn" onClick={handleLoginClick}>
                <FontAwesomeIcon icon={faSignInAlt} /> Login
              </button>
            </div>
          )}
        </div>

        {/* Login Modal */}
        <LoginModal 
          isOpen={showLoginModal} 
          onClose={() => setShowLoginModal(false)}
        />
      </div>
    </header>
  );
};

Header.propTypes = {
  cartProducts: PropTypes.array,
};

export default Header;
