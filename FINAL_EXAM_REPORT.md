



University of Europe for Applied Sciences


Final Project:
Test SQL Injection, on your own website
Software Optimization


Prof. Rand Koualty


Teyyub Malikov 			20583466
Jorge Adrian Torres Zuniga 	98466681
Ashim Batyr 			92767418
Tahir Akhundov 			64770004
Samuel Josino de Souza		20352497
Kavita Nadlamani			82170198

Delivery date: 13-Jan-2026
Presentation date: 22-Jan-2026
Contents
Abstarct …………………………………………………………………………………...	3
1. Introduction …………………………………………………………………………….	3
2. Project Overview ……………………………………………………………………….	3
3. System Description and Attack Surface ………………………………………………..	4
4. Static Application Security Testing (SAST) ……………………………………………	5
4.1 Objective ...……………………………………………………………………………	5
4.2 Methodology …………………………………………………………………………..	5
4.3 Vulnerability Findings ………………………………………………………………...	6
4.4 Static Testing Summary ……………………………………………………………….	6
5. Dynamic Application Security Testing (DAST) ………………………………………..	7
5.1 Manual SQL Injection Testing ………………………………………………………..	7
5.2 Automated SQL Injection Testing Using SQLMap …………………………………..	9
5.3 Parameter Testing …………………………………………………………………….	9
5.4 Database Enumeration ……………………………………………………………….. 10
5.5 Data Extraction ………………………………………………………………….…… 12
6. Findings Summary …………………………………………………………………….. 13
7. Security Analysis ……………………………………………………………………… 13
8. Security recommendations ………………………………………………...….……….  14
9. Conclusion …………………………………………………………………………....... 15
10 References …………………………………………………………………………….  16






Abstract
SQL Injection is a problem for web applications, even after many articles have been written. The reason is that many systems do not check user input properly and developers do not follow coding rules. In this report we examine a web application that was created on purpose to be vulnerable. The web application is built with PHP. The goal is to find out if SQL Injection problems exist, to confirm that SQL Injection problems are real and to see how bad SQL Injection problems can be. We use two methods to test the application: Static Application Security Testing and Dynamic Application Security Testing. The two methods were used to see the security of the web application. Also, it was performed code inspection, payload-based attacks and used SQLMap for automated exploitation. SQL Injection vulnerabilities show how weak query construction can cause authentication bypass, unauthorized data access and full database compromise. 
1. Introduction
Web applications use databases to store information. The private information is user credentials, personal data and transaction records. When web applications do not handle user input correctly, they become vulnerable, specially to SQL injection attacks. SQL injection attacks let attackers change queries and read data they should not see. 
This project looks at a web application that was specifically created for testing. The goal is not to exploit the web application, for harm. The goal is to show in a way how SQL injection vulnerabilities appear. The goal is to show how SQL injection vulnerabilities can be detected with security testing methods and what these attacks mean for security. 
2. Project Overview
This project was built to show how to find SQL injection vulnerabilities by doing source code analysis and to confirm SQL injection vulnerabilities by doing runtime testing. The results show the security risks that come from SQL query construction and the consequences of ignoring programming techniques. 
For this test, static and dynamic analysis was performed (See Table 1).
Testing Scope
Static Application Security Testing (SAST)
	Dynamic Application Security Testing (DAST)

	Manual SQL injection testing using crafted payloads
	Automated SQL injection testing using SQLMap

Table 1. Testing Scope diagram.
Two webpages were created for testing (Figure 1). Both look identical for the front end, but the back end for the protected one has much more security against attacks (Table 2).
Site	Description
https://soprotected.wasmer.app/	A protected website, secured to hold against SQL injection attacks.
https://sovulnerable.wasmer.app/
	A more weak and vulnerable website. Vulnerable to hacks and sensible data compromise.
Table 2. Description of both used websites.

Figure 1. Website homescreen.
3. System Description and Attack Surface
The target system is a PHP based web application that connects to a database and it handles user authentication. The system also handles data retrieval through URL parameters and processes user supplied input directly in scripts. The backend scripts use the user supplied input to build SQL queries dynamically.
Key exposed components include login functionality handling username and password fields, a data retrieval endpoint using a GET parameter (id) to query database records and Backend PHP scripts responsible for database interaction.














Figure 2. System Architecture.
The system architecture (Figure 2) consists of a client interacting with a PHP-based web application through HTTP requests. User-supplied input is processed by backend scripts and directly embedded into SQL queries, which are executed on a relational database. The lack of input validation and query parameterization introduces SQL injection vulnerabilities exploitable through both manual and automated techniques.
4. Static Application Security Testing (SAST)
4.1 Objective
Static security testing finds the SQL injection vulnerabilities, without running the application. We used security testing to look at the source code and spot coding patterns. Static security testing looks for patterns that build SQL queries. That handle the user input in unsafe ways.
4.2	Methodology
A manual source code review was performed and examined the PHP files that handle authentication GET parameter processing and database access. The manual source code review focused on direct concatenation of user input into SQL statements, absence of prepared statements and parameter binding, lack of input validation or sanitization, trust assumptions made about external input.



4.3 Vulnerability Findings
Table 3 summarizes the SQL injection vulnerabilities identified during static analysis, highlighting the affected components, root causes, and potential security impact.
Vulnerability	Affected File	Description	Why the Code Is Vulnerable	Security Impact
Login Authentication SQL Injection	lib/auth.php	The authentication mechanism dynamically builds SQL queries by inserting user-provided login credentials directly into the query string.	- User input is directly embedded into the SQL statement.- No parameterization or escaping mechanisms are used.- Crafted input can alter the logical structure of the query.	An attacker can inject SQL logic to manipulate the WHERE clause, forcing authentication checks to always evaluate as true. This results in authentication bypass and unauthorized system access.
GET Parameter SQL Injection	contacts.php	The application retrieves a record identifier from a GET parameter and directly incorporates it into an SQL query without prior validation.	- No type enforcement is applied.- No input sanitization is performed.- User input directly influences the SQL query structure.	An attacker can manipulate the URL parameter to retrieve all database records, enumerate schema metadata, and access sensitive tables and columns.
Table 3. Vulnerability findings.
4.4 Static Testing Summary
Multiple files rely on string concatenation to construct SQL queries, embedding user-supplied input directly into the query logic. The application does not implement prepared statements or parameterized queries, and user input is consistently trusted without proper validation or sanitization. These insecure coding patterns significantly expose the system to SQL injection attacks by allowing external input to directly influence database query execution.
The static analysis shows violations of secure coding rules, as we can see vulnerabilities without running the application. The static analysis shows that SAST works to find security problems, in the development of lifecycle.
5. Dynamic Application Security Testing (DAST)
The dynamic testing was conducted by interacting with the running application and observing its behavior when malicious payloads were introduced.
5.1 Manual SQL Injection Testing
The objective is to find out if the authentication can be bypassed with the SQL injection payloads.
Payload Types Used:
•	Boolean-based injections
•	Comment-based injections
We noticed that the injected payloads changed the query logic. The query logic change let the login work without any credentials. The login succeeded even though no valid credentials were supplied (Figures 3, 4, 5 and 6). 


Figure 3. Boolean-based injection login test.




Figure 4. Boolean-based injection login successful.
Figure 5. Comment-based injection login test.
Figure 6. Comment-based injection login successful.
Now we need to check the security of the URL parameters and if they are vulnerable to SQL injection. To test this, we introduced payloads and we managed to change the query results, enumerate the tables and extract the metadata. The application responded with expanded datasets and schema information, confirming exploitable SQL injection vulnerabilities.
5.2	Automated SQL Injection Testing Using SQLMap
Automated testing was performed using SQLMap to validate findings and demonstrate the extent of exploitation. SQLMap confirmed that the id parameter is vulnerable to multiple SQL injection techniques, including boolean-based blind injection, error-based injection, time-based blind injection and UNION-based injection.
SQLMap (Figures 7-14) enumerated available databases, tables within the target database and columns of sensitive tables. We see that the injection vulnerabilities confirm the entire database visibility and that it can be accessed. Sensitive user data was successfully extracted from the database, demonstrating the real-world impact of the vulnerability. 
5.3 Parameter Testing:
Target Parameter: id
SQLMap Command: python3 sqlmap.py -u “https://sovulnerable.wasmer.app/contacts.php?id=1”

Figure 7. SQL Map. Parameter Testing.
Result: SQLMap has checked and confirmed the parameter is vulnerable to injection.

5.4 Database Enumeration
Objective:
Enumerate database structure using SQL injection.
Commands Used:
python3 sqlmap.py -u “https://sovulnerable.wasmer.app/contacts.php?id=1” -–tables (List tables)
python3 sqlmap.py -u “https://sovulnerable.wasmer.app/contacts.php?id=1” –tables -D gamedev_php –-columns (List columns)

Figure 8. SQL Map. Database Enumeration 1.

Figure 9. SQL Map. Database Enumeration 2.


Figure 10. SQL Map. Database Enumeration 3.

5.5 Data Extraction
Goal: Showing the effect of extracting sensitive data.
Data Dump Command:
python3 sqlmap.py -u “https://sovulnerable.wasmer.app/contacts.php?id=1” -D gamedev_php -T users -C first_name,last_name,email,password --dump

Figure 11. SQL Map. Data extraction 1.

Figure 12. SQL Map. Data extraction 2.


Figure 13. SQL Map. Data extraction 3.
Figure 14. SQL Map. Data extraction 4

.
6. Findings Summary
Table 4 shows a summary of findings for all 3 tests performed.
Static Testing Results	Dynamic Testing Results (Manual)
	Dynamic Testing Results (Automated):

- The system detected SQL construction.

- Application classified as vulnerable.	- Authentication bypass confirmed.

- Unauthorized data access achieved.
	- Injection vulnerabilities detected.

- Full schema enumeration successful.

- Sensitive data extraction successful.

Table 4. Findings summary.
7. Security Analysis
The evaluated application exhibits several high-risk characteristics:
•	Acceptance of unvalidated user input
•	Dynamic SQL query construction
•	Absence of prepared statements
•	No defense against automated exploitation
•	Complete exposure of database structure and content
These weaknesses let a novice attacker break into the system and let an advanced attacker break into the system with minimal effort.
8. Security recommendations
To avoid problems with SQL injection we need to be careful when we talk to databases. We should always use statements and parameterized queries when we do things with databases. This means we keep the SQL logic separate from the information that users give us. Parameterized queries are good because they stop information from changing the way SQL statements work. This helps a lot because it removes the way that SQL injection happens. We should use queries, for all database things we do. SQL injection is a problem and using parameterized queries helps to make our databases safer.
When we build a website or an application we need to make sure that the information people put in is safe. So we have to check everything that people type in carefully. We should always think that what people type in might not be correct and we need to check it to make sure it is what we expect. This means checking what type of information it is, how long it is and what it looks like. If we do this we can stop things from happening to our application like someone trying to mess with the database. The database is an important part of our application so we need to protect it from bad input. By checking everything we can make our application safer and prevent problems.
You should never put user input into SQL queries. This is because it lets bad people change what the query does by sending information. If you use simple ways to ask the database questions instead of building queries from strings that the user gives you the application will be a lot safer from attacks that try to sneak bad things into the database. This also makes the code a lot easier to work with and understand. The SQL queries are safer. The application is better protected against injection attacks when you do this. Using ways to talk to the database is very important, for the security of the application and the database.
Security needs to be checked all the time. We should use static and dynamic security testing techniques to do this. Static analysis is good because it helps us find coding patterns early on when we are making the application. Dynamic testing is also important because it shows us if someone can really exploit a weakness when the application is running. This testing also shows us what would happen in the world. If we use both of these methods we get an idea of the security of the application than if we only used one method. Security testing is very important. We should always use both static and dynamic security testing techniques to check the security of the application.
Finally, secure coding guidelines and best practices should be formally incorporated into the development process. Establishing clear standards, performing regular code reviews, and raising security awareness among developers help ensure that security considerations are integrated into software design decisions from the outset, rather than treated as an afterthought.
9. Conclusion
This project demonstrates SQL injection vulnerabilities and it helps us prevent them. They can be identified systematically through analysis and validated through testing. The results show that insecure SQL query construction leads to severe security consequences, including authentication bypass and complete database compromise. Of course we want to avoid this as much as possible. By combining SAST and DAST methodologies, software engineers can gain deeper insight into application security weaknesses and address them before deployment, which is a vital step in web security. The findings reinforce the importance of defensive programming and proactive security practices in modern software engineering.




10. References
1. OWASP Foundation. (2021). *OWASP Top 10 - 2021*. Retrieved from https://owasp.org/www-project-top-ten/
2. Halfond, W. G., Viegas, J., & Orso, A. (2006). *A Classification of SQL-Injection Attacks and Countermeasures*. Proceedings of the IEEE International Symposium on Secure Software Engineering.
3. Stuttard, D., & Pinto, M. (2011). *The Web Application Hacker's Handbook: Finding and Exploiting Security Flaws*. Wiley.
4. PHP.net. (2024). *PHP: Prepared Statements*. Retrieved from https://www.php.net/manual/en/pdo.prepared-statements.php
5. OWASP Foundation. (2024). *Password Storage Cheat Sheet*. Retrieved from https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html
6. Shar, L. K., & Tan, H. B. K. (2012). Defeating SQL injection. Computer, 46(3), 69-77.
7. Sadeghian, A., Zamani, M., & Abdullah, S. M. (2013, September). A taxonomy of SQL injection attacks. In 2013 International Conference on Informatics and Creative Multimedia (pp. 269-273). IEEE.
8. Boyd, S. W., & Keromytis, A. D. (2004, June). SQLrand: Preventing SQL injection attacks. In International conference on applied cryptography and network security (pp. 292-302). Berlin, Heidelberg: Springer Berlin Heidelberg.
9. IBM Guardium Data Protection. (02-01-2026). Characteristics of an SQL Injection.
https://www.ibm.com/docs/en/gdp/12.x?topic=analytics-characteristics-sql-injection-attack

