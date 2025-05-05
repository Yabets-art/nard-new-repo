<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Debug</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, button {
            padding: 8px;
            margin-bottom: 15px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <h1>Payment Debug Page</h1>
    
    <div class="card">
        <h2>Test Payment Initiation</h2>
        <form id="initForm">
            <label for="userId">User ID:</label>
            <input type="number" id="userId" name="user_id" value="1">
            
            <button type="submit">Initiate Payment</button>
        </form>
        <div id="initResult"></div>
    </div>
    
    <div class="card">
        <h2>Test Payment Confirmation</h2>
        <form id="confirmForm">
            <label for="txRef">Transaction Reference:</label>
            <input type="text" id="txRef" name="tx_ref" placeholder="Enter tx_ref here">
            
            <label for="status">Status:</label>
            <select id="status" name="status" style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="success">Success</option>
                <option value="failed">Failed</option>
            </select>
            
            <button type="submit">Test Confirmation</button>
        </form>
        <div id="confirmResult"></div>
    </div>
    
    <script>
        document.getElementById('initForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = document.getElementById('userId').value;
            
            fetch('/api/initiate-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_id: userId }),
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('initResult');
                resultDiv.innerHTML = '<h3>Response:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
                
                if (data.payment_url) {
                    resultDiv.innerHTML += '<p><a href="' + data.payment_url + '" target="_blank">Open Payment Page</a></p>';
                    
                    // Add instructions about the receipt viewing
                    if (data.message) {
                        resultDiv.innerHTML += '<div style="padding: 10px; background-color: #fffde7; border-left: 4px solid #fbc02d; margin: 15px 0;">' +
                            '<strong>Important:</strong> ' + data.message + '</div>';
                    }
                    
                    // Automatically fill the confirmation form with the tx_ref
                    if (data.tx_ref) {
                        document.getElementById('txRef').value = data.tx_ref;
                    }
                }
            })
            .catch(error => {
                document.getElementById('initResult').innerHTML = '<h3>Error:</h3><p>' + error.message + '</p>';
            });
        });
        
        document.getElementById('confirmForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const txRef = document.getElementById('txRef').value;
            const status = document.getElementById('status').value;
            
            // Open the confirmation URL in a new tab
            window.open('/api/confirm-payment?tx_ref=' + txRef + '&status=' + status, '_blank');
        });
    </script>
</body>
</html> 