<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Book - Urgent</title>
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
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
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
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 2px solid #d42a3d;
        }
        .danger-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
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
        <h1>⚠️ URGENT: Book Overdue</h1>
        <p>Immediate action required</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $userName }},</h2>
        
        <p><strong>IMPORTANT:</strong> Your borrowed book is now overdue and must be returned immediately.</p>
        
        <div class="danger-box">
            <h3>🚨 Overdue Notice</h3>
            <p style="font-size: 18px; margin: 0; color: #dc3545;">
                <strong>{{ $daysOverdue }} day{{ $daysOverdue > 1 ? 's' : '' }} overdue</strong>
            </p>
            <p style="margin: 10px 0 0 0;">Was due on: <strong>{{ $dueAt }}</strong></p>
        </div>
        
        <div class="info-box">
            <h3>📖 Loan Details</h3>
            <ul>
                <li><strong>Book Title:</strong> {{ $bookTitle }}</li>
                @if($bookTitleBn)
                <li><strong>বই শিরোনাম:</strong> {{ $bookTitleBn }}</li>
                @endif
                <li><strong>Loan ID:</strong> #{{ $loanId }}</li>
                <li><strong>Original Due Date:</strong> {{ $dueAt }}</li>
                <li><strong>Days Overdue:</strong> {{ $daysOverdue }}</li>
            </ul>
        </div>
        
        <h3>⚡ Immediate Action Required:</h3>
        <ul>
            <li><strong style="color: #dc3545;">Return the book to the library IMMEDIATELY</strong></li>
            <li>Late fees may apply for overdue items</li>
            <li>Your borrowing privileges may be suspended</li>
            <li>Contact the library if there are any issues</li>
        </ul>
        
        <div class="danger-box">
            <h3>Consequences of Non-Return:</h3>
            <p>Continued failure to return the book may result in:</p>
            <ul>
                <li>Accumulated late fees</li>
                <li>Suspension of library privileges</li>
                <li>Further administrative action</li>
            </ul>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ $dashboardUrl }}" class="button">View My Loans</a>
        </div>
        
        <p>If you have already returned the book or need assistance, please contact us immediately at <strong>{{ $contactEmail }}</strong></p>
        
        <p><strong>Library Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an urgent automated notice. Please contact us if you need help.</p>
    </div>
</body>
</html>

