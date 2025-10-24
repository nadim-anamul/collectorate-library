<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Request Approved</title>
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
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 2px solid #0e7c74;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>✓ Request Approved!</h1>
        <p>Your book request has been approved</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userName }},</h2>
        
        <div class="success-box">
            <h3>🎉 Congratulations!</h3>
            <p>Your request for the book has been approved by the library staff.</p>
        </div>
        
        <div class="info-box">
            <h3>📖 Loan Details</h3>
            <ul>
                <li><strong>Book Title:</strong> {{ $bookTitle }}</li>
                @if($bookTitleBn)
                <li><strong>বই শিরোনাম:</strong> {{ $bookTitleBn }}</li>
                @endif
                <li><strong>Loan ID:</strong> #{{ $loanId }}</li>
                <li><strong>Issue Date:</strong> {{ $issuedAt }}</li>
                <li><strong>Due Date:</strong> {{ $dueAt }}</li>
                <li><strong>Status:</strong> <span style="color: #28a745;">Approved & Issued</span></li>
            </ul>
        </div>
        
        <h3>📋 Next Steps:</h3>
        <ul>
            <li>Visit the library to collect your book</li>
            <li>Bring your library card or ID</li>
            <li>Show your loan ID (#{{ $loanId }}) at the desk</li>
            <li>Remember to return by {{ $dueAt }}</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ $dashboardUrl }}" class="button">View My Loans</a>
        </div>
        
        <p>Happy reading!</p>
        
        <p><strong>Library Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply directly to this message.</p>
    </div>
</body>
</html>

