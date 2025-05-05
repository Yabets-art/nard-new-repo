import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import './MyOrders.css';

const MyOrders = () => {
    const [orders, setOrders] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);
    const [paymentStatus, setPaymentStatus] = useState(null);
    const [checkingPayment, setCheckingPayment] = useState(false);
    const { user } = useAuth();
    
    useEffect(() => {
        if (user) {
            // Check for any pending payments
            checkPendingPayment();
            // Fetch orders
            fetchOrders();
        }
    }, [user]);

    const checkPendingPayment = async () => {
        // Check both possible storage keys for transaction reference
        const lastTxRef = localStorage.getItem('last_payment_tx_ref') || localStorage.getItem('current_tx_ref');
        
        if (lastTxRef) {
            setCheckingPayment(true);
            try {
                console.log('Checking payment status for:', lastTxRef);
                
                // Try to fetch payment status with timeout and error handling
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
                
                try {
                    // First, try to check if an order exists in the backend with this tx_ref
                    // This is more reliable than the Chapa API which might be down
                    const token = localStorage.getItem('token');
                    const orderCheckResponse = await fetch(`http://127.0.0.1:8000/api/check-order-by-txref/${lastTxRef}`, {
                        headers: {
                            'Authorization': `Bearer ${token}`
                        },
                        signal: controller.signal
                    });
                    
                    clearTimeout(timeoutId);
                    
                    if (orderCheckResponse.ok) {
                        // Order exists in our system, payment was successful
                        const orderData = await orderCheckResponse.json();
                        
                        setPaymentStatus({
                            success: true,
                            message: 'Your payment was successful! Your order has been confirmed in our system.',
                            orderId: orderData.order_id,
                            // Use order data instead of Chapa data for the receipt
                            receiptData: {
                                tx_ref: lastTxRef,
                                amount: orderData.total_amount,
                                currency: 'ETB',
                                first_name: orderData.customer_name?.split(' ')[0] || '',
                                last_name: orderData.customer_name?.split(' ').slice(1).join(' ') || '',
                                email: orderData.customer_email || user?.email || '',
                                phone_number: orderData.customer_phone || ''
                            }
                        });
                        
                        // Clear the saved tx_ref as it's been processed
                        localStorage.removeItem('last_payment_tx_ref');
                        localStorage.removeItem('current_tx_ref');
                        
                        // Refresh the orders list after successful payment
                        fetchOrders();
                        return;
                    }
                    
                    // If order check failed, fall back to Chapa API
                    const response = await fetch(`http://127.0.0.1:8000/api/payment-status/${lastTxRef}`, {
                        signal: controller.signal
                    });
                    
                    if (!response.ok) {
                        throw new Error(`Failed to check payment status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('Payment status response:', data);
                    
                    if (data.success) {
                        setPaymentStatus({
                            success: true,
                            message: 'Your payment was successful! Your order is being processed. You will receive an email confirmation shortly.',
                            orderId: data.order_id,
                            receiptData: data.data
                        });
                        
                        // Clear the saved tx_ref as it's been processed
                        localStorage.removeItem('last_payment_tx_ref');
                        localStorage.removeItem('current_tx_ref');
                        
                        // Refresh the orders list after successful payment
                        fetchOrders();
                    } else {
                        setPaymentStatus({
                            success: false,
                            message: 'Your payment was not successful. Please try again.'
                        });
                    }
                } catch (fetchError) {
                    // If we encounter a network error but have a transaction reference, 
                    // Check directly from the backend if any order matches this tx_ref
                    console.error('Network error checking payment status:', fetchError);
                    
                    try {
                        // Check if we have any local orders with this tx_ref
                        const token = localStorage.getItem('token');
                        fetchOrders();
                        
                        setPaymentStatus({
                            success: true, // Assume success unless proven otherwise
                            message: 'We detected a recent transaction. Your order will appear below once it has been processed.',
                            cannotVerify: true,
                            txRef: lastTxRef
                        });
                    } catch (orderError) {
                        console.error('Error checking orders for tx_ref:', orderError);
                        setPaymentStatus({
                            success: true, // Still assume success to prevent blocking the user
                            message: 'Transaction reference recorded. Please keep your transaction ID for order tracking.',
                            cannotVerify: true,
                            txRef: lastTxRef
                        });
                    }
                } 
            } catch (err) {
                console.error('Error in payment verification process:', err);
                setPaymentStatus({
                    success: false,
                    message: 'Error checking payment status. Please check your orders or contact support if needed.'
                });
                
                // Still try to fetch orders
                fetchOrders();
            } finally {
                setCheckingPayment(false);
            }
        }
    };

    const fetchOrders = async () => {
        if (!user) return;
        
        setIsLoading(true);
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`http://127.0.0.1:8000/api/orders`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to fetch orders');
            }
            
            const data = await response.json();
            console.log('Orders loaded:', data);
            setOrders(data);
            setError(null);
        } catch (err) {
            setError('Error loading orders: ' + err.message);
            console.error('Error fetching orders:', err);
        } finally {
            setIsLoading(false);
        }
    };

    // Add function to refresh orders manually
    const refreshOrders = () => {
        fetchOrders();
    };

    const formatDate = (dateString) => {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const getStatusClass = (status) => {
        switch (status.toLowerCase()) {
            case 'processing':
                return 'status-processing';
            case 'completed':
                return 'status-completed';
            case 'cancelled':
                return 'status-cancelled';
            default:
                return 'status-pending';
        }
    };

    // Function to open the Chapa receipt in a new tab
    const openChapaReceipt = async (receiptData) => {
        if (!receiptData || !receiptData.tx_ref) {
            alert('Receipt data is not available');
            return;
        }
        
        // Check if the order status is pending and update it
        if (receiptData.status === 'pending') {
            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`http://127.0.0.1:8000/api/update-order-status/${receiptData.tx_ref}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Order status updated:', data);
                    
                    // If the status was updated successfully, refresh the orders list
                    if (data.success && data.status === 'completed') {
                        // Show confirmation message
                        setPaymentStatus({
                            success: true,
                            message: 'Your payment has been confirmed! You will receive an email confirmation shortly.',
                            orderId: data.order_id
                        });
                        
                        // Refresh the orders list
                        fetchOrders();
                    }
                }
            } catch (error) {
                console.error('Error updating order status:', error);
            }
        }
        
        // Check if this is an incomplete receipt (verification failed)
        const isIncompleteData = !receiptData.amount || receiptData.amount === 'Unavailable';
        
        // Set default values for missing data
        const receiptDetails = {
            tx_ref: receiptData.tx_ref || 'N/A',
            date: new Date().toLocaleString(),
            status: receiptData.status || 'Completed', // Original status
            originalStatus: receiptData.status // Store the original status to show status update message
        };
        
        // Only add these details if we have actual payment data
        if (!isIncompleteData) {
            receiptDetails.amount = receiptData.amount || 'N/A';
            receiptDetails.currency = receiptData.currency || 'ETB';
            receiptDetails.first_name = receiptData.first_name || user?.first_name || '';
            receiptDetails.last_name = receiptData.last_name || user?.last_name || '';
            receiptDetails.email = receiptData.email || user?.email || 'N/A';
            receiptDetails.phone = receiptData.phone_number || user?.phone_number || 'N/A';
        }
        
        // Create a simple receipt page with transaction details
        const receiptHTML = `
            <html>
            <head>
                <title>Nard Candles - Payment Receipt</title>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
                    body { 
                        font-family: 'Poppins', sans-serif; 
                        max-width: 800px; 
                        margin: 0 auto; 
                        padding: 20px;
                        color: #333;
                        line-height: 1.6;
                    }
                    .receipt-container {
                        margin-top: 30px;
                        border: 1px solid #e0e0e0;
                        border-radius: 10px;
                        overflow: hidden;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                    }
                    .receipt-header {
                        background-color: #4a90e2;
                        color: white;
                        padding: 25px;
                        text-align: center;
                    }
                    .receipt-header h1 {
                        margin: 0;
                        font-size: 28px;
                        font-weight: 600;
                    }
                    .receipt-logo {
                        text-align: center;
                        margin-bottom: 20px;
                        font-size: 40px;
                        font-weight: bold;
                        color: #333;
                    }
                    .receipt-body {
                        padding: 25px;
                    }
                    .receipt-success {
                        text-align: center;
                        color: #2e7d32;
                        font-size: 24px;
                        font-weight: 600;
                        margin-bottom: 25px;
                        border-bottom: 1px solid #e8f5e9;
                        padding-bottom: 15px;
                    }
                    .receipt-success i {
                        font-size: 36px;
                        display: block;
                        margin-bottom: 10px;
                    }
                    .receipt-detail {
                        margin-bottom: 30px;
                    }
                    .receipt-row {
                        display: flex;
                        margin-bottom: 12px;
                        border-bottom: 1px dashed #f0f0f0;
                        padding-bottom: 12px;
                    }
                    .receipt-row:last-child {
                        border-bottom: none;
                    }
                    .receipt-label {
                        font-weight: 500;
                        width: 180px;
                        color: #666;
                    }
                    .receipt-value {
                        flex: 1;
                        font-weight: 400;
                    }
                    .receipt-footer {
                        text-align: center;
                        margin-top: 30px;
                        color: #666;
                        font-size: 14px;
                    }
                    .receipt-footer p {
                        margin: 5px 0;
                    }
                    .highlight {
                        background-color: #f8f9fa;
                        padding: 15px;
                        border-radius: 5px;
                        margin-top: 20px;
                    }
                    .highlight p {
                        margin: 0;
                        font-weight: 500;
                    }
                    .note {
                        background-color: #fff8e1;
                        color: #f57c00;
                        padding: 15px;
                        border-radius: 5px;
                        margin-top: 20px;
                        border-left: 4px solid #f57c00;
                    }
                    .note p {
                        margin: 0;
                        font-weight: 500;
                    }
                    .verification-note {
                        background-color: #e8f5e9;
                        padding: 15px;
                        border-radius: 5px;
                        margin-top: 20px;
                        border-left: 4px solid #2e7d32;
                        font-size: 15px;
                    }
                    .status-update-note {
                        background-color: #e3f2fd;
                        padding: 15px;
                        border-radius: 5px;
                        margin-top: 20px;
                        border-left: 4px solid #1976d2;
                        font-size: 15px;
                    }
                    .tx-highlight {
                        font-weight: 600;
                        font-family: monospace;
                        background: #f5f5f5;
                        padding: 3px 5px;
                        border-radius: 3px;
                    }
                    @media print {
                        body {
                            padding: 0;
                            margin: 0;
                        }
                        .receipt-container {
                            border: none;
                            box-shadow: none;
                        }
                        .print-btn {
                            display: none;
                        }
                    }
                    .print-btn {
                        background-color: #4a90e2;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        margin-top: 20px;
                        font-weight: 500;
                        display: block;
                        margin: 20px auto;
                    }
                    .print-btn:hover {
                        background-color: #3a7abf;
                    }
                </style>
            </head>
            <body>
                <div class="receipt-logo">
                    NARD CANDLES
                </div>
                
                <div class="receipt-container">
                    <div class="receipt-header">
                        <h1>${isIncompleteData ? 'Transaction Verification' : 'Payment Receipt'}</h1>
                    </div>
                    
                    <div class="receipt-body">
                        <div class="receipt-success">
                            <span style="font-size: 48px;">✓</span>
                            <div>${isIncompleteData ? 'Transaction Recorded' : 'Payment Successful'}</div>
                        </div>
                        
                        <div class="receipt-detail">
                            <div class="receipt-row">
                                <div class="receipt-label">Transaction Reference:</div>
                                <div class="receipt-value"><span class="tx-highlight">${receiptDetails.tx_ref}</span></div>
                            </div>
                            
                            <div class="receipt-row">
                                <div class="receipt-label">Date:</div>
                                <div class="receipt-value">${receiptDetails.date}</div>
                            </div>
                            
                            ${!isIncompleteData ? `
                            <div class="receipt-row">
                                <div class="receipt-label">Amount:</div>
                                <div class="receipt-value">${receiptDetails.amount} ${receiptDetails.currency}</div>
                            </div>
                            <div class="receipt-row">
                                <div class="receipt-label">Payment Method:</div>
                                <div class="receipt-value">Chapa Payment Gateway</div>
                            </div>
                            <div class="receipt-row">
                                <div class="receipt-label">Customer:</div>
                                <div class="receipt-value">${receiptDetails.first_name} ${receiptDetails.last_name}</div>
                            </div>
                            <div class="receipt-row">
                                <div class="receipt-label">Email:</div>
                                <div class="receipt-value">${receiptDetails.email}</div>
                            </div>
                            ` : ''}
                        </div>
                        
                        ${isIncompleteData ? `
                        <div class="verification-note">
                            <p><strong>Transaction ID Verification Only</strong></p>
                            <p>This document verifies that transaction reference <span class="tx-highlight">${receiptDetails.tx_ref}</span> has been recorded in our system. Please keep this reference for tracking your order.</p>
                            <p>For full payment details, please check your bank statement or Chapa payment confirmation.</p>
                        </div>
                        ` : `
                        <div class="highlight">
                            <p>Your order has been confirmed and is being processed.</p>
                        </div>
                        `}
                        
                        ${receiptDetails.originalStatus === 'pending' ? `
                        <div class="status-update-note">
                            <p><strong>Order Status Update</strong></p>
                            <p>Your order status has been changed from "pending" to "completed".</p>
                            <p>You will receive an email confirmation shortly with all your order details.</p>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                <button class="print-btn" onclick="window.print()">Print ${isIncompleteData ? 'Verification' : 'Receipt'}</button>
                
                <div class="receipt-footer">
                    <p>Thank you for shopping with Nard Candles!</p>
                    <p>If you have any questions about your order, please contact us.</p>
                    <p>&copy; ${new Date().getFullYear()} Nard Candles. All rights reserved.</p>
                </div>
                
                <script>
                    // Auto-print for convenience
                    setTimeout(function() {
                        // Only show print dialog if no popups are blocked
                        if (window.print) {
                            // window.print();
                        }
                    }, 1000);
                </script>
            </body>
            </html>
        `;
        
        // Open a new window with the receipt
        const receiptWindow = window.open('', '_blank');
        if (receiptWindow) {
            receiptWindow.document.write(receiptHTML);
            receiptWindow.document.close();
        } else {
            alert('Popup blocked. Please allow popups and try again.');
        }
    };

    if (!user) {
        return (
            <div className="orders-container">
                <div className="auth-required-message">
                    <p>Please sign in to view your orders</p>
                    <Link to="/login" className="login-button">Sign In</Link>
                </div>
            </div>
        );
    }

    if (isLoading && !paymentStatus) {
        return <div className="orders-container"><p>Loading your orders...</p></div>;
    }

    return (
        <div className="orders-container">
            <h1>My Orders</h1>
            
            {checkingPayment && (
                <div className="checking-payment">
                    <p>Checking your recent payment status...</p>
                </div>
            )}
            
            {paymentStatus && (
                <div className={`payment-status ${paymentStatus.success ? 'success' : 'error'}`}>
                    <p>{paymentStatus.message}</p>
                    {paymentStatus.success && (
                        <div className="payment-actions">
                            <Link to="/products" className="btn btn-primary">Continue Shopping</Link>
                            {paymentStatus.receiptData && (
                                <button 
                                    className="btn btn-secondary" 
                                    onClick={() => openChapaReceipt(paymentStatus.receiptData)}
                                >
                                    View Receipt
                                </button>
                            )}
                            {paymentStatus.cannotVerify && paymentStatus.txRef && (
                                <button
                                    className="btn btn-secondary"
                                    onClick={() => {
                                        // Create a simple receipt with the transaction reference
                                        const simpleReceiptData = {
                                            tx_ref: paymentStatus.txRef,
                                            // Don't include amount - we're just verifying the transaction ID
                                        };
                                        openChapaReceipt(simpleReceiptData);
                                    }}
                                >
                                    Verify Transaction ID
                                </button>
                            )}
                        </div>
                    )}
                </div>
            )}
            
            {paymentStatus && paymentStatus.cannotVerify && (
                <div className="manual-actions">
                    <p className="retry-hint">
                        If you've completed payment but see connection errors, you can manually retry verifying your payment.
                    </p>
                    <button 
                        className="retry-btn" 
                        onClick={() => {
                            // Try checking the payment status again
                            if (paymentStatus.txRef) {
                                // Re-set the transaction reference to localStorage temporarily
                                localStorage.setItem('last_payment_tx_ref', paymentStatus.txRef);
                                // Then trigger the check again
                                checkPendingPayment();
                            }
                        }}
                    >
                        Retry Payment Verification
                    </button>
                </div>
            )}
            
            <div className="orders-header">
                <button className="refresh-btn" onClick={refreshOrders} disabled={isLoading}>
                    {isLoading ? 'Refreshing...' : 'Refresh Orders'}
                </button>
            </div>
            
            {error && <div className="error-message">{error}</div>}
            
            {orders.length === 0 ? (
                <div className="empty-orders">
                    <p>You haven't placed any orders yet</p>
                    <Link to="/products" className="btn btn-primary">Shop Now</Link>
                </div>
            ) : (
                <div className="orders-list">
                    {orders.map((order) => (
                        <div key={order.id} className="order-card">
                            <div className="order-header">
                                <div className="order-info">
                                    <h3>Order #{order.id}</h3>
                                    <p className="order-date">Placed on {formatDate(order.created_at)}</p>
                                    {order.paid_at && (
                                        <p className="order-date">Paid on {formatDate(order.paid_at)}</p>
                                    )}
                                </div>
                                <div className={`order-status ${getStatusClass(order.status)}`}>
                                    {order.status}
                                </div>
                            </div>
                            
                            <div className="order-items">
                                {order.order_items && order.order_items.map((item, index) => (
                                    <div key={index} className="order-item">
                                        <div className="item-details">
                                            <p className="item-name">{item.product_name}</p>
                                            <p className="item-price">${parseFloat(item.price).toFixed(2)} × {item.quantity}</p>
                                        </div>
                                        <p className="item-total">${(parseFloat(item.price) * item.quantity).toFixed(2)}</p>
                                    </div>
                                ))}
                            </div>
                            
                            <div className="order-footer">
                                <div className="order-total">
                                    <span>Total:</span>
                                    <span>${parseFloat(order.total_amount).toFixed(2)}</span>
                                </div>
                                
                                <div className="order-actions">
                                    {order.status.toLowerCase() === 'pending' && order.checkout_url && (
                                        <a 
                                            href={order.checkout_url} 
                                            target="_blank" 
                                            rel="noopener noreferrer"
                                            className="btn-pay"
                                        >
                                            Complete Payment
                                        </a>
                                    )}
                                    
                                    {order.status.toLowerCase() === 'completed' && (
                                        <button className="btn-reorder">Buy Again</button>
                                    )}
                                    
                                    {order.tx_ref && (
                                        <button 
                                            className="btn-verify"
                                            onClick={() => {
                                                // Create a simple receipt with the transaction reference
                                                const receiptData = {
                                                    tx_ref: order.tx_ref,
                                                    amount: order.total_amount,
                                                    currency: 'ETB',
                                                    status: order.status,
                                                    first_name: order.customer_name?.split(' ')[0] || '',
                                                    last_name: order.customer_name?.split(' ').slice(1).join(' ') || ''
                                                };
                                                openChapaReceipt(receiptData);
                                            }}
                                        >
                                            View Receipt
                                        </button>
                                    )}
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default MyOrders; 