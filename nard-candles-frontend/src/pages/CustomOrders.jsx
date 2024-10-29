import axios from 'axios'; 
import { useState } from 'react';
import './CustomOrders.css'; // Import the CSS file

const CustomOrders = () => {
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [email, setEmail] = useState('');
  const [preferences, setPreferences] = useState('');
  const [image, setImage] = useState(null);
  const [showModal, setShowModal] = useState(false); // State for modal visibility

  const handleFormSubmit = async (e) => {
    e.preventDefault();
  
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
      
      // Show the thank-you modal and clear form fields
      setShowModal(true);
      setName('');
      setPhone('');
      setEmail('');
      setPreferences('');
      setImage(null);
    } catch (error) {
      console.error('Submission error:', error);
      alert('There was an error submitting the order. Please try again.');
    }
  };

  const closeModal = () => {
    setShowModal(false); // Close the modal
  };

  return (
    <div className="custom-orders">
      <h2>Custom Orders</h2>
      <form onSubmit={handleFormSubmit}>
        <div className="form-group">
          <label htmlFor="name">Name:</label>
          <input type="text" value={name} onChange={(e) => setName(e.target.value)} required />
        </div>
        <div className="form-group">
          <label htmlFor="phone">Phone:</label>
          <input type="text" value={phone} onChange={(e) => setPhone(e.target.value)} />
        </div>
        <div className="form-group">
          <label htmlFor="email">Email:</label>
          <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} />
        </div>
        <div className="form-group">
          <label htmlFor="preferences">Preferences:</label>
          <textarea value={preferences} onChange={(e) => setPreferences(e.target.value)} required placeholder='Enter your preferences (make sure to specify the quantity)'/>
        </div>
        <div className="form-group">
          <label htmlFor="image">Image:</label>
          <input type="file" onChange={(e) => setImage(e.target.files[0])} />
        </div>
        <button type="submit">Submit Order</button>
      </form>

      {/* Modal for thank-you message */}
      {showModal && (
        <div className="modal-overlay">
          <div className="modal-content">
            <h3>Thank You!</h3>
            <p>Thank you for choosing us! We will contact you on your email soon...</p>
            <button onClick={closeModal}>Close</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default CustomOrders;
