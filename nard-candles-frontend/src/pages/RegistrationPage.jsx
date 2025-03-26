import { useState } from "react";
import "./RegistrationPage.css"; // Import custom CSS

const RegistrationPage = () => {
  const [formData, setFormData] = useState({
    firstName: "",
    lastName: "",
    address: "",
    phoneNumber: "",
    email: "",
    educationalStatus: "",
    trainingType: "",
    trainingDay: "",
  });

  const trainingTypes = ["Basic Candle Making", "Advanced Techniques", "Business Setup"];
  const availableDates = ["2024-11-20", "2024-11-25", "2024-12-01"]; // Example dates

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log("Registration data:", formData);
    // Add logic to send formData to the backend
  };

  return (
    <div className="registration-container">
      <div className="registration-form">
        <h2>Register for Training</h2>
        <form onSubmit={handleSubmit}>
          <div className="form-grid">
            <input
              type="text"
              name="firstName"
              placeholder="First Name"
              value={formData.firstName}
              onChange={handleInputChange}
              required
            />
            <input
              type="text"
              name="lastName"
              placeholder="Last Name"
              value={formData.lastName}
              onChange={handleInputChange}
              required
            />
            <input
              type="text"
              name="address"
              placeholder="Address"
              value={formData.address}
              onChange={handleInputChange}
              required
            />
            <input
              type="tel"
              name="phoneNumber"
              placeholder="Phone Number"
              value={formData.phoneNumber}
              onChange={handleInputChange}
              required
            />
            <input
              type="email"
              name="email"
              placeholder="Email"
              value={formData.email}
              onChange={handleInputChange}
              required
            />
            <select
              name="educationalStatus"
              value={formData.educationalStatus}
              onChange={handleInputChange}
              required
            >
              <option value="">Select Educational Status</option>
              <option value="High School">High School</option>
              <option value="Diploma">Diploma</option>
              <option value="Bachelor's Degree">Bachelors Degree</option>
              <option value="Master's Degree">Masters Degree</option>
              <option value="Other">Other</option>
            </select>
            <select
              name="trainingType"
              value={formData.trainingType}
              onChange={handleInputChange}
              required
            >
              <option value="">Select Training Type</option>
              {trainingTypes.map((type, index) => (
                <option key={index} value={type}>
                  {type}
                </option>
              ))}
            </select>
            <select
              name="trainingDay"
              value={formData.trainingDay}
              onChange={handleInputChange}
              required
            >
              <option value="">Select Training Day</option>
              {availableDates.map((date, index) => (
                <option key={index} value={date}>
                  {date}
                </option>
              ))}
            </select>
          </div>
          <button type="submit">Register Now</button>
        </form>
      </div>
    </div>
  );
};

export default RegistrationPage;
