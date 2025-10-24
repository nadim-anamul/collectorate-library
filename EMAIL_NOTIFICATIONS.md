# Email Notification System

This document describes the comprehensive email notification system implemented for the Library Management System.

## Overview

The system sends automated email notifications to users for various events related to their library account and book loans. All emails are professionally designed with HTML templates.

## SMTP Configuration

The system uses Gmail SMTP for sending emails. Configuration is stored in the `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Note:** For Gmail, you need to use an [App Password](https://support.google.com/accounts/answer/185833) instead of your regular password.

## Email Types

### 1. User Account Notifications

#### Account Approved
- **Trigger:** When an admin approves a pending user account
- **Recipient:** The newly approved user
- **Template:** `resources/views/emails/user-approved.blade.php`
- **Mail Class:** `App\Mail\UserApproved`
- **Content:** Welcome message, account details, role assignment, login link

#### Account Rejected
- **Trigger:** When an admin rejects a user account application
- **Recipient:** The rejected user
- **Template:** `resources/views/emails/user-rejected.blade.php`
- **Mail Class:** `App\Mail\UserRejected`
- **Content:** Rejection reason, next steps, contact information

### 2. Book Loan Notifications

#### Loan Issued
- **Trigger:** When an admin directly issues a book to a user
- **Recipient:** The borrower
- **Template:** `resources/views/emails/loan-issued.blade.php`
- **Mail Class:** `App\Mail\LoanIssued`
- **Content:** Book details, issue date, due date, collection instructions

#### Loan Request Approved
- **Trigger:** When an admin approves a pending loan request
- **Recipient:** The borrower
- **Template:** `resources/views/emails/loan-approved.blade.php`
- **Mail Class:** `App\Mail\LoanApproved`
- **Content:** Approval confirmation, book details, collection instructions

#### Loan Request Declined
- **Trigger:** When an admin declines a pending loan request
- **Recipient:** The borrower
- **Template:** `resources/views/emails/loan-declined.blade.php`
- **Mail Class:** `App\Mail\LoanDeclined`
- **Content:** Decline reason, alternative options, contact information

#### Loan Due Soon (Reminder)
- **Trigger:** Automated daily check (1, 2, or 3 days before due date)
- **Recipient:** The borrower
- **Template:** `resources/views/emails/loan-due-soon.blade.php`
- **Mail Class:** `App\Mail\LoanDueSoon`
- **Content:** Due date reminder, return instructions, days remaining

#### Loan Overdue (Urgent)
- **Trigger:** Automated daily check (after due date has passed)
- **Recipient:** The borrower
- **Template:** `resources/views/emails/loan-overdue.blade.php`
- **Mail Class:** `App\Mail\LoanOverdue`
- **Content:** Overdue notice, days overdue, consequences, urgent return request

### 3. Administrative Notifications

Admins and Librarians receive email notifications for:
- New loan requests
- Book returns
- Return requests from users
- Cancelled loan requests

These use the `LoanEventNotification` class which sends to both database and email channels.

## Testing Email Functionality

### Send Test Email

Use the custom artisan command to send a test email:

```bash
php artisan mail:test your-email@example.com
```

This will send a test email to verify that your SMTP configuration is working correctly.

### Check Due/Overdue Loans Manually

Run the loan checker command manually:

```bash
php artisan loans:check-due
```

This will check for loans that are:
- Due in 1, 2, or 3 days (sends due soon reminders)
- Already overdue (sends overdue notices)

## Automated Scheduling

The system automatically runs the loan checker command daily at 8:00 AM via Laravel's task scheduler.

### Configure Cron Job

To enable automated emails, add this to your server's crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

This cron entry will run every minute and Laravel will execute scheduled tasks at their designated times.

### Scheduled Tasks

Current schedule (defined in `app/Console/Kernel.php`):

| Command | Schedule | Description |
|---------|----------|-------------|
| `loans:check-due` | Daily at 08:00 | Send due soon and overdue email reminders |
| `loans:mark-overdue` | Daily at 01:00 | Mark loans as overdue in database |
| `notifications:backfill-loan-urls` | Daily at 02:00 | Backfill notification URLs |

## Email Template Customization

All email templates are located in `resources/views/emails/` and use responsive HTML/CSS design.

### Template Features:
- ✓ Responsive design (mobile-friendly)
- ✓ Professional gradient headers
- ✓ Color-coded by urgency/type
- ✓ Clear call-to-action buttons
- ✓ Bilingual support (English and Bengali)
- ✓ Branded with library information

### Customizing Templates

To customize an email template:

1. Navigate to `resources/views/emails/`
2. Edit the `.blade.php` file for the email type
3. Save changes (no cache clearing required for views in development)

## Troubleshooting

### Emails Not Sending

1. **Check SMTP Configuration**
   ```bash
   # Verify .env settings
   cat .env | grep MAIL
   
   # Clear config cache
   php artisan config:clear
   
   # Send test email
   php artisan mail:test your-email@example.com
   ```

2. **Check Gmail App Password**
   - Make sure you're using an App Password, not your regular Gmail password
   - Verify 2-Factor Authentication is enabled on your Gmail account
   - Generate a new App Password if needed

3. **Check Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Verify Queue Configuration**
   - If using queues, make sure queue worker is running:
   ```bash
   php artisan queue:work
   ```

### Common Issues

#### "Mailer [X] is not defined"
- Check that `MAIL_MAILER=smtp` (not the actual SMTP host)
- Run `php artisan config:clear`

#### "Authentication failed"
- Verify Gmail App Password is correct
- Ensure 2FA is enabled on Gmail account
- Check that `MAIL_ENCRYPTION=tls` and `MAIL_PORT=587`

#### "Connection timeout"
- Check firewall settings
- Verify port 587 is not blocked
- Try port 465 with `MAIL_ENCRYPTION=ssl`

## Development vs Production

### Development
In development, you might want to use Laravel's `log` mailer to preview emails without actually sending them:

```env
MAIL_MAILER=log
```

Emails will be written to `storage/logs/laravel.log`

### Production
Use proper SMTP settings as documented above. Consider using:
- Queue system for faster response times
- Email service providers (SendGrid, Mailgun, Amazon SES) for better deliverability
- Rate limiting to prevent spam accusations

## File Structure

```
app/
├── Console/Commands/
│   ├── SendTestEmail.php          # Test email command
│   └── CheckLoansDue.php          # Automated due/overdue checker
├── Mail/
│   ├── TestEmail.php              # Test email class
│   ├── UserApproved.php           # User approval email
│   ├── UserRejected.php           # User rejection email
│   ├── LoanIssued.php             # Loan issued email
│   ├── LoanApproved.php           # Loan approved email
│   ├── LoanDeclined.php           # Loan declined email
│   ├── LoanDueSoon.php            # Due soon reminder
│   └── LoanOverdue.php            # Overdue notice
└── Notifications/
    └── LoanEventNotification.php  # Admin notifications

resources/views/emails/
├── test-email.blade.php           # Test email template
├── user-approved.blade.php        # User approval template
├── user-rejected.blade.php        # User rejection template
├── loan-issued.blade.php          # Loan issued template
├── loan-approved.blade.php        # Loan approved template
├── loan-declined.blade.php        # Loan declined template
├── loan-due-soon.blade.php        # Due soon template
└── loan-overdue.blade.php         # Overdue template
```

## Security Considerations

1. **Environment Variables:** Never commit `.env` file with real credentials
2. **App Passwords:** Use Gmail App Passwords, not account passwords
3. **Rate Limiting:** Monitor email sending rates to avoid spam flags
4. **User Privacy:** Ensure email content doesn't expose sensitive information
5. **Error Handling:** All email sends are wrapped in try-catch blocks

## Best Practices

1. **Test Before Deploy:** Always test emails in development/staging
2. **Monitor Logs:** Regularly check logs for failed email attempts
3. **Queue Jobs:** Use queue system for better performance in production
4. **User Preferences:** Consider adding email notification preferences in future
5. **Unsubscribe:** Consider adding unsubscribe options for automated reminders

## Support

For issues or questions about the email system:
- Check the troubleshooting section above
- Review Laravel logs: `storage/logs/laravel.log`
- Test SMTP connection manually
- Contact system administrator

---

**Last Updated:** October 24, 2025
**System Version:** 1.0

