<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 30px;
        }
        .check-icon {
            font-size: 48px;
            color: #28a745;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✓ Email Test Successful</h1>
        <p>{{ $appName }}</p>
    </div>
    
    <div class="content">
        <div class="check-icon">✓</div>
        
        <div class="success-box">
            <h3>🎉 Congratulations!</h3>
            <p>Your email configuration is working correctly. This is a test email to verify that your SMTP settings are properly configured.</p>
        </div>
        
        <div class="info-box">
            <h3>Test Details</h3>
            <ul>
                <li><strong>Application:</strong> {{ $appName }}</li>
                <li><strong>Sent At:</strong> {{ $testTime }}</li>
                <li><strong>Status:</strong> Successfully Delivered</li>
            </ul>
        </div>
        
        <h3>What's Next?</h3>
        <p>Now that your email system is configured, you will receive notifications for:</p>
        <ul>
            <li>📚 Book loan requests and approvals</li>
            <li>📅 Due date reminders</li>
            <li>📖 Book returns</li>
            <li>👤 User account updates</li>
            <li>📊 System notifications</li>
        </ul>
        
        <p><strong>Note:</strong> This is an automated test message. No action is required.</p>
    </div>
    
    <div class="footer">
        <p>This is an automated test email from {{ $appName }}</p>
        <p>&copy; {{ date('Y') }} All rights reserved.</p>
    </div>
</body>
</html>

