import { useState, useEffect } from 'react';
import './Contact.css';

const Contact = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    message: ''
  });

  const [touched, setTouched] = useState({
    name: false,
    email: false,
    phone: false,
    message: false
  });

  const [errors, setErrors] = useState({
    name: '',
    email: '',
    phone: '',
    message: ''
  });

  const [isSubmitting, setIsSubmitting] = useState(false);
  const [showPopup, setShowPopup] = useState(false);
  const [submitStatus, setSubmitStatus] = useState({ type: '', message: '' });

  // Validation rules
  const validateName = (value) => {
    if (!value.trim()) return 'Name is required';
    if (value.trim().length < 2) return 'Name must be at least 2 characters';
    if (!/^[a-zA-Z\s]*$/.test(value)) return 'Name should only contain letters';
    return '';
  };

  const validateEmail = (value) => {
    if (!value.trim()) return 'Email is required';
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return 'Invalid email format';
    return '';
  };

  const validatePhone = (value) => {
    if (value && !/^[0-9\s-+()]*$/.test(value)) {
      return 'Invalid phone number format';
    }
    return '';
  };

  const validateMessage = (value) => {
    if (!value.trim()) return 'Message is required';
    if (value.trim().length < 10) return 'Message must be at least 10 characters';
    return '';
  };

  // Live validation effect
  useEffect(() => {
    if (touched.name) setErrors(prev => ({ ...prev, name: validateName(formData.name) }));
    if (touched.email) setErrors(prev => ({ ...prev, email: validateEmail(formData.email) }));
    if (touched.phone) setErrors(prev => ({ ...prev, phone: validatePhone(formData.phone) }));
    if (touched.message) setErrors(prev => ({ ...prev, message: validateMessage(formData.message) }));
  }, [formData, touched]);

  const handleChange = (e) => {
    const { id, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [id]: value
    }));
  };

  const handleBlur = (field) => {
    setTouched(prev => ({
      ...prev,
      [field]: true
    }));
  };

  const isFormValid = () => {
    const validationErrors = {
      name: validateName(formData.name),
      email: validateEmail(formData.email),
      phone: validatePhone(formData.phone),
      message: validateMessage(formData.message)
    };
    setErrors(validationErrors);
    return !Object.values(validationErrors).some(error => error);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Mark all fields as touched
    setTouched({
      name: true,
      email: true,
      phone: true,
      message: true
    });

    if (!isFormValid()) {
      setSubmitStatus({
        type: 'error',
        message: 'Please fix the errors before submitting.'
      });
      return;
    }

    setIsSubmitting(true);

    try {
      const response = await fetch('http://127.0.0.1:8000/api/messages', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        setShowPopup(true);
        setSubmitStatus({
          type: 'success',
          message: 'Message sent successfully!'
        });
        setFormData({ name: '', email: '', phone: '', message: '' });
        setTouched({
          name: false,
          email: false,
          phone: false,
          message: false
        });
      } else {
        throw new Error('Failed to send message');
      }
    } catch (error) {
      console.error('Error:', error);
      setSubmitStatus({
        type: 'error',
        message: 'Failed to send message. Please try again.'
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  const getInputClassName = (fieldName) => {
    return `form-input ${touched[fieldName] ? (errors[fieldName] ? 'invalid' : 'valid') : ''}`;
  };

  return (
    <div className="contact-container">
      <div className="contact-content">
        <div className="contact-info">
          <h2>Get in Touch</h2>
          <p className="contact-description">
            Have questions about our products or services? We'd love to hear from you.
            Fill out the form and we'll be in touch as soon as possible.
          </p>
          
          <div className="contact-details">
            <div className="contact-item">
              <i className="fas fa-map-marker-alt"></i>
              <div>
                <h3>Visit Us</h3>
                <p>Bole -medhanialem||Alemnesh Plaza||Addis Ababa</p>
              </div>
            </div>
            
            <div className="contact-item">
              <i className="fas fa-phone"></i>
              <div>
                <h3>Call Us</h3>
                <p><a href="tel:+251933340777">(251) 933340777</a></p>
              </div>
            </div>
            
            <div className="contact-item">
              <i className="fas fa-envelope"></i>
              <div>
                <h3>Email Us</h3>
                <p><a href="mailto:nardosabera248@gmail.com">nardosabera248@gmail.com</a></p>
              </div>
            </div>

            <div className="social-links">
              <a href="https://facebook.com" target="_blank" rel="noopener noreferrer">
                <i className="fab fa-facebook-f"></i>
              </a>
              <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
                <i className="fab fa-instagram"></i>
              </a>
              <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer">
                <i className="fab fa-tiktok"></i>
              </a>
            </div>
          </div>
        </div>

        <div className="contact-form">
          <form onSubmit={handleSubmit} noValidate>
            {submitStatus.message && (
              <div className={`submit-status ${submitStatus.type}`}>
                {submitStatus.message}
              </div>
            )}

        <div className="form-group">
              <label htmlFor="name">
                Full Name
                <span className="required">*</span>
              </label>
          <input
            type="text"
            id="name"
                className={getInputClassName('name')}
            value={formData.name}
            onChange={handleChange}
                onBlur={() => handleBlur('name')}
                placeholder="Enter your name"
            required
          />
              {touched.name && errors.name && (
                <div className="error-message">{errors.name}</div>
              )}
        </div>

        <div className="form-group">
              <label htmlFor="email">
                Email Address
                <span className="required">*</span>
              </label>
          <input
            type="email"
            id="email"
                className={getInputClassName('email')}
            value={formData.email}
            onChange={handleChange}
                onBlur={() => handleBlur('email')}
                placeholder="Enter your email"
            required
          />
              {touched.email && errors.email && (
                <div className="error-message">{errors.email}</div>
              )}
        </div>

        <div className="form-group">
              <label htmlFor="phone">Phone Number</label>
          <input
            type="tel"
            id="phone"
                className={getInputClassName('phone')}
            value={formData.phone}
            onChange={handleChange}
                onBlur={() => handleBlur('phone')}
                placeholder="Enter your phone number"
          />
              {touched.phone && errors.phone && (
                <div className="error-message">{errors.phone}</div>
              )}
        </div>

        <div className="form-group">
              <label htmlFor="message">
                Message
                <span className="required">*</span>
              </label>
          <textarea
            id="message"
                className={getInputClassName('message')}
            value={formData.message}
            onChange={handleChange}
                onBlur={() => handleBlur('message')}
                placeholder="How can we help you?"
            required
          />
              {touched.message && errors.message && (
                <div className="error-message">{errors.message}</div>
              )}
            </div>

            <button 
              type="submit" 
              className={`submit-button ${isSubmitting ? 'loading' : ''}`}
              disabled={isSubmitting || Object.values(errors).some(error => error)}
            >
              {isSubmitting ? 'Sending...' : 'Send Message'}
            </button>
          </form>
        </div>
      </div>

      {showPopup && (
        <div className="popup-overlay" onClick={() => setShowPopup(false)}>
          <div className="popup" onClick={e => e.stopPropagation()}>
            <i className="fas fa-check-circle"></i>
            <h3>Thank You!</h3>
            <p>Your message has been sent successfully. We'll get back to you soon.</p>
            <button onClick={() => setShowPopup(false)}>Close</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default Contact;
