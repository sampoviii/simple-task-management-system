SIMPLE TASK MANAGEMENT SYSTEM
=============================

1. REQUIREMENTS
---------------
- XAMPP with Apache, MySQL/MariaDB, and PHP.
- The project folder served by Apache. The usual location is:
  C:\xampp\htdocs\simple-task-management-system

If the project is still on your Desktop, copy it into htdocs or configure
Apache to serve its current location. Keep the submissions folder writable
by Apache so users can upload task files.


2. START THE PROJECT
--------------------
1. Open the XAMPP Control Panel.
2. Start Apache and MySQL.
3. Open http://localhost/phpmyadmin/
4. Select Import, choose this project's database.sql file, and click Import.
   The script creates the stms_db database and the tables used by the app.
5. Open:
   http://localhost/simple-task-management-system/

The database connection settings are in config/db_connect.php. They currently
expect MySQL on localhost, database stms_db, username root, and a blank
password. If your local MySQL root account has a password, update that file.


3. CREATE A REGULAR USER
------------------------
1. Open:
   http://localhost/simple-task-management-system/auth/sign-up.php
2. Enter a name, email, and password.
3. Passwords must be at least 8 characters and contain at least one uppercase
   letter and one special character.
4. Sign in at:
   http://localhost/simple-task-management-system/auth/login.php

New signups receive the user role and can use the user dashboard, view
announcements and tasks, submit task files, and update their profile.


4. CREATE A NEW ADMIN
---------------------
The project does not have a built-in admin account or default credentials.
Choose a strong password and generate its PHP password hash in PowerShell:

  & 'C:\xampp\php\php.exe' -r '$password = readline("New admin password: "); echo password_hash($password, PASSWORD_DEFAULT), PHP_EOL;'

Copy the printed hash. In phpMyAdmin, select the SQL tab and run the following
query after replacing the example name, email, and hash:

  INSERT INTO stms_db.users (first_name, last_name, email, password, role)
  VALUES ('Admin', 'Name', 'admin@example.com', 'PASTE_HASH_HERE', 'admin');

Use the email and password you chose to sign in through the regular login
page. Admin accounts can create and manage tasks and announcements, view
users, and view submissions. Only grant this role to people you trust.


5. PROMOTE AN EXISTING USER TO ADMIN
------------------------------------
First, create the account through the signup page. Then run this query in
phpMyAdmin's SQL tab, replacing the email with the exact account email:

  UPDATE stms_db.users
  SET role = 'admin'
  WHERE email = 'user@example.com';

Check that exactly one account was updated:

  SELECT id, first_name, last_name, email, role
  FROM stms_db.users
  WHERE email = 'user@example.com';

The user must sign out and sign in again for the new role to take effect.

To remove admin access from that account:

  UPDATE stms_db.users
  SET role = 'user'
  WHERE email = 'user@example.com';


6. RESET A PASSWORD
-------------------
Passwords are stored as hashes and cannot be read back. Generate a hash for a
new password using the PowerShell command in section 4, then run:

  UPDATE stms_db.users
  SET password = 'PASTE_NEW_HASH_HERE'
  WHERE email = 'user@example.com';

Replace the email with the account to reset. Do not share or publish the
generated hash.


7. BACKUPS AND UPLOADS
----------------------
Export the stms_db database from phpMyAdmin regularly to back up account,
task, announcement, and submission records. Back up the submissions folder
separately; uploaded files are stored on disk, not inside the database.
