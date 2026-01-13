# Game Development Website - Project Explanation

## What Is This Project?

This is a **Game Development Company Website** where users can:
- View game development services
- See trending games
- Register for an account
- Log in to their account
- Add new games (if logged in)
- Contact the company

Think of it like a portfolio website for a game development studio.

---

## Two Versions of the Website

### 1. **Weak Version** (Vulnerable)
This is the original version with **security problems**. It works, but it's like a house with unlocked doors - anyone can break in.

### 2. **Protected Version** (Secure)
This is the **fixed version** with all security problems solved. It's like the same house, but now with locks, alarms, and security cameras.

---

## What Was Wrong with the Weak Version?

### Problem 1: SQL Injection (Hacking the Database)
**What it means:** Hackers could trick the website into showing them all user passwords and data.

**How it worked:**
- When you log in, the website asks the database: "Is there a user with this login and password?"
- In the weak version, it built the question like this: `"Is there a user with login='john' AND password='123'?"`
- A hacker could type: `john' OR '1'='1` as the login
- The database would see: `"Is there a user with login='john' OR '1'='1'?"`
- Since '1'='1' is always true, the hacker gets in!

**How we fixed it:**
- Now the website uses "prepared statements" - it asks the database: "Is there a user with login=? AND password=?"
- Then it fills in the `?` safely, so hackers can't inject malicious code.

---

### Problem 2: Weak Password Storage
**What it means:** Passwords were stored using MD5, which is like writing your password on a sticky note.

**How it worked:**
- When you register, your password "mypassword123" becomes "482c811da5d5b4bc6d497ffa98491e38" (MD5 hash)
- The problem: MD5 is broken. There are websites with billions of MD5 hashes already cracked.
- If someone steals the database, they can look up your hash and know your password in seconds.

**How we fixed it:**
- Now we use `password_hash()` which uses bcrypt
- Your password "mypassword123" becomes something like: `$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy`
- This is much harder to crack - it would take years even with powerful computers
- Each password gets a unique "salt" (random extra data) so even identical passwords look different

---

### Problem 3: Cross-Site Scripting (XSS) - Code Injection
**What it means:** Hackers could inject malicious code that runs in other users' browsers.

**How it worked:**
- If a hacker could add a game with the name: `<script>alert('Hacked!')</script>`
- When someone views that game, the script runs in their browser
- This could steal cookies, redirect users, or do other bad things

**How we fixed it:**
- Now all user input is "escaped" before showing it
- `<script>` becomes `&lt;script&gt;` (HTML code that displays as text, not runs as code)
- The browser sees it as text, not as code to execute

---

### Problem 4: Cookie-Only Authentication
**What it means:** The website only checked if you had a cookie to see if you were logged in.

**How it worked:**
- When you log in, the website sets a cookie: `login=john`
- To check if you're logged in, it just looks for that cookie
- Problem: Cookies can be easily faked or stolen
- If someone knows your username, they could create a fake cookie and log in as you

**How we fixed it:**
- Now we use "sessions" - server-side storage
- When you log in, the server creates a session ID (like a ticket)
- Your browser gets the ticket, and the server remembers: "Ticket #12345 belongs to John"
- Every time you visit a page, the server checks: "Do you have a valid ticket?"
- If you're inactive for 30 minutes, your ticket expires
- Much more secure!

---

### Problem 5: Unsafe File Uploads
**What it means:** Users could upload dangerous files or files with malicious names.

**How it worked:**
- In the weak version, if you upload a file named `../../../etc/passwd`, it could access system files
- Or you could upload a PHP script that executes on the server
- No size limits - someone could upload a 10GB file and crash the server

**How we fixed it:**
- [PASS] File size limit: Maximum 5MB
- [PASS] File type checking: Only images allowed (jpg, png, gif, webp, avif)
- [PASS] MIME type validation: Checks the actual file type, not just the extension
- [PASS] Unique filenames: `game1.jpg` becomes `69658f87313c4_1768263559.jpg` (prevents overwrites)
- [PASS] Path sanitization: Removes dangerous characters from filenames
- [PASS] Secure storage: Files saved in a safe location

---

### Problem 6: No Input Validation
**What it means:** The website accepted any input, even if it was wrong or malicious.

**How it worked:**
- You could register with email: `notanemail` (not valid)
- You could register with login: `a` (too short)
- No checking if data makes sense

**How we fixed it:**
- [PASS] Email validation: Must be a real email format
- [PASS] Length checks: Login must be at least 2 characters, password at least 6
- [PASS] Input sanitization: Removes dangerous characters
- [PASS] Type checking: Numbers must be numbers, emails must be emails

---

### Problem 7: Error Messages Reveal Secrets
**What it means:** When something went wrong, the website showed technical details that helped hackers.

**How it worked:**
- If there was a database error, it might show: `Error connecting to database at /var/www/db.php line 45`
- Hackers could learn about the server structure
- Or see SQL queries that reveal table names

**How we fixed it:**
- [PASS] Errors are logged to server files (not shown to users)
- [PASS] Users see friendly messages: "Something went wrong. Please try again."
- [PASS] No technical details exposed

---

## What We Added to the Protected Version

### 1. Session Management System
- **File:** `lib/session_check.php`
- **What it does:** 
  - Checks if you're logged in
  - Tracks when you were last active
  - Automatically logs you out after 30 minutes of inactivity
  - Provides helper functions: `isLoggedIn()`, `requireLogin()`, `getUserLogin()`

### 2. Secure Configuration
- **File:** `lib/config.php`
- **What it does:**
  - Automatically detects the correct base path (works on local and deployed servers)
  - Uses environment variables for sensitive data (database passwords, etc.)
  - Provides `buildUrl()` function for safe URL generation

### 3. Directory Protection
- **File:** `lib/.htaccess`
- **What it does:**
  - Blocks direct access to PHP files in the `lib/` folder
  - You can't visit `lib/auth.php` directly in your browser
  - Form submissions (POST) still work
  - PHP includes still work (server-side)

### 4. Security Headers
- **File:** `.htaccess` (root)
- **What it does:**
  - Tells browsers to use security features
  - Prevents clickjacking (embedding your site in malicious frames)
  - Prevents MIME type sniffing (browsers guessing file types)

---

## How the Website Works (Step by Step)

### Registration Process:
1. User fills out registration form (`reg.php`)
2. Form submits to `lib/reg.php` (processing script)
3. Script validates input (email format, length, etc.)
4. Password is hashed using `password_hash()`
5. User data is saved to database using prepared statement
6. User is redirected back to registration page with success message

### Login Process:
1. User fills out login form (`auth.php`)
2. Form submits to `lib/auth.php` (processing script)
3. Script finds user in database using prepared statement
4. Password is verified using `password_verify()`
5. If correct, session is created with user ID and login
6. User is redirected to profile page (`user.php`)

### Adding a Game (Protected Page):
1. User must be logged in (checked by `session_check.php`)
2. User fills out form on `user.php`
3. Form submits to `lib/add_game.php`
4. Script validates:
   - File is an image
   - File size is under 5MB
   - File type is allowed
5. File is saved with unique name
6. Game data is saved to database
7. User is redirected to trending page

### Viewing Protected Pages:
1. User tries to visit `user.php`
2. Page includes `lib/session_check.php`
3. Script checks: Is user logged in? Is session expired?
4. If not logged in → redirect to login page
5. If logged in → show the page

---

## File Structure Explained

```
protected_version/
├── index.php          → Home page (shows games, services)
├── auth.php           → Login page (form)
├── reg.php            → Registration page (form)
├── user.php           → User profile (protected - need login)
├── contacts.php       → Contact form page
├── trending.php       → Shows all trending games
├── about.php          → About us page
│
├── lib/               → Processing scripts (not directly accessible)
│   ├── auth.php       → Handles login form submission
│   ├── reg.php        → Handles registration form submission
│   ├── add_game.php   → Handles adding new games
│   ├── contact.php    → Handles contact form submission
│   ├── logout.php     → Logs user out
│   ├── session_check.php → Session management functions
│   ├── config.php     → Configuration (BASE_PATH, etc.)
│   ├── db.php         → Database connection
│   └── .htaccess      → Protects lib/ folder
│
├── blocks/            → Reusable page parts
│   ├── header.php     → Navigation menu (shows login/logout)
│   └── footer.php     → Footer content
│
├── imgs/              → Images folder
│   ├── blocks/        → Game images (user uploads go here)
│   ├── features/       → Feature icons
│   └── projects/      → Project screenshots
│
├── style.css          → Website styling
├── java.js            → JavaScript functionality
├── database.sql       → Database structure (tables, etc.)
└── .htaccess          → Security headers and settings
```

---

## Key Security Features Summary

| Feature | Weak Version | Protected Version |
|---------|-------------|------------------|
| **SQL Queries** | String concatenation | Prepared statements |
| **Password Storage** | MD5 (broken) | bcrypt (secure) |
| **Output** | Direct echo | Escaped with htmlspecialchars() |
| **Authentication** | Cookies only | Sessions + cookies |
| **Session Timeout** | None | 30 minutes |
| **File Upload** | No validation | Size, type, MIME checks |
| **Input Validation** | None | Full validation |
| **Error Messages** | Technical details | User-friendly only |
| **Directory Protection** | None | .htaccess blocks |

---

## Real-World Example: SQL Injection Attack

### Scenario: How a Hacker Could Break Into the Weak Version

Let's see what happens when someone tries to attack the vulnerable website using SQL injection.

#### Step 1: Manual SQL Injection Attack

**What the hacker does:**
1. Goes to the login page: `https://sovulnerable.wasmer.app/auth.php`
2. Instead of typing a real username, types: `admin' OR '1'='1 #`
3. Types any password (doesn't matter)
4. Clicks "Log In"

**What happens:**
- The website builds this SQL query: `SELECT id FROM users WHERE login = 'admin' OR '1'='1' AND password = 'anything'`
- Since `'1'='1'` is always true, the query finds a user
- The hacker gets logged in as admin **without knowing the password!**
- Now they can access all user data, change settings, delete accounts, etc.

**Result:** ✅ Attack successful - hacker is in!

#### Step 2: URL-Based SQL Injection (Contacts Page)

**What the hacker does:**
1. Visits: `https://sovulnerable.wasmer.app/contacts.php?id=1 OR 1=1`
2. Opens browser console (F12)

**What happens:**
- The website runs: `SELECT * FROM contacts WHERE id = 1 OR 1=1`
- This returns ALL contacts in the database (not just one)
- The hacker can see everyone's contact information
- They can also try: `id=-1 UNION SELECT 1,2,3,4,5` to extract data from other tables

**Result:** ✅ All database data exposed!

#### Step 3: Using SQLMap (Automated Attack Tool)

SQLMap is a real tool that hackers use to automatically find and exploit SQL injection vulnerabilities. It's like a robot hacker that tries thousands of attacks automatically.

**What SQLMap does:**

1. **Detects the vulnerability:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" --batch
   ```
   - SQLMap tries different SQL injection techniques
   - Finds that the website is vulnerable
   - Reports: "SQL injection detected!"

2. **Lists all databases:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" --dbs --batch
   ```
   - Shows: `gamedev_php`, `information_schema`, `mysql`
   - Hacker now knows all databases on the server

3. **Lists all tables:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" -D gamedev_php --tables --batch
   ```
   - Shows: `contacts`, `users`, `trending`
   - Hacker knows the database structure

4. **Steals all user data:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" -D gamedev_php -T users --dump --batch
   ```
   - Extracts ALL usernames, emails, and password hashes
   - Complete data breach!

**Result:** ✅ Complete database compromise in minutes!

### What Happens with the Protected Version?

Now let's see what happens when the same hacker tries to attack the protected version:

#### Manual SQL Injection Attempt:
- Hacker types: `admin' OR '1'='1 #` in login
- **Result:** ❌ Login fails - attack blocked!
- Prepared statements prevent the SQL code from being injected

#### URL-Based SQL Injection Attempt:
- Hacker visits: `https://soprotected.wasmer.app/contacts.php?id=1 OR 1=1`
- **Result:** ❌ No data extracted - attack blocked!
- The `id` parameter is validated as an integer, so `OR 1=1` is rejected

#### SQLMap Automated Attack:
```bash
sqlmap -u "https://soprotected.wasmer.app/contacts.php?id=1" --batch
```

**What SQLMap reports:**
- ❌ "No SQL injection detected"
- ❌ "All injection attempts failed"
- ❌ "No injection point found"

**Result:** ✅ All attacks blocked - database is safe!

### Why the Protected Version Works

**The protected version uses:**
1. **Prepared Statements:** SQL code and data are separated, so injection is impossible
2. **Input Validation:** The `id` parameter is checked to be a number only
3. **Type Safety:** Invalid input is rejected before it reaches the database

**Comparison:**

| Attack Method | Weak Version | Protected Version |
|--------------|-------------|-------------------|
| Manual SQL Injection (Login) | ✅ Successful | ❌ Blocked |
| URL SQL Injection (Contacts) | ✅ Successful | ❌ Blocked |
| SQLMap Detection | ✅ Vulnerability Found | ❌ No Vulnerability |
| Data Extraction | ✅ All data stolen | ❌ Protected |
| Database Enumeration | ✅ Possible | ❌ Prevented |

### Real-World Impact

**If the weak version was a real website:**
- All user passwords could be stolen
- All personal information exposed
- Financial data compromised
- Website reputation destroyed
- Legal liability for data breach
- Users lose trust

**With the protected version:**
- All attacks are blocked
- User data is safe
- Website reputation maintained
- No legal issues
- Users trust the website

**Remember:** SQLMap is used by real hackers every day. If your website can be exploited by SQLMap, real attackers will find and exploit it too. That's why we test with SQLMap - if it can't break in, real hackers probably can't either!

---

## What You Learned

This project demonstrates:
1. **Common web vulnerabilities** and how they work
2. **How to fix security issues** using industry-standard practices
3. **Defense in depth** - multiple layers of security
4. **Secure coding practices** for PHP applications
5. **Session management** and authentication
6. **Input validation** and output escaping
7. **File upload security** best practices

---

## Technologies Used

- **PHP** - Server-side programming
- **MySQL** - Database
- **HTML/CSS** - Frontend
- **JavaScript** - Client-side functionality (console loop demo; main functionality in inline scripts)
- **Apache** - Web server (.htaccess configuration)
- **Sessions** - Server-side state management
- **Prepared Statements** - Secure database queries
- **bcrypt** - Password hashing

---

## Summary

**Weak Version = House with unlocked doors**
- Works, but anyone can break in
- No security measures
- Vulnerable to common attacks

**Protected Version = Fortified house**
- Same functionality
- Multiple security layers
- Industry-standard protection
- Safe from common attacks

Both versions do the same thing (game development website), but the protected version does it **securely**.

---

**Remember:** Security is not optional. In the real world, websites without proper security get hacked, data gets stolen, and users lose trust. Always build secure applications from the start!
