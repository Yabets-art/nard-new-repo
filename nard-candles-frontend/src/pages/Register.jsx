import React, { useState, useEffect } from 'react';
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
    const [passwordStrength, setPasswordStrength] = useState('');

    // Client-side validation rules
    const validateField = (name, value) => {
        switch (name) {
            case 'first_name':
            case 'last_name':
                if (!value.trim()) return 'This field is required';
                if (!/^[A-Za-z\s]+$/.test(value)) return 'Only letters are allowed';
                if (value.length < 2) return 'Must be at least 2 characters';
                return '';
            
            case 'email':
                if (!value) return 'Email is required';
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return 'Invalid email format';
                return '';
            
            case 'password':
                if (!value) return 'Password is required';
                if (value.length < 8) return 'Password must be at least 8 characters';
                return '';
            
            case 'password_confirmation':
                if (!value) return 'Please confirm your password';
                if (value !== formData.password) return 'Passwords do not match';
                return '';
            
            default:
                return '';
        }
    };

    // Check password strength
    const checkPasswordStrength = (password) => {
        if (!password) return '';
        
        const hasLower = /[a-z]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const length = password.length;

        const strength = 
            (hasLower ? 1 : 0) +
            (hasUpper ? 1 : 0) +
            (hasNumber ? 1 : 0) +
            (hasSpecial ? 1 : 0) +
            (length >= 8 ? 1 : 0);

        if (strength < 2) return 'weak';
        if (strength < 4) return 'medium';
        return 'strong';
    };

    // Validate field on change
    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
        
        // Update password strength if password field
        if (name === 'password') {
            setPasswordStrength(checkPasswordStrength(value));
        }

        // Validate field
        const error = validateField(name, value);
        setValidationErrors(prev => ({
            ...prev,
            [name]: error ? [error] : null
        }));
    };

    // Validate all fields
    const validateForm = () => {
        const errors = {};
        Object.keys(formData).forEach(key => {
            const error = validateField(key, formData[key]);
            if (error) errors[key] = [error];
        });
        setValidationErrors(errors);
        return Object.keys(errors).length === 0;
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        // Validate all fields before submission
        if (!validateForm()) {
            setError('Please correct the errors in the form');
            return;
        }

        setIsLoading(true);
        setError(null);

        try {
            const response = await fetch('http://127.0.0.1:8000/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            const data = await response.json();
            
            if (!response.ok) {
                if (response.status === 422 && data.messages) {
                    setValidationErrors(data.messages);
                    throw new Error('Please correct the validation errors');
                } else {
                    throw new Error(data.error || data.message || 'Registration failed');
                }
            }

            localStorage.setItem('user', JSON.stringify(data.user));
            localStorage.setItem('token', data.token);
            navigate('/');
        } catch (error) {
            setError(error.message);
            console.error('Registration error:', error);
        } finally {
            setIsLoading(false);
        }
    };
    
    const getFieldError = (fieldName) => {
        return validationErrors[fieldName] && validationErrors[fieldName][0];
    };

    const getInputClassName = (fieldName) => {
        if (getFieldError(fieldName)) return 'error';
        if (formData[fieldName] && !getFieldError(fieldName)) return 'valid';
        return '';
    };

    return (
        <div className="register-container">
            <div className="register-form">
                <h2>Create an Account</h2>
                <p>Join Nard Candles to start shopping</p>

                {error && <div className="error-message">{error}</div>}

                <form onSubmit={handleSubmit} noValidate>
                    <div className="form-group">
                        <label htmlFor="first_name">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value={formData.first_name}
                            onChange={handleChange}
                            className={getInputClassName('first_name')}
                            placeholder="Enter your first name"
                        />
                        <span className="validation-icon"></span>
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
                            className={getInputClassName('last_name')}
                            placeholder="Enter your last name"
                        />
                        <span className="validation-icon"></span>
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
                            className={getInputClassName('email')}
                            placeholder="Enter your email"
                        />
                        <span className="validation-icon"></span>
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
                            className={getInputClassName('password')}
                            placeholder="Create a password"
                        />
                        <span className="validation-icon"></span>
                        {passwordStrength && (
                            <div className={`password-strength strength-${passwordStrength}`}></div>
                        )}
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
                            className={getInputClassName('password_confirmation')}
                            placeholder="Confirm your password"
                        />
                        <span className="validation-icon"></span>
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