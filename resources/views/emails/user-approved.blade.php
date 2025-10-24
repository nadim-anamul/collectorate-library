<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.account_approved_title') }}</title>
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
        <h1>{{ __('ui.welcome_to_library') }}</h1>
        <p>{{ __('ui.account_approved_subtitle') }}</p>
    </div>
    
    <div class="content">
        <h2>{{ __('ui.hello_user', ['name' => $userName]) }}</h2>
        
        <p>{{ __('ui.great_news') }}</p>
        
        <div class="info-box">
            <h3>{{ __('ui.account_details') }}</h3>
            <ul>
                <li><strong>{{ __('ui.email') }}:</strong> {{ $userEmail }}</li>
                <li><strong>{{ __('ui.role') }}:</strong> {{ $role }}</li>
                <li><strong>{{ __('ui.status') }}:</strong> {{ __('ui.active') }}</li>
            </ul>
        </div>
        
        <p>{{ __('ui.you_can_now') }}</p>
        <ul>
            <li>{{ __('ui.browse_collection') }}</li>
            <li>{{ __('ui.borrow_books_online') }}</li>
            <li>{{ __('ui.track_reading_history') }}</li>
            <li>{{ __('ui.access_dashboard') }}</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">{{ __('ui.login_to_account') }}</a>
        </div>
        
        <p>{{ __('ui.contact_library_staff') }}</p>
        
        <p>{{ __('ui.happy_reading') }}</p>
        
        <p><strong>{{ __('ui.library_team') }}</strong></p>
    </div>
    
    <div class="footer">
        <p>{{ __('ui.automated_email_no_reply') }}</p>
    </div>
</body>
</html>
