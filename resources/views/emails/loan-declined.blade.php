<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Request Declined</title>
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
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Book Request Update</h1>
        <p>Regarding your book request</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userName }},</h2>
        
        <p>We regret to inform you that your book request has been declined by the library staff.</p>
        
        <div class="info-box">
            <h3>📖 Request Details</h3>
            <ul>
                <li><strong>Book Title:</strong> {{ $bookTitle }}</li>
                @if($bookTitleBn)
                <li><strong>বই শিরোনাম:</strong> {{ $bookTitleBn }}</li>
                @endif
            </ul>
        </div>
        
        <div class="warning-box">
            <h3>Reason for Decline</h3>
            <p>{{ $reason }}</p>
        </div>
        
        <h3>What You Can Do:</h3>
        <ul>
            <li>Contact the library staff for more information</li>
            <li>Check if there are alternative books available</li>
            <li>Reserve the book for when it becomes available</li>
            <li>Visit the library for assistance</li>
        </ul>
        
        <p>If you believe this was in error or need clarification, please contact us at <strong>{{ $contactEmail }}</strong></p>
        
        <p>We appreciate your understanding.</p>
        
        <p><strong>Library Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. You can reply to this message for support.</p>
    </div>
</body>
</html>

