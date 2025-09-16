<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved</title>
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
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Welcome to Our Library!</h1>
        <p>Your account has been approved</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userName }},</h2>
        
        <p>Great news! Your library account application has been <strong>approved</strong>. You now have full access to our library system.</p>
        
        <div class="info-box">
            <h3>Account Details:</h3>
            <ul>
                <li><strong>Email:</strong> {{ $userEmail }}</li>
                <li><strong>Role:</strong> {{ $role }}</li>
                <li><strong>Status:</strong> Active</li>
            </ul>
        </div>
        
        <p>You can now:</p>
        <ul>
            <li>Browse our extensive book collection</li>
            <li>Borrow books online</li>
            <li>Track your reading history</li>
            <li>Access your personalized dashboard</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">Login to Your Account</a>
        </div>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact our library staff.</p>
        
        <p>Happy reading!</p>
        
        <p><strong>The Library Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>
