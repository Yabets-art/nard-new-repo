import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import './EmailTest.css';

const EmailTest = () => {
    const { user } = useAuth();
    const [email, setEmail] = useState(user?.email || '');
    const [results, setResults] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    const testEmail = async () => {
        setLoading(true);
        setError('');

        try {
            const token = localStorage.getItem('token');
            const response = await fetch('http://127.0.0.1:8000/api/test-email-validation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();
            
            if (response.ok) {
                setResults([
                    { timestamp: new Date().toLocaleTimeString(), email, result: data.valid ? 'Valid' : 'Invalid', details: data.message }
                ]);
            } else {
                setError(data.error || 'Failed to test email');
            }
        } catch (err) {
            setError('Error: ' + err.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="email-test-container">
            <h1>Email Validation Test Tool</h1>
            <p className="description">
                This tool helps test which email formats are accepted by the Chapa payment system.
            </p>

            <div className="test-form">
                <div className="input-group">
                    <label htmlFor="email">Test Email</label>
                    <input
                        type="text"
                        id="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        placeholder="Enter email to test"
                    />
                </div>

                <button 
                    className="test-button"
                    onClick={testEmail}
                    disabled={loading}
                >
                    {loading ? 'Testing...' : 'Test Email'}
                </button>
            </div>

            {error && <div className="error-message">{error}</div>}

            {results.length > 0 && (
                <div className="results-container">
                    <h2>Test Results</h2>
                    <table className="results-table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Email</th>
                                <th>Result</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            {results.map((result, index) => (
                                <tr key={index}>
                                    <td>{result.timestamp}</td>
                                    <td>{result.email}</td>
                                    <td className={result.result === 'Valid' ? 'valid-result' : 'invalid-result'}>
                                        {result.result}
                                    </td>
                                    <td>{result.details}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}

            <div className="troubleshooting">
                <h3>Common Email Format Requirements</h3>
                <ul>
                    <li>Must contain @ symbol with domain part</li>
                    <li>Domain must have at least one period (.)</li>
                    <li>No spaces allowed</li>
                    <li>Some systems reject disposable email domains</li>
                    <li>Some payment systems require real domains only (no example.com)</li>
                </ul>
            </div>
        </div>
    );
};

export default EmailTest; 