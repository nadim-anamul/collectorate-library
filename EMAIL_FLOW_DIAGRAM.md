# Email Notification Flow

## User Account Lifecycle

```
┌─────────────────────┐
│  User Registers     │
└──────────┬──────────┘
           │
           ▼
    ┌──────────────┐
    │ Status:      │
    │ "pending"    │
    └──────┬───────┘
           │
      Admin Reviews
           │
     ┌─────┴─────┐
     │           │
     ▼           ▼
┌─────────┐  ┌──────────┐
│Approved │  │ Rejected │
└────┬────┘  └─────┬────┘
     │             │
     ▼             ▼
📧 Welcome    📧 Rejection
   Email         Email
```

## Book Loan Lifecycle

```
┌──────────────────────┐
│ User Requests Book   │
└──────────┬───────────┘
           │
           ▼
    ┌──────────────┐
    │ Status:      │
    │ "pending"    │
    └──────┬───────┘
           │
      Admin Reviews
           │
     ┌─────┴─────┐
     │           │
     ▼           ▼
┌─────────┐  ┌──────────┐
│Approved │  │ Declined │
└────┬────┘  └─────┬────┘
     │             │
     ▼             ▼
📧 Approval   📧 Decline
   Email         Email
     │
     ▼
┌─────────────┐
│ Status:     │
│ "issued"    │
└──────┬──────┘
       │
Daily Automated Checks
       │
   ┌───┴───┬────────┬────────┐
   │       │        │        │
   ▼       ▼        ▼        ▼
┌──────┐┌──────┐┌──────┐┌─────────┐
│3 days││2 days││1 day ││ Overdue │
│before││before││before││         │
└──┬───┘└──┬───┘└──┬───┘└────┬────┘
   │       │        │         │
   ▼       ▼        ▼         ▼
📧 Due  📧 Due  📧 Due  📧 URGENT
 Reminder Reminder Reminder Overdue
```

## Admin Loan Request Flow

```
┌──────────────────────┐
│ Admin Issues Book    │
│   (Direct Issue)     │
└──────────┬───────────┘
           │
           ▼
    ┌──────────────┐
    │ Status:      │
    │ "issued"     │
    └──────┬───────┘
           │
           ▼
    📧 Loan Issued
       Email
```

## Email Notification Matrix

| Event | Trigger | Recipient | Email Type | Urgency |
|-------|---------|-----------|------------|---------|
| Account Approved | Admin approval | User | Welcome | Normal |
| Account Rejected | Admin rejection | User | Rejection | Normal |
| Loan Issued | Admin direct issue | User | Loan Issued | Normal |
| Request Approved | Admin approval | User | Approval | Normal |
| Request Declined | Admin decline | User | Decline | Normal |
| Due in 3 days | Automated (8 AM) | User | Reminder | Low |
| Due in 2 days | Automated (8 AM) | User | Reminder | Medium |
| Due in 1 day | Automated (8 AM) | User | Reminder | High |
| Overdue | Automated (8 AM) | User | Urgent | Critical |
| New Request | User action | Admin/Librarian | Notification | Normal |
| Book Returned | Admin action | Admin/Librarian | Notification | Normal |

## Email Color Coding

```
🟣 Purple/Blue Gradient
   └─ Standard Notifications
      ├─ Loan Issued
      ├─ Account Approved
      └─ Test Email

🟢 Green Gradient
   └─ Approval Confirmations
      └─ Loan Request Approved

🟡 Yellow/Orange Gradient
   └─ Warnings/Reminders
      └─ Loan Due Soon

🔴 Red Gradient
   └─ Urgent/Critical
      ├─ Loan Overdue
      ├─ Account Rejected
      └─ Loan Declined
```

## Automated Schedule

```
Daily Schedule:
─────────────────────────────────────────────

01:00 AM  ⏰  Mark Overdue Loans
              └─ Updates database status

02:00 AM  ⏰  Backfill Notification URLs
              └─ Maintenance task

08:00 AM  ⏰  Check Due/Overdue Loans
              └─ Send Email Reminders
                 ├─ Due in 3 days
                 ├─ Due in 2 days
                 ├─ Due in 1 day
                 └─ Overdue books
```

## Email Template Structure

All emails follow this structure:

```
┌─────────────────────────────────┐
│       HEADER (Gradient)         │
│  ┌─────────────────────────┐   │
│  │   Icon + Title          │   │
│  │   Subtitle              │   │
│  └─────────────────────────┘   │
└─────────────────────────────────┘
┌─────────────────────────────────┐
│         CONTENT AREA            │
│  ┌─────────────────────────┐   │
│  │ Greeting                │   │
│  │                         │   │
│  │ Main Message            │   │
│  │                         │   │
│  │ ┌─────────────────────┐ │   │
│  │ │  Info Box           │ │   │
│  │ │  (Details)          │ │   │
│  │ └─────────────────────┘ │   │
│  │                         │   │
│  │ Action Items (List)     │   │
│  │                         │   │
│  │ ┌─────────────────────┐ │   │
│  │ │   Call-to-Action    │ │   │
│  │ │      [Button]       │ │   │
│  │ └─────────────────────┘ │   │
│  │                         │   │
│  │ Closing Message         │   │
│  └─────────────────────────┘   │
└─────────────────────────────────┘
┌─────────────────────────────────┐
│           FOOTER                │
│  Automated Email Notice         │
│  Copyright Info                 │
└─────────────────────────────────┘
```

## Testing Workflow

```
1. Send Test Email
   ├─ Command: php artisan mail:test email@example.com
   └─ Result: Verifies SMTP configuration

2. Approve Test User
   ├─ Action: Admin approves pending user
   └─ Result: User receives welcome email

3. Issue Test Loan
   ├─ Action: Admin issues book to user
   └─ Result: User receives loan issued email

4. Check Due Loans
   ├─ Command: php artisan loans:check-due
   └─ Result: Sends reminders for due/overdue books

5. Monitor Logs
   ├─ Location: storage/logs/laravel.log
   └─ Command: tail -f storage/logs/laravel.log
```

## Quick Command Reference

```bash
# Send test email
php artisan mail:test nadim.csm@gmail.com

# Check and send due/overdue reminders
php artisan loans:check-due

# View scheduled tasks
php artisan schedule:list

# Run scheduler once (for testing)
php artisan schedule:run

# Clear configuration cache
php artisan config:clear

# View logs in real-time
tail -f storage/logs/laravel.log | grep -i mail
```

## Integration Points

```
Controllers:
├─ UserManagementController
│  ├─ approve() → UserApproved email
│  └─ reject() → UserRejected email
│
└─ Admin/LoanController
   ├─ store() → LoanIssued email
   ├─ approve() → LoanApproved email
   └─ decline() → LoanDeclined email

Commands:
└─ CheckLoansDue
   ├─ checkDueSoon() → LoanDueSoon emails
   └─ checkOverdue() → LoanOverdue emails

Notifications:
└─ LoanEventNotification
   ├─ Database channel (in-app)
   └─ Mail channel (email to admins)
```

## Success Indicators

✅ Test email delivered successfully
✅ Users receive welcome emails on approval
✅ Users receive loan notifications
✅ Automated reminders sent daily at 8 AM
✅ Admins receive notification emails
✅ All emails are mobile-responsive
✅ Professional HTML design
✅ Bilingual support (EN/BN)

---

*System Status: ✅ FULLY OPERATIONAL*
*Last Updated: October 24, 2025*

