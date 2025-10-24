<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Loan Issued</title>
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
            color: white !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 2px solid #5568d3;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
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
        <h1>📚 Book Loan Issued</h1>
        <p>Your requested book is ready!</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userName }},</h2>
        
        <p>Great news! Your book loan has been issued successfully.</p>
        
        <div class="info-box">
            <h3>📖 Loan Details</h3>
            <ul>
                <li><strong>Book Title:</strong> {{ $bookTitle }}</li>
                @if($bookTitleBn)
                <li><strong>বই শিরোনাম:</strong> {{ $bookTitleBn }}</li>
                @endif
                <li><strong>Loan ID:</strong> #{{ $loanId }}</li>
                <li><strong>Issued Date:</strong> {{ $issuedAt }}</li>
                <li><strong>Due Date:</strong> {{ $dueAt }}</li>
            </ul>
        </div>
        
        <div class="warning-box">
            <h3>⚠️ Important Reminder</h3>
            <p>Please return the book by <strong>{{ $dueAt }}</strong> to avoid late fees. You can return it at the library circulation desk during working hours.</p>
        </div>
        
        <h3>📋 What to Do Next:</h3>
        <ul>
            <li>Visit the library to collect your book</li>
            <li>Show your loan ID (#{{ $loanId }}) at the circulation desk</li>
            <li>Remember to return the book before the due date</li>
            <li>Check your dashboard for all your active loans</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ $dashboardUrl }}" class="button">View My Dashboard</a>
        </div>
        
        <p>Thank you for using our library services!</p>
        
        <p><strong>Library Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply directly to this message.</p>
    </div>
</body>
</html>

