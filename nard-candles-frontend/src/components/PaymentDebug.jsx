import React from 'react';
import './PaymentDebug.css';

const PaymentDebug = ({ payload, response }) => {
  if (!payload && !response) return null;

  // Extract email from various places in case it's not directly in payload
  let emailValue = '';
  if (payload && payload.email) {
    emailValue = payload.email;
  } else if (response && response.debug_info && response.debug_info.email_used) {
    emailValue = response.debug_info.email_used;
  } else if (response && response.debug_info && response.debug_info.payload && response.debug_info.payload.email) {
    emailValue = response.debug_info.payload.email;
  }

  const hasEmailError = response && response.details && 
                        response.details.message && 
                        response.details.message.email;

  return (
    <div className="payment-debug">
      <h3>Payment Debug Information</h3>
      
      {emailValue && (
        <div className={`email-debug ${hasEmailError ? 'error-highlight' : ''}`}>
          <h4>Email Field Value</h4>
          <div className="debug-content">
            <p><strong>Email being sent:</strong> <span className="email-value">{emailValue}</span></p>
            {hasEmailError && (
              <div className="validation-error">
                <p><strong>Email Validation Error:</strong> {response.details.message.email.join(', ')}</p>
              </div>
            )}
          </div>
        </div>
      )}
      
      {payload && (
        <div className="debug-section">
          <h4>Complete Payment Payload</h4>
          <div className="debug-content">
            <pre>{JSON.stringify(payload, null, 2)}</pre>
          </div>
        </div>
      )}
      
      {response && (
        <div className="debug-section">
          <h4>Response from Server</h4>
          <div className="debug-content">
            <pre>{JSON.stringify(response, null, 2)}</pre>
          </div>
        </div>
      )}
      
      <div className="debug-help">
        <p>
          <strong>Email Validation Tips:</strong><br />
          1. Make sure your email format is valid (name@domain.com)<br />
          2. Use a real domain (gmail.com, outlook.com, etc.) not example.com<br />
          3. Avoid test@ or user@ prefixes which Chapa may reject<br />
          4. Your current email field might need updating in your profile<br />
        </p>
      </div>
    </div>
  );
};

export default PaymentDebug; 