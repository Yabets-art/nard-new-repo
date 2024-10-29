import { useState, useEffect } from 'react'; 
import { Link, useNavigate } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHome, faBoxOpen, faClipboardList, faBlog, faPhone, faInfoCircle, faUser } from '@fortawesome/free-solid-svg-icons';
import './Header.css';
import logo from '../../assets/logo.png';
import PropTypes from 'prop-types';

const Header = ({ cartProducts = [] }) => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isProfileOpen, setIsProfileOpen] = useState(false);
  const [isEditProfileOpen, setIsEditProfileOpen] = useState(false);
  const [profile, setProfile] = useState({
    profilePicture: null,
    name: 'Jabez Hap',
    lastName: '',
    email: '',
    contact: '',
  });

  const navigate = useNavigate();

  // Toggles the Hamburger Menu
  const toggleMenu = (e) => {
    e.stopPropagation(); // Prevents immediate closing
    setIsMenuOpen(prev => !prev);
  };

  // Toggles the Profile Dropdown
  const toggleProfile = (e) => {
    e.stopPropagation(); // Prevents immediate closing
    setIsProfileOpen(prev => !prev);
    if (isEditProfileOpen) {
      setIsEditProfileOpen(false); // Close edit profile if the profile menu is opened
    }
  };

  // Handle Profile Picture Change
  const handleProfilePictureChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setProfile(prevProfile => ({ ...prevProfile, profilePicture: URL.createObjectURL(file) }));
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setProfile(prevProfile => ({ ...prevProfile, [name]: value }));
  };

  const handleSaveProfile = () => {
    // You can add form validation or API calls here
    setIsEditProfileOpen(false);
    // Optionally, display a success message or update user data
  };

  const handleLogout = () => {
    console.log('User logged out');
    // Implement logout functionality here
  };

  const handleCartClick = () => {
    setIsProfileOpen(false); // Close the profile dropdown
    navigate('/MyCart');
  };

  const handleProfileClick = () => {
    setIsProfileOpen(false); // Close profile dropdown
    setIsEditProfileOpen(true); // Open edit profile
  };

  // Close Menus if clicked outside
  const closeMenus = () => {
    setIsMenuOpen(false);
    setIsProfileOpen(false);
    setIsEditProfileOpen(false); // Close all modals
  };

  useEffect(() => {
    const closeOnClickOutside = (e) => {
      if (!e.target.closest('.nav') && !e.target.closest('.hamburger') && !e.target.closest('.profile-section') && !e.target.closest('.profile-popup')) {
        closeMenus();
      }
    };

    if (isMenuOpen || isProfileOpen || isEditProfileOpen) {
      document.addEventListener('click', closeOnClickOutside);
    } else {
      document.removeEventListener('click', closeOnClickOutside);
    }

    return () => document.removeEventListener('click', closeOnClickOutside);
  }, [isMenuOpen, isProfileOpen, isEditProfileOpen]);

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
            </ul>
          </nav>

          {/* Profile Section */}
          <div className="profile-section" onClick={(e) => e.stopPropagation()}>
            <div className="profile-icon" onClick={toggleProfile}>
              {profile.profilePicture ? (
                <img src={profile.profilePicture} alt="Profile" className="profile-img" />
              ) : (
                <FontAwesomeIcon icon={faUser} />
              )}
            </div>

            {isProfileOpen && (
              <div className="profile-dropdown">
                <div className="profile-info">
                  <img src={profile.profilePicture || logo} alt="Profile" className="profile-dropdown-img" />
                  <p>{profile.name}</p>
                </div>
                <ul className="profile-actions">
                  <li onClick={handleProfileClick}>Profile</li>
                  <li onClick={handleCartClick}>My Cart ({cartProducts.length})</li>
                  <li onClick={handleLogout}>Logout</li>
                </ul>
              </div>
            )}
          </div>
        </div>

        {/* Profile Edit Popup */}
        {isEditProfileOpen && (
          <div className="overlay active" onClick={closeMenus}></div>
        )}

        {isEditProfileOpen && (
          <div className="profile-popup" onClick={(e) => e.stopPropagation()}>
            <div className="profile-form">
              <h2>Edit Profile</h2>
              <div className="profile-picture">
                {profile.profilePicture ? (
                  <img src={profile.profilePicture} alt="Profile" />
                ) : (
                  <FontAwesomeIcon icon={faUser} />
                )}
                <label htmlFor="profilePictureUpload">Upload Image</label>
                <input type="file" id="profilePictureUpload" onChange={handleProfilePictureChange} />
              </div>
              <div className="profile-inputs">
                <label htmlFor="name">Name</label>
                <input type="text" id="name" name="name" value={profile.name} onChange={handleInputChange} />
                
                <label htmlFor="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" value={profile.lastName} onChange={handleInputChange} />
                
                <label htmlFor="email">Email</label>
                <input type="email" id="email" name="email" value={profile.email} onChange={handleInputChange} />
                
                <label htmlFor="contact">Contact</label>
                <input type="text" id="contact" name="contact" value={profile.contact} onChange={handleInputChange} />
              </div>
              <div className="button-group">
                <button className="save-button" onClick={handleSaveProfile}>Save</button>
                <button className="cancel-button" onClick={() => setIsEditProfileOpen(false)}>Cancel</button>
              </div>
            </div>
          </div>
        )}
      </div>
    </header>
  );
};

Header.propTypes = {
  cartProducts: PropTypes.array.isRequired,
};

export default Header;
