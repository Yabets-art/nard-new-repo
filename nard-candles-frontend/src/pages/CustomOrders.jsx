import axios from 'axios'; 
import { useState, useEffect } from 'react';
import './CustomOrders.css'; // Import the CSS file

const CustomOrders = () => {
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [email, setEmail] = useState('');
  const [preferences, setPreferences] = useState('');
  const [image, setImage] = useState(null);
  const [showModal, setShowModal] = useState(false); // State for modal visibility
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [showSuccess, setShowSuccess] = useState(false);

  // Validation states
  const [errors, setErrors] = useState({
    name: '',
    phone: '',
    email: '',
    preferences: '',
    image: ''
  });

  const [touched, setTouched] = useState({
    name: false,
    phone: false,
    email: false,
    preferences: false
  });

  // Validation rules
  const validateName = (value) => {
    if (!value.trim()) return 'Name is required';
    if (value.trim().length < 2) return 'Name must be at least 2 characters';
    if (!/^[a-zA-Z\s]*$/.test(value)) return 'Name should only contain letters';
    return '';
  };

  const validatePhone = (value) => {
    if (value && !/^[0-9\s-+()]*$/.test(value)) {
      return 'Invalid phone number format';
    }
    return '';
  };

  const validateEmail = (value) => {
    if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
      return 'Invalid email format';
    }
    return '';
  };

  const validatePreferences = (value) => {
    if (!value.trim()) return 'Please describe your custom candle preferences';
    if (value.trim().length < 10) return 'Please provide more details (at least 10 characters)';
    return '';
  };

  const validateImage = (file) => {
    if (!file) return '';
    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
      return 'Please upload a valid image file (JPEG, PNG, or GIF)';
    }
    if (file.size > 2 * 1024 * 1024) {
      return 'Image size should be less than 2MB';
    }
    return '';
  };

  // Live validation effect
  useEffect(() => {
    if (touched.name) setErrors(prev => ({ ...prev, name: validateName(name) }));
    if (touched.phone) setErrors(prev => ({ ...prev, phone: validatePhone(phone) }));
    if (touched.email) setErrors(prev => ({ ...prev, email: validateEmail(email) }));
    if (touched.preferences) setErrors(prev => ({ ...prev, preferences: validatePreferences(preferences) }));
  }, [name, phone, email, preferences, touched]);

  const handleBlur = (field) => {
    setTouched(prev => ({ ...prev, [field]: true }));
  };

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      const imageError = validateImage(file);
      setErrors(prev => ({ ...prev, image: imageError }));
      if (!imageError) {
        setImage(file);
      } else {
        e.target.value = null; // Clear the file input
      }
    }
  };

  const isFormValid = () => {
    const validationErrors = {
      name: validateName(name),
      phone: validatePhone(phone),
      email: validateEmail(email),
      preferences: validatePreferences(preferences),
      image: validateImage(image)
    };

    setErrors(validationErrors);
    return !Object.values(validationErrors).some(error => error);
  };

  const handleFormSubmit = async (e) => {
    e.preventDefault();
    
    // Mark all fields as touched
    setTouched({
      name: true,
      phone: true,
      email: true,
      preferences: true
    });

    if (!isFormValid()) {
      return;
    }

    setIsSubmitting(true);
  
    try {
      const formData = new FormData();
      formData.append('name', name);
      formData.append('phone', phone);
      formData.append('email', email);
      formData.append('preferences', preferences);
      if (image) {
        formData.append('image', image);
      }
  
      await axios.post('http://127.0.0.1:8000/api/custom-orders', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        withCredentials: true,
      });
      
      setShowModal(true);
      setShowSuccess(true);
      
      // Clear form and validation states
      setName('');
      setPhone('');
      setEmail('');
      setPreferences('');
      setImage(null);
      setTouched({
        name: false,
        phone: false,
        email: false,
        preferences: false
      });
      setErrors({
        name: '',
        phone: '',
        email: '',
        preferences: '',
        image: ''
      });

      setTimeout(() => {
        setShowSuccess(false);
      }, 5000);
    } catch (error) {
      console.error('Submission error:', error);
      alert('There was an error submitting the order. Please try again.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const closeModal = () => {
    setShowModal(false); // Close the modal
  };

  const getInputClassName = (fieldName) => {
    return `form-input ${touched[fieldName] ? (errors[fieldName] ? 'invalid' : 'valid') : ''}`;
  };

  return (
    <div className="custom-orders">
      <h2>Create Your Custom Candle</h2>
      <form onSubmit={handleFormSubmit} noValidate>
        <div className="form-section">
          <div className="form-group">
            <label htmlFor="name">
              Full Name
              <span className="required">*</span>
            </label>
            <input 
              type="text" 
              id="name"
              className={getInputClassName('name')}
              value={name} 
              onChange={(e) => setName(e.target.value)}
              onBlur={() => handleBlur('name')}
              placeholder="Enter your full name"
              required 
            />
            {touched.name && errors.name && (
              <div className="error-message">{errors.name}</div>
            )}
          </div>
        </div>

        <div className="form-section">
          <div className="form-group">
            <label htmlFor="phone">Phone Number</label>
            <input 
              type="tel" 
              id="phone"
              className={getInputClassName('phone')}
              value={phone} 
              onChange={(e) => setPhone(e.target.value)}
              onBlur={() => handleBlur('phone')}
              placeholder="Enter your phone number"
            />
            {touched.phone && errors.phone && (
              <div className="error-message">{errors.phone}</div>
            )}
          </div>
        </div>

        <div className="form-section">
          <div className="form-group">
            <label htmlFor="email">Email Address</label>
            <input 
              type="email" 
              id="email"
              className={getInputClassName('email')}
              value={email} 
              onChange={(e) => setEmail(e.target.value)}
              onBlur={() => handleBlur('email')}
              placeholder="Enter your email address"
            />
            {touched.email && errors.email && (
              <div className="error-message">{errors.email}</div>
            )}
          </div>
        </div>

        <div className="form-section">
          <div className="form-group">
            <label htmlFor="preferences">
              Your Custom Candle Preferences
              <span className="required">*</span>
            </label>
            <textarea 
              id="preferences"
              className={getInputClassName('preferences')}
              value={preferences} 
              onChange={(e) => setPreferences(e.target.value)}
              onBlur={() => handleBlur('preferences')}
              placeholder="Describe your perfect candle (colors, scents, size, quantity, etc.)"
              required
            />
            {touched.preferences && errors.preferences && (
              <div className="error-message">{errors.preferences}</div>
            )}
          </div>
        </div>

        <div className="form-section">
          <div className="form-group">
            <label htmlFor="image">Reference Image (Optional)</label>
            <input 
              type="file" 
              id="image"
              onChange={handleImageChange}
              accept="image/*"
              className={errors.image ? 'invalid' : ''}
            />
            {errors.image && (
              <div className="error-message">{errors.image}</div>
            )}
          </div>
        </div>

        <button 
          type="submit" 
          className={`submit-button ${isSubmitting ? 'loading' : ''}`}
          disabled={isSubmitting || Object.values(errors).some(error => error)}
        >
          {isSubmitting ? 'Submitting...' : 'Submit Custom Order'}
        </button>
      </form>

      {showModal && (
        <div className="modal-overlay" onClick={closeModal}>
          <div className="modal-content" onClick={e => e.stopPropagation()}>
            <h3>Thank You!</h3>
            <p>
              We're excited to create your custom candle! We'll review your preferences
              and contact you soon to discuss the details of your order.
            </p>
            <button onClick={closeModal}>Close</button>
          </div>
        </div>
      )}

      {showSuccess && (
        <div className="success-message">
          Order submitted successfully!
        </div>
      )}
    </div>
  );
};

export default CustomOrders;
