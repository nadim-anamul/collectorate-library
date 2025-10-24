<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.account_rejected_title') }}</title>
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
        <h1>{{ __('ui.application_update') }}</h1>
        <p>{{ __('ui.regarding_application') }}</p>
    </div>
    
    <div class="content">
        <h2>{{ __('ui.hello_user', ['name' => $userName]) }}</h2>
        
        <p>{{ __('ui.thank_you_interest') }}</p>
        
        <div class="warning-box">
            <h3>{{ __('ui.reason_for_rejection') }}</h3>
            <p>{{ $reason }}</p>
        </div>
        
        <p>{{ __('ui.decision_error') }}</p>
        
        <p><strong>{{ __('ui.what_you_can_do') }}</strong></p>
        <ul>
            <li>{{ __('ui.review_rejection_reason') }}</li>
            <li>{{ __('ui.contact_library_staff_clarification') }}</li>
            <li>{{ __('ui.reapply_address_concerns') }}</li>
            <li>{{ __('ui.visit_library_person') }}</li>
        </ul>
        
        <p>{{ __('ui.browse_public_catalog') }}</p>
        
        <p>{{ __('ui.contact_us_at') }} <strong>{{ $contactEmail }}</strong></p>
        
        <p>{{ __('ui.thank_you_understanding') }}</p>
        
        <p><strong>{{ __('ui.library_team') }}</strong></p>
    </div>
    
    <div class="footer">
        <p>{{ __('ui.automated_email_can_reply') }}</p>
    </div>
</body>
</html>