import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import './Profile.css';

const Profile = () => {
    const { user, isAuthenticated, login } = useAuth();
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        first_name: '',
        last_name: '',
        email: '',
        phone_number: ''
    });
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState(null);

    useEffect(() => {
        if (!isAuthenticated) {
            navigate('/');
            return;
        }

        // Initialize form with user data
        if (user) {
            setFormData({
                first_name: user.first_name || '',
                last_name: user.last_name || '',
                email: user.email || '',
                phone_number: user.phone_number || ''
            });
        }
    }, [user, isAuthenticated, navigate]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setMessage(null);

        try {
            const token = localStorage.getItem('token');
            const response = await fetch('http://127.0.0.1:8000/api/user/update-profile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                // Update localStorage and context with new user data
                const updatedUser = {...user, ...formData};
                localStorage.setItem('user', JSON.stringify(updatedUser));
                login(updatedUser, token); // Re-use the login function to update context
                
                setMessage({
                    type: 'success',
                    text: 'Profile updated successfully!'
                });
            } else {
                setMessage({
                    type: 'error',
                    text: data.error || 'Failed to update profile'
                });
            }
        } catch (error) {
            console.error('Error updating profile:', error);
            setMessage({
                type: 'error',
                text: 'An error occurred while updating your profile'
            });
        } finally {
            setLoading(false);
        }
    };

    if (!isAuthenticated) {
        return null; // Will redirect in useEffect
    }

    return (
        <div className="profile-container">
            <h1>My Profile</h1>

            {message && (
                <div className={`message ${message.type}`}>
                    {message.text}
                </div>
            )}

            <form onSubmit={handleSubmit} className="profile-form">
                <div className="form-group">
                    <label htmlFor="first_name">First Name</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value={formData.first_name}
                        onChange={handleChange}
                        required
                    />
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
                    />
                </div>

                <div className="form-group">
                    <label htmlFor="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                        required
                    />
                    <small className="form-text">A valid email is required for payment processing</small>
                </div>

                <div className="form-group">
                    <label htmlFor="phone_number">Phone Number</label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        value={formData.phone_number}
                        onChange={handleChange}
                        required
                    />
                </div>

                <button 
                    type="submit" 
                    className="update-profile-button"
                    disabled={loading}
                >
                    {loading ? 'Updating...' : 'Update Profile'}
                </button>
            </form>
        </div>
    );
};

export default Profile; 