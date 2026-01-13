# Protected Version - Security Documentation

## Overview

This document explains the security improvements in the **protected version** compared to the **weak version** (main folder). The protected version implements industry-standard security practices to protect against common web vulnerabilities.

---

## Key Security Improvements

### 1. SQL Injection Protection

**Weak Version:**
```php
$sql = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";
$query = $pdo->query($sql);
```
- Direct string concatenation in SQL queries
- Vulnerable to SQL injection attacks
- Example attack: `' OR '1'='1`

**Protected Version:**
```php
$sql = 'SELECT id, login, password FROM users WHERE login = ?';
$query = $pdo->prepare($sql);
$query->execute([$login]);
```
- Uses prepared statements with parameter binding
- All user input is safely escaped
- Prevents SQL injection completely

---

### 2. Password Security

**Weak Version:**
```php
$password = md5($password); // Weak hashing
```
- Uses MD5 (cryptographically broken)
- No salt
- Vulnerable to rainbow table attacks
- Fast to crack

**Protected Version:**
```php
$password_hash = password_hash($password, PASSWORD_DEFAULT); // bcrypt
// Verification:
password_verify($password, $user->password)
```
- Uses `password_hash()` with bcrypt (PASSWORD_DEFAULT)
- Automatic salt generation
- Computationally expensive (slows brute force)
- Industry standard (OWASP recommended)

---

### 3. Cross-Site Scripting (XSS) Protection

**Weak Version:**
```php
echo $user->login; // Direct output
echo $el->followers;
```
- No output escaping
- Vulnerable to XSS attacks
- Malicious scripts can execute in user's browser

**Protected Version:**
```php
echo htmlspecialchars($user_login, ENT_QUOTES, 'UTF-8');
echo htmlspecialchars($el->followers, ENT_QUOTES, 'UTF-8');
```
- All user-controlled output is escaped
- `htmlspecialchars()` converts `<`, `>`, `&`, `"`, `'` to HTML entities
- Prevents script injection
- Applied to all echo statements

---

### 4. Authentication Security

**Weak Version:**
```php
setcookie('login', $login, time() + 3600 * 24 * 30, '/');
// Authentication check:
if(isset($_COOKIE['login'])) { ... }
```
- Cookie-based authentication only
- Cookies can be easily manipulated
- No server-side session validation
- No session timeout

**Protected Version:**
```php
// Session-based authentication
$_SESSION['user_id'] = $user->id;
$_SESSION['login'] = $user->login;
$_SESSION['last_activity'] = time();

// Authentication check:
require_once "lib/session_check.php";
requireLogin(); // Validates session on server + checks timeout
```
- Server-side session storage
- Session timeout (30 minutes inactivity)
- Session validation on every request
- Automatic session destruction on timeout
- More secure than cookies alone

---

### 5. File Upload Security

**Weak Version:**
```php
$image = $_FILES['image']['name'];
move_uploaded_file($tempname, 'imgs/blocks/' . $image);
```
- Uses original filename (path traversal risk)
- No file type validation
- No size limits
- Vulnerable to malicious file uploads

**Protected Version:**
```php
// Size limit (5MB)
$max_size = 5 * 1024 * 1024;
if ($file_size > $max_size) { ... }

// Extension validation
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
if(!in_array($ext, $allowed_extensions)) { ... }

// MIME type validation
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $tempname);

// Unique filename generation
$unique_name = uniqid() . '_' . time() . '.' . $ext;

// Path sanitization
$real_upload_dir = realpath($upload_dir);
$folder = $real_upload_dir . DIRECTORY_SEPARATOR . basename($unique_name);
```
- File size limit (5MB)
- Extension whitelist
- MIME type validation (prevents fake extensions)
- Unique filename generation (prevents overwrites)
- Path sanitization (prevents directory traversal)
- Secure file storage

---

### 6. Input Validation & Sanitization

**Weak Version:**
```php
$login = $_POST['login'];
$email = $_POST['email'];
```
- No input sanitization
- No validation
- Direct use of user input

**Protected Version:**
```php
$login = trim(filter_var($_POST['login'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));

// Validation
if(strlen($login) < 2) { ... }
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { ... }
```
- `filter_var()` with appropriate filters
- Length validation
- Email format validation
- Null coalescing operator (`??`) for safety
- Trim whitespace

---

### 7. Error Handling

**Weak Version:**
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Errors displayed directly to users
```
- Errors exposed to users
- Reveals sensitive information (paths, SQL, etc.)
- Helps attackers understand system structure

**Protected Version:**
```php
error_log("Registration error: " . $e->getMessage());
// Errors logged, not displayed
header('Location: ...?error=' . urlencode('User-friendly message'));
```
- Errors logged to server logs
- Generic error messages to users
- No sensitive information disclosure
- Better user experience

---

### 8. Directory Protection

**Protected Version Only:**
- `.htaccess` in `lib/` directory blocks direct GET access
- PHP includes still work (server-side)
- Form POST requests allowed
- Prevents direct URL access to processing scripts

---

### 9. Session Management

**Protected Version:**
- Session timeout: 30 minutes of inactivity
- Automatic session destruction on timeout
- Session activity tracking
- Secure session cookie handling
- Proper logout with session destruction

---

### 10. Security Headers

**Protected Version Only:**
```apache
# .htaccess security headers
Header set X-Frame-Options "SAMEORIGIN"
Header set X-Content-Type-Options "nosniff"
Header set X-XSS-Protection "1; mode=block"
```
- Prevents clickjacking
- Prevents MIME type sniffing
- XSS protection headers

---

## Security Checklist

### Implemented in Protected Version:

- [x] SQL Injection protection (prepared statements)
- [x] XSS protection (output escaping)
- [x] Secure password hashing (bcrypt)
- [x] Session-based authentication
- [x] Session timeout
- [x] File upload security (validation, size limits, unique names)
- [x] Input sanitization and validation
- [x] Secure error handling
- [x] Directory protection (.htaccess)
- [x] Security headers
- [x] Path traversal prevention
- [x] MIME type validation


## File Structure Comparison

### Weak Version:
```
/
├── index.php
├── auth.php
├── reg.php
├── user.php
├── lib/
│   ├── auth.php (cookie-based)
│   ├── reg.php (MD5 hashing)
│   └── db.php
└── ...
```

### Protected Version:
```
/
├── index.php (XSS protected)
├── auth.php (session-based)
├── reg.php (secure hashing)
├── user.php (session check)
├── lib/
│   ├── auth.php (session + password_verify)
│   ├── reg.php (password_hash)
│   ├── session_check.php (NEW - session management)
│   ├── config.php (environment variables)
│   ├── db.php (prepared statements)
│   └── .htaccess (directory protection)
├── .htaccess (security headers)
└── ...
```

---

## Migration Guide

If migrating from weak to protected version:

1. **Database:** Update password hashes from MD5 to bcrypt
   ```sql
   -- Old users will need to reset passwords or use migration script
   ```

2. **Sessions:** Replace cookie checks with session checks
   ```php
   // Old:
   if(isset($_COOKIE['login'])) { ... }
   
   // New:
   require_once "lib/session_check.php";
   requireLogin();
   ```

3. **Output:** Add `htmlspecialchars()` to all user output
   ```php
   // Old:
   echo $user->login;
   
   // New:
   echo htmlspecialchars($user->login, ENT_QUOTES, 'UTF-8');
   ```

4. **SQL:** Convert all queries to prepared statements
   ```php
   // Old:
   $query = $pdo->query("SELECT * FROM users WHERE id = $id");
   
   // New:
   $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
   $query->execute([$id]);
   ```

---

## Testing Security

### SQL Injection Test:
```
Login: admin' OR '1'='1
Password: anything
Result: Should fail (protected) vs succeed (weak)
```

### XSS Test:
```
Input: <script>alert('XSS')</script>
Result: Should be escaped (protected) vs executed (weak)
```

### Session Timeout Test:
```
1. Log in
2. Wait 30+ minutes
3. Try to access protected page
Result: Should redirect to login (protected) vs stay logged in (weak)
```

---

## Best Practices Applied

1. **Defense in Depth:** Multiple layers of security
2. **Principle of Least Privilege:** Minimal access required
3. **Fail Securely:** Errors don't reveal information
4. **Input Validation:** Validate and sanitize all input
5. **Output Encoding:** Escape all output
6. **Secure Defaults:** Secure by default configuration
7. **Keep It Simple:** Clear, maintainable security code

---

## References

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)
- [OWASP Password Storage Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html)

---

**Last Updated:** 2024
**Version:** Protected Version 1.0
