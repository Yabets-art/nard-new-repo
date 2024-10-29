import './TermsOfService.css';

const TermsOfService = () => {
  return (
    <div className="content-page">
      <h1>Terms of Service</h1>
      <p className="effective-date">
        <strong>Effective Date:</strong> August 30, 2024
      </p>
      <p>
        We operate the website located at <a href="https://nardcandles.com" target="_blank" rel="noopener noreferrer">nardcandles.com</a>. These Terms of Service govern your use of our Site and services. By accessing or using our Site, you agree to these Terms of Service.
      </p>
      <h5>1. Acceptance of Terms</h5>
      <p>By accessing and using our Site, you agree to comply with and be bound by these Terms of Service and our Privacy Policy. If you do not agree to these terms, you must not use our Site.</p>

      <h5>2. Changes to Terms</h5>
      <p>We may update these Terms of Service from time to time. Your continued use of our Site constitutes acceptance of any changes.</p>

      <h5>3. User Responsibilities</h5>
      <p>You agree to use our Site for lawful purposes only and not to engage in any conduct that could damage, disable, or impair our Site or interfere with other users access.</p>

      <h5>4. Intellectual Property</h5>
      <p>All content on our Site, including text, images, and logos, is the property of Nard Candles and is protected by intellectual property laws. You may not use, reproduce, or distribute any content without our prior written permission.</p>

      <h5>5. Limitation of Liability</h5>
      <p>Nard Candles is not liable for any direct, indirect, incidental, or consequential damages arising from your use of our Site. We make no warranties or representations about the accuracy or completeness of the content on our Site.</p>

      <h5>6. Governing Law</h5>
      <p>These Terms of Service are governed by and construed in accordance with the laws of GVT. Any disputes arising from these terms will be resolved in the courts of GVT.</p>

      <h5>7. Contact Us</h5>
      <p>If you have any questions about these Terms of Service, please contact us at <a href="mailto:nardosabera248@gmail.com">nardosabera248@gmail.com</a>.</p>

      <footer className="terms-footer">
        <p>&copy; {new Date().getFullYear()} Nard Candles. All rights reserved.</p>
      </footer>
    </div>
  );
};

export default TermsOfService;
