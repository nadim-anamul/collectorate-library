<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Due Soon Reminder</title>
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
            background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
            color: #333;
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
            background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
            color: #333 !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 2px solid #e89440;
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
        <h1>⏰ Reminder: Book Due Soon</h1>
        <p>Don't forget to return your book!</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userName }},</h2>
        
        <p>This is a friendly reminder that your borrowed book is due soon.</p>
        
        <div class="warning-box">
            <h3>📅 Due Date Approaching</h3>
            <p style="font-size: 18px; margin: 0;">
                <strong>{{ $daysRemaining }} day{{ $daysRemaining > 1 ? 's' : '' }} remaining</strong>
            </p>
            <p style="margin: 10px 0 0 0;">Due on: <strong>{{ $dueAt }}</strong></p>
        </div>
        
        <div class="info-box">
            <h3>📖 Loan Details</h3>
            <ul>
                <li><strong>Book Title:</strong> {{ $bookTitle }}</li>
                @if($bookTitleBn)
                <li><strong>বই শিরোনাম:</strong> {{ $bookTitleBn }}</li>
                @endif
                <li><strong>Loan ID:</strong> #{{ $loanId }}</li>
                <li><strong>Due Date:</strong> {{ $dueAt }}</li>
            </ul>
        </div>
        
        <h3>📋 Action Required:</h3>
        <ul>
            <li>Return the book to the library by {{ $dueAt }}</li>
            <li>Visit during library working hours</li>
            <li>Avoid late fees by returning on time</li>
            <li>Contact us if you need to extend the loan</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ $dashboardUrl }}" class="button">View My Loans</a>
        </div>
        
        <p>Thank you for using our library!</p>
        
        <p><strong>Library Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated reminder. Please do not reply directly to this message.</p>
    </div>
</body>
</html>

