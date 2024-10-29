import './PrivacyPolicy.css';

const PrivacyPolicy = () => {
  return (
    <div className="content-page">
      <h1>Privacy Policy</h1>
      <p className="effective-date">
        <strong>Effective Date:</strong> August 30, 2024
      </p>
      <p>
        <strong>We value your privacy.</strong> This Privacy Policy outlines how we collect, use, disclose, and protect your personal information when you visit our website located at <a href="https://nardcandles.com" target="_blank" rel="noopener noreferrer">nardcandles.com</a> or use our services. By using our Site, you agree to the terms of this Privacy Policy.
      </p>
      <h4>1. Information We Collect</h4>
      <ul>
        <li><strong>Personal Information:</strong> Name, email address, phone number, shipping and billing address, payment information.</li>
        <li><strong>Usage Data:</strong> IP address, browser type, device information, pages visited, referring website.</li>
        <li><strong>Cookies and Tracking Technologies:</strong> We use cookies to enhance your experience.</li>
      </ul>

      <h4>2. How We Use Your Information</h4>
      <ul>
        <li>To process orders and communicate with you.</li>
        <li>To improve our Site and services.</li>
        <li>To send promotional materials.</li>
        <li>To comply with legal obligations.</li>
      </ul>

      <h4>3. How We Share Your Information</h4>
      <ul>
        <li><strong>Third-Party Service Providers:</strong> We may share your information with providers who assist us.</li>
        <li><strong>Legal Requirements:</strong> We may disclose information if required by law.</li>
        <li><strong>Business Transfers:</strong> Your information may be transferred in the event of a merger or sale.</li>
      </ul>

      <h4>4. Data Security</h4>
      <p>We implement security measures but cannot guarantee absolute security.</p>

      <h4>5. Your Rights and Choices</h4>
      <ul>
        <li><strong>Access and Update:</strong> You can access and update your information.</li>
        <li><strong>Opt-Out:</strong> You may opt out of promotional communications.</li>
        <li><strong>Do Not Track:</strong> We do not respond to Do Not Track signals.</li>
      </ul>

      <h4>6. Children Privacy</h4>
      <p>Our Site is not intended for children under 18. We do not knowingly collect their information.</p>

      <h4>7. Changes to This Privacy Policy</h4>
      <p>We may update this Privacy Policy and encourage you to review it periodically.</p>

      <h4>8. Contact Us</h4>
      <p>If you have questions about this Privacy Policy, contact us at <a href="mailto:nardosabera248@gmail.com">nardosabera248@gmail.com</a>.</p>

      <footer className="privacy-footer">
        <p>&copy; {new Date().getFullYear()} Nard Candles. All rights reserved.</p>
      </footer>
    </div>
  );
};

export default PrivacyPolicy;
