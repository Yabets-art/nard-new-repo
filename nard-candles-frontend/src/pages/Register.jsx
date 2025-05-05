import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Register.css';

const Register = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: ''
    });
    const [error, setError] = useState(null);
    const [validationErrors, setValidationErrors] = useState({});
    const [isLoading, setIsLoading] = useState(false);

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        });
        
        // Clear validation error for this field when it's changed
        if (validationErrors[e.target.name]) {
            setValidationErrors({
                ...validationErrors,
                [e.target.name]: null
            });
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        setError(null);
        setValidationErrors({});

        try {
            const response = await fetch('http://127.0.0.1:8000/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            const data = await response.json();
            
            // Check if the response contains validation errors
            if (!response.ok) {
                if (response.status === 422 && data.messages) {
                    // Handle validation errors
                    setValidationErrors(data.messages);
                    throw new Error('Please correct the validation errors');
                } else {
                    throw new Error(data.error || data.message || 'Registration failed');
                }
            }

            // Store user data and token
            localStorage.setItem('user', JSON.stringify(data.user));
            localStorage.setItem('token', data.token);

            // Redirect to home page
            navigate('/');
        } catch (error) {
            setError(error.message);
            console.error('Registration error:', error);
        } finally {
            setIsLoading(false);
        }
    };
    
    // Helper function to get a field's error message
    const getFieldError = (fieldName) => {
        return validationErrors[fieldName] && validationErrors[fieldName][0];
    };

    return (
        <div className="register-container">
            <div className="register-form">
                <h2>Create an Account</h2>
                <p>Join Nard Candles to start shopping</p>

                {error && <div className="error-message">{error}</div>}

                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label htmlFor="first_name">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value={formData.first_name}
                            onChange={handleChange}
                            required
                            placeholder="Enter your first name"
                            className={getFieldError('first_name') ? 'error-input' : ''}
                        />
                        {getFieldError('first_name') && (
                            <div className="field-error">{getFieldError('first_name')}</div>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="last_name">Last Name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value={formData.last_name}
                            onChange={handleChange}
                            required
                            placeholder="Enter your last name"
                            className={getFieldError('last_name') ? 'error-input' : ''}
                        />
                        {getFieldError('last_name') && (
                            <div className="field-error">{getFieldError('last_name')}</div>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value={formData.email}
                            onChange={handleChange}
                            required
                            placeholder="Enter your email"
                            className={getFieldError('email') ? 'error-input' : ''}
                        />
                        {getFieldError('email') && (
                            <div className="field-error">{getFieldError('email')}</div>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            value={formData.password}
                            onChange={handleChange}
                            required
                            placeholder="Create a password"
                            className={getFieldError('password') ? 'error-input' : ''}
                        />
                        {getFieldError('password') && (
                            <div className="field-error">{getFieldError('password')}</div>
                        )}
                    </div>

                    <div className="form-group">
                        <label htmlFor="password_confirmation">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            value={formData.password_confirmation}
                            onChange={handleChange}
                            required
                            placeholder="Confirm your password"
                            className={getFieldError('password_confirmation') ? 'error-input' : ''}
                        />
                        {getFieldError('password_confirmation') && (
                            <div className="field-error">{getFieldError('password_confirmation')}</div>
                        )}
                    </div>

                    <button type="submit" className="register-button" disabled={isLoading}>
                        {isLoading ? 'Creating Account...' : 'Create Account'}
                    </button>
                </form>

                <div className="login-link">
                    Already have an account? <a href="/login">Sign in</a>
                </div>
            </div>
        </div>
    );
};

export default Register; 