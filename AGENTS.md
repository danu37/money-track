# AGENTS.md

# MoneyTrack — Development Guide

## 1. Project Identity

**MoneyTrack** is a personal finance management web application.

The application uses:

- PHP Native
- MySQL
- PDO
- HTML
- Tailwind CSS
- JavaScript
- Composer
- `vlucas/phpdotenv`

Do not replace the stack with Laravel, React, Vue, or another framework unless explicitly requested.

---

# 2. Visual Reference — IMPORTANT

The application's visual design must follow the uploaded MoneyTrack reference screenshots.

There are two primary visual references:

1. **Authentication / Register screen**
2. **Main dashboard / Overview screen**

The screenshots are the primary design direction. Do not create a generic admin dashboard.

The visual language should be:

- Clean
- Minimal
- Modern
- Professional
- Finance/SaaS oriented
- Light gray page background
- White cards
- Dark charcoal sidebar
- Teal/turquoise primary accent
- Small rounded corners
- Soft shadows
- Thin borders
- Compact spacing
- Clear hierarchy
- Desktop-first but responsive
- No excessive gradients
- No excessive animations
- No overly colorful UI

The target result should visually feel like the supplied MoneyTrack screenshots.

---

# 3. Design System

## Primary Colors

Use these colors consistently.

```text
Primary teal:
#2FA89D

Primary teal hover:
#27958B

Dark sidebar:
#171717

Main background:
#F5F6F8

Card:
#FFFFFF

Main text:
#2B2B2B

Secondary text:
#8A8F98

Border:
#E8EAED

Success:
#2FA89D

Danger:
#E56B6F
```

Do not randomly introduce additional accent colors.

Charts may use the MoneyTrack teal as the primary visual color.

---

# 4. Typography

Use a clean modern sans-serif font.

Recommended:

```text
Inter
```

Fallback:

```text
system-ui, sans-serif
```

Typography should be compact and readable.

Use:

- Small labels
- Medium-weight headings
- Strong numbers for financial values
- Muted secondary text
- Avoid oversized headings inside the dashboard

---

# 5. Authentication UI

Authentication pages must follow the supplied registration screenshot.

The page should have:

```text
                  MoneyTrack

                    Nama
             [ John Doe              ]

                    Email
             [ johndoe@email.com     ]

                  Kata Sandi
             [ •••••••••             ]

              Lupa Kata Sandi?

             [      Daftar      ]

        ─────── atau masuk dengan ───────

          [ Google / Lanjutkan ... ]

             Sudah Memiliki Akun?
                 Masuk Di sini
```

Important visual details:

- Center the authentication form horizontally.
- Keep the form narrow.
- Use a very light gray page background.
- Logo/brand uses teal.
- Inputs are white with thin gray borders.
- Inputs have small rounded corners.
- Primary button is teal.
- Secondary Google button is light gray.
- Links use teal.
- Do not add a large illustration unless explicitly requested.
- Keep large empty space around the form.
- The form should look lightweight rather than like a large card.

Authentication pages:

```text
/login
/register
/forgot-password
/reset-password
```

Use Indonesian UI text for the current application unless the existing page explicitly uses English.

---

# 6. Dashboard UI

The main dashboard must follow the first supplied screenshot.

Overall structure:

```text
┌────────────────┬──────────────────────────────────────────┐
│                │ Hello Danu          Search      Bell     │
│   MoneyTrack   ├──────────────────────────────────────────┤
│                │                                          │
│   Overview     │ Total Saldo     Target     Tagihan       │
│   Saldo        │                                          │
│   Transaksi    │ Account Card     Target Card             │
│   Tagihan      │                                          │
│   Pengeluaran  │ Riwayat Transaksi    Statistik           │
│   Target       │                                          │
│   Pengaturan   │                                          │
│                │                                          │
│   Logout       │                                          │
│                │                                          │
│   User         │                                          │
└────────────────┴──────────────────────────────────────────┘
```

The dashboard has a **dark vertical sidebar** on the left.

The content area uses a light gray background.

---

# 7. Sidebar

Sidebar requirements:

- Width approximately 230–250px on desktop.
- Dark charcoal background.
- MoneyTrack logo at the top.
- Navigation icons on the left.
- Active menu has a teal background.
- Text is white/light gray.
- Logout button near the bottom.
- User profile section at the bottom.

Navigation:

```text
Overview
Saldo
Transaksi
Tagihan
Pengeluaran
Target Keuangan
Pengaturan
```

Bottom section:

```text
Logout

Danu Anggo
View profile
```

The active menu should visually resemble the screenshot:

```text
[ icon  Overview ]
```

with a teal rounded background.

Do not make the sidebar pure black.

---

# 8. Dashboard Header

At the top of the content area:

```text
Hello Danu                         🔔     Search here
```

Requirements:

- Small greeting.
- Current date can appear beside the greeting.
- Search field on the right.
- Notification icon.
- White/very light header area.
- Avoid an oversized navbar.

---

# 9. Dashboard Cards

The dashboard should use compact cards.

Primary cards:

```text
Total Saldo
Target
Tagihan Mendatang
```

## Total Saldo

Display:

```text
Total Saldo

Rp 100.000.000

All Accounts
```

Below it, show the selected account card.

Example:

```text
Account Type
Credit Card

**** 2598
```

Include previous/next controls when multiple accounts exist.

---

# 10. Target Card

Display:

```text
Target

Rp 200.000.000

May, 2023
```

Include:

- Circular progress indicator
- Target Keuangan
- Target bulan ini
- Current progress

The target card should visually resemble the supplied reference.

---

# 11. Upcoming Bills

Display a compact list/card:

```text
Tagihan Mendatang

15 Mar
Figma - Bulanan       900k

16 Jun
Adobe - Tahunan       500k
```

Each bill item should include:

- Date
- Service name
- Billing type
- Amount
- Small icon/avatar

---

# 12. Transaction History

The dashboard contains a transaction history section.

Tabs:

```text
All
Revenue
Expenses
```

Example items:

```text
GTR 5              200K
Gadget & Gear

Polo Shirt         100K
T-shirts

Biryani            300K
Halal Biryani

Taxi Fare          200K
Uber

Keyboard           500K
Gadget & Gear
```

Each row should contain:

- Small category icon
- Transaction name
- Category/vendor
- Amount
- Date

Keep the list compact.

---

# 13. Statistics

The dashboard includes a statistics card.

Primary chart:

```text
Pengeluaran Bulanan
```

Use a smooth line chart.

Requirements:

- Teal line.
- Light grid.
- Month labels.
- Small tooltip.
- Minimal visual noise.
- White card.
- Rounded corners.
- Soft shadow.

The chart must use real data from PHP/MySQL when connected.

Do not fabricate financial data in production.

---

# 14. Responsive Behavior

Desktop should closely match the supplied screenshot.

For smaller screens:

```text
Desktop
Sidebar + content

Tablet
Collapsible sidebar

Mobile
Top navigation / drawer
Single-column cards
```

Do not simply shrink the desktop layout.

Cards should stack naturally.

Charts should resize to available width.

Tables/lists should remain usable on mobile.

---

# 15. Component Style

Use consistent cards:

```html
<div class="rounded-lg bg-white border border-gray-200 shadow-sm">
    ...
</div>
```

Prefer moderate rounding such as:

```text
rounded-lg
rounded-xl
```

Avoid excessive:

```text
rounded-full
```

except for avatars, pills, progress indicators, or circular controls.

Shadows should be subtle.

Avoid:

```text
shadow-2xl
```

for normal cards.

Prefer:

```text
shadow-sm
```

or a similarly subtle shadow.

---

# 16. Database

Database:

```text
moneytrack
```

Main tables:

```text
users
accounts
categories
transactions
```

Relationships:

```text
users
  ├── accounts
  ├── categories
  └── transactions
          ├── accounts
          └── categories
```

## users

```text
id
name
email
password
created_at
updated_at
```

## accounts

```text
id
user_id
name
type
initial_balance
created_at
updated_at
```

Account types:

```text
cash
bank
ewallet
other
```

## categories

```text
id
user_id
name
type
icon
created_at
updated_at
```

Category types:

```text
income
expense
```

## transactions

```text
id
user_id
account_id
category_id
type
amount
description
transaction_date
created_at
updated_at
```

Transaction types:

```text
income
expense
```

Money values must use:

```sql
DECIMAL(15,2)
```

Never use floating-point database types for money.

---

# 17. Balance Calculation

Conceptually:

```text
Current Balance
=
Initial Balance
+ Income
- Expense
```

Do not blindly trust a balance sent from JavaScript.

Calculate financial totals using database data.

---

# 18. Authentication

Use PHP sessions.

Register:

```text
Form
 ↓
Validation
 ↓
password_hash()
 ↓
users
```

Login:

```text
Form
 ↓
Find user
 ↓
password_verify()
 ↓
Session
 ↓
Dashboard
```

Never store plaintext passwords in production.

Protected pages must verify authentication.

---

# 19. Authorization

Every personal finance record belongs to a user.

Always scope queries by authenticated user.

Correct:

```sql
SELECT *
FROM transactions
WHERE id = :id
AND user_id = :user_id;
```

Incorrect:

```sql
SELECT *
FROM transactions
WHERE id = :id;
```

Apply this rule to:

- accounts
- categories
- transactions
- profile data

A user must never access another user's records by changing an ID.

---

# 20. Security

Always use PDO prepared statements.

Good:

```php
$stmt = $pdo->prepare(
    'SELECT * FROM users WHERE email = :email'
);

$stmt->execute([
    'email' => $email
]);
```

Never concatenate user input into SQL.

Protect against:

- SQL injection
- XSS
- CSRF
- unauthorized access
- session fixation

Escape user-controlled output:

```php
<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>
```

Use CSRF tokens for state-changing forms.

---

# 21. Money Formatting

Keep money numeric in the database.

Format only for display.

Use Indonesian Rupiah:

```text
Rp 100.000.000
Rp 200.000
Rp 900.000
```

Example helper:

```php
function formatRupiah(float|int $amount): string
{
    return 'Rp ' . number_format(
        $amount,
        0,
        ',',
        '.'
    );
}
```

---

# 22. JavaScript

Use JavaScript for:

- Sidebar interactions
- Search
- Tabs
- Modal
- Form interactions
- AJAX/fetch
- Charts
- Filters
- Dynamic dashboard data

Files:

```text
app.js
dashboard.js
transaction.js
account.js
category.js
chart.js
```

Do not create one huge JavaScript file.

---

# 23. Charts

Use a chart library only if needed.

Dashboard charts should represent actual backend data.

Possible charts:

```text
Monthly Expenses
Income vs Expenses
Expenses by Category
Account Distribution
```

Primary chart style:

```text
Teal line
Light grid
Minimal labels
White card
```

Never fabricate financial data for production.

---

# 24. Routes

Keep routes readable:

```text
GET     /login
POST    /login

GET     /register
POST    /register

POST    /logout

GET     /dashboard

GET     /transactions
GET     /transactions/create
POST    /transactions
GET     /transactions/edit
POST    /transactions/update
POST    /transactions/delete

GET     /accounts
POST    /accounts
POST    /accounts/update
POST    /accounts/delete

GET     /categories
POST    /categories
POST    /categories/update
POST    /categories/delete

GET     /profile
POST    /profile
```

Do not put large business logic in routes.

---

# 25. Project Structure

Use:

```text
moneytrack/
├── AGENTS.md
├── app/
│   ├── bootstrap.php
│   ├── config/
│   │   ├── app.php
│   │   ├── database.php
│   │   └── connection.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── TransactionController.php
│   │   ├── AccountController.php
│   │   ├── CategoryController.php
│   │   └── ProfileController.php
│   ├── models/
│   │   ├── User.php
│   │   ├── Account.php
│   │   ├── Category.php
│   │   └── Transaction.php
│   └── helpers/
│       ├── auth.php
│       ├── format.php
│       └── validation.php
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/
│       │   ├── input.css
│       │   └── output.css
│       ├── js/
│       │   ├── app.js
│       │   ├── dashboard.js
│       │   ├── transaction.js
│       │   ├── account.js
│       │   ├── category.js
│       │   └── chart.js
│       └── images/
├── views/
│   ├── layouts/
│   │   ├── app.php
│   │   ├── auth.php
│   │   ├── navbar.php
│   │   └── sidebar.php
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── forgot-password.php
│   │   └── reset-password.php
│   ├── dashboard/
│   │   └── index.php
│   ├── transactions/
│   ├── accounts/
│   ├── categories/
│   └── profile/
├── routes/
│   └── web.php
├── database/
│   └── database.sql
├── storage/
│   ├── logs/
│   └── cache/
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── package.json
├── tailwind.config.js
└── README.md
```

---

# 26. Development Order

Build incrementally.

## Phase 1 — Foundation

```text
1. Project structure
2. Composer
3. .env
4. PDO connection
5. Routing
6. Base layout
7. Tailwind
```

## Phase 2 — Authentication

```text
1. Register
2. Login
3. Logout
4. Session protection
5. Password hashing
```

## Phase 3 — Dashboard UI

Recreate the supplied dashboard:

```text
1. Dark sidebar
2. Header
3. Total Saldo
4. Account card
5. Target card
6. Upcoming bills
7. Transaction history
8. Statistics chart
9. User profile
10. Logout
```

## Phase 4 — Accounts

```text
1. List
2. Create
3. Edit
4. Delete
5. Balance calculation
```

## Phase 5 — Categories

```text
1. List
2. Create
3. Edit
4. Delete
5. Income/expense type
```

## Phase 6 — Transactions

```text
1. List
2. Create
3. Edit
4. Delete
5. Search
6. Filter
7. Pagination
```

## Phase 7 — Dashboard Data

Connect:

```text
Database
 ↓
Controller
 ↓
Dashboard
```

Replace all placeholder financial numbers with real data.

## Phase 8 — Profile

```text
1. View profile
2. Edit profile
3. Change password
```

## Phase 9 — Polish

```text
1. Responsive layout
2. Empty states
3. Loading states
4. Error states
5. Form validation
6. Security review
7. Performance cleanup
```

---

# 27. Coding Rules

When modifying the project:

1. Inspect existing code before creating files.
2. Reuse existing components.
3. Do not rewrite unrelated files.
4. Do not introduce another framework.
5. Keep functions small.
6. Use meaningful names.
7. Prefer readable code.
8. Use prepared SQL statements.
9. Validate all user input.
10. Check ownership of every personal record.
11. Test the affected feature after changes.
12. Match the supplied screenshots before inventing new UI patterns.

When implementing a UI feature, first ask:

```text
Does this look like it belongs in the supplied MoneyTrack screenshots?
```

If not, simplify it.

---

# 28. Naming Conventions

PHP classes/files:

```text
PascalCase
```

Examples:

```text
AuthController.php
TransactionController.php
Transaction.php
```

Methods:

```text
camelCase
```

Examples:

```php
findByEmail()
createTransaction()
calculateBalance()
```

Database:

```text
snake_case
```

Examples:

```text
user_id
account_id
transaction_date
initial_balance
```

JavaScript:

```text
camelCase
```

Examples:

```javascript
loadDashboard()
formatCurrency()
deleteTransaction()
```

---

# 29. Git

Do not commit:

```text
.env
/vendor
/node_modules
logs
temporary files
```

Commit:

```text
.env.example
composer.json
composer.lock
package.json
package-lock.json
source code
database schema
README.md
AGENTS.md
```

Commit examples:

```text
feat: add authentication
feat: build moneytrack dashboard
feat: add transaction management
feat: add account management
feat: add financial statistics
fix: prevent unauthorized transaction access
refactor: separate database connection
style: match moneytrack dashboard design
```

---

# 30. Restrictions

Do not:

- Add Laravel unless explicitly requested.
- Add React unless explicitly requested.
- Add Vue unless explicitly requested.
- Replace PHP Native.
- Store plaintext passwords in production.
- Build SQL with string concatenation.
- Trust IDs from the frontend.
- Expose `.env`.
- Hardcode database credentials.
- Put database queries inside views.
- Put sensitive business logic in JavaScript.
- Delete working functionality without a reason.
- Change database structure without checking dependencies.
- Replace the supplied visual style with a generic dashboard template.

---

# 31. Definition of Done

A feature is complete when:

- It works with the existing architecture.
- It follows the database relationships.
- Authentication and authorization are respected.
- User input is validated.
- SQL uses prepared statements.
- Financial calculations come from database data.
- The UI matches the MoneyTrack reference.
- Desktop layout is visually close to the screenshots.
- Mobile layout remains usable.
- Errors are handled properly.
- No credentials are hardcoded.
- Existing features are not unnecessarily broken.

When uncertain, choose the simplest implementation that preserves the supplied MoneyTrack design and the existing architecture.
