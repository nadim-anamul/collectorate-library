<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Application Update</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
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
        <h1>📧 Application Update</h1>
        <p>Regarding your library account application</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userName }},</h2>
        
        <p>Thank you for your interest in joining our library. After reviewing your application, we regret to inform you that we cannot approve your account at this time.</p>
        
        <div class="warning-box">
            <h3>Reason for Rejection:</h3>
            <p>{{ $reason }}</p>
        </div>
        
        <p>If you believe this decision was made in error or if you have additional information that might help with your application, please feel free to contact us.</p>
        
        <p><strong>What you can do:</strong></p>
        <ul>
            <li>Review the rejection reason above</li>
            <li>Contact our library staff for clarification</li>
            <li>Reapply once you've addressed the concerns</li>
            <li>Visit our library in person for assistance</li>
        </ul>
        
        <p>You can still browse our public book catalog without an account, though borrowing privileges require approval.</p>
        
        <p>If you have any questions, please contact us at: <strong>{{ $contactEmail }}</strong></p>
        
        <p>Thank you for your understanding.</p>
        
        <p><strong>The Library Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. You can reply to this message for further assistance.</p>
    </div>
</body>
</html>