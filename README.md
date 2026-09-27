# 🎓 Student & Examination Management Portal

> A PHP and MySQL based student and examination management web application with student registration, authentication, academic information, examination-form submission, semester-wise subject selection, printable examination confirmation, PDF generation, and admit-card workflow.

---

## 📌 Project Overview

The **Student & Examination Management Portal** is a web-based academic management application developed using **PHP, MySQL, Bootstrap, JavaScript, and HTML/CSS**.

The system provides a student-oriented workflow for managing:

- Student registration
- Student authentication
- Student profile information
- Profile photo upload
- Semester selection
- Examination type selection
- Semester-wise subjects
- Examination form submission
- Examination confirmation
- Print functionality
- PDF download
- Admit-card generation workflow
- Password management
- Session-based access control

The project was developed as a practical demonstration of building a database-driven PHP web application.

---

# ✨ Key Features

## 👨‍🎓 Student Registration

Students can create an account by providing personal and academic information.

### Registration Fields

- Candidate Name
- Enrollment Number
- Roll Number
- Father's Name
- Mother's Name
- Date of Birth
- Gender
- Nationality
- Religion
- Course
- Admission Year
- Email
- Password
- Profile Photograph

The registration system validates required fields and email format and also applies a minimum password-length check.

Profile photographs support:

```text
JPG
JPEG
PNG
```

Uploaded images are stored with generated filenames to reduce filename collisions.

Passwords are stored using PHP's `password_hash()` function rather than plain text.

---

# 🔐 Student Login

Students can authenticate using either:

```text
Email
```

or

```text
Enrollment Number
```

together with their password.

### Login Flow

```text
                ┌─────────────────┐
                │     Student     │
                └────────┬────────┘
                         │
                         ▼
                ┌─────────────────┐
                │   Login Page    │
                └────────┬────────┘
                         │
                 Email / Enrollment
                         +
                      Password
                         │
                         ▼
                ┌─────────────────┐
                │     MySQL       │
                │ Student Record  │
                └────────┬────────┘
                         │
                         ▼
                ┌─────────────────┐
                │ Password Verify │
                └────────┬────────┘
                         │
                  ┌──────┴──────┐
                  │             │
                Valid         Invalid
                  │             │
                  ▼             ▼
             Dashboard         Error
```

Successful authentication stores relevant student information in the PHP session and redirects the student to the dashboard.

---

# 🛡️ Session-Based Authentication

Protected student pages check whether the student session exists.

If the student is not authenticated, the application redirects the user to the login page.

Session information includes data such as:

```text
student_id
student_name
enrollment_no
image_path
```

This prevents direct access to protected student pages without authentication.

---

# 📊 Student Dashboard

After login, the student receives access to a dashboard.

The dashboard provides navigation to:

```text
🏠 Dashboard
🏠 Home
👤 Student Data
📝 Examination Form
👨‍💻 Contact
🔑 Change Password
🚪 Logout
```

The dashboard also displays:

- Student name
- Academic session
- Current date
- Current time
- Examination-related options

---

# 👤 Student Data

The student data section presents the student's academic and personal information in a structured data-sheet format.

### Displayed Information

```text
Candidate Name
Enrollment Number
Roll Number
Father's Name
Mother's Name
Date of Birth
Gender
Nationality
Religion
Course
Year of Admission
Email
Mobile Number
Profile Photograph
```

The data is displayed in a printable student-data-sheet layout.

---

# 📝 Examination Form

The examination form allows an authenticated student to submit examination information.

The student selects:

```text
Course
Semester
Examination Type
Subjects
```

The course is retrieved from the student's profile.

---

# 📚 Semester Selection

The current implementation provides semester options from:

```text
Semester 1
Semester 2
Semester 3
Semester 4
Semester 5
Semester 6
```

When the student selects a semester, the corresponding subjects are displayed dynamically.

---

# 🧾 Examination Types

The application currently provides:

```text
Regular
Ex
Back
```

These options are available from the examination-type selection field.

---

# 📖 Dynamic Subject Selection

Subjects are associated with semesters.

Each subject can contain:

```text
Subject Code
Subject Name
Subject Type
```

### Subject Types

```text
THEORY
PRACTICAL
```

When the semester changes, JavaScript loads the corresponding subject list and dynamically creates the subject-selection interface.

Example:

```text
DCO 502 - Web Technology - THEORY
DCO 503 - Data Communication & Networks - THEORY
DCO 511 - Graphics & Multimedia Lab - PRACTICAL
DCO 512 - Web Technology Lab - PRACTICAL
```

---

# 💾 Examination Form Submission

After selecting the semester, examination type, and subjects, the form is submitted to the PHP backend.

The backend stores the examination information in the MySQL database.

The stored information includes:

```text
student_id
course
semester
exam_type
subjects
```

Selected subjects are serialized as JSON before being stored.

---

# 🔎 Latest Examination Form

The application retrieves the latest examination form associated with the currently logged-in student.

The latest record is selected using:

```sql
ORDER BY id DESC
LIMIT 1
```

The stored subject JSON is decoded before displaying the examination confirmation.

---

# 📄 Examination Confirmation

After successful submission, the system displays an examination confirmation page.

The confirmation can contain:

```text
Student Name
Enrollment Number
Semester
Examination Type
Submission Date
Student Photograph
Selected Subjects
```

Example layout:

```text
┌─────────────────────────────────────────┐
│       FORM A - EXAMINATION              │
│           CONFIRMATION                   │
├─────────────────────────────────────────┤
│ Name:          Student Name             │
│ Enrollment:    XXXXXXXX                 │
│ Semester:      Semester 6               │
│ Exam Type:     Regular                   │
│ Submitted On:  Date / Time              │
│                                         │
│ Subjects                                │
│ ├── DCO 601 - Advanced RDBMS            │
│ ├── DCO 602 - Visual Programming        │
│ ├── DCO 603 - Information Security     │
│ └── DCO 620 - Major Project             │
└─────────────────────────────────────────┘
```

---

# 🖨️ Print Examination Form

The application provides a print option for the examination confirmation.

The print workflow extracts the relevant examination section and opens the browser's print dialog.

Users can then:

- Print the document
- Save it as PDF using the browser
- Select a printer
- Adjust print settings

---

# 📥 PDF Download

The application also includes client-side PDF generation using:

```text
html2canvas
jsPDF
```

The examination confirmation section is rendered into a canvas and converted into a PDF.

Generated file:

```text
exam_form.pdf
```

---

# 🎫 Admit Card Workflow

After examination-form submission, the interface includes a countdown-based workflow for generating the admit card.

The current implementation:

```text
60-second countdown
        ↓
Generate Admit Card button
        ↓
admit_card.php
```

The generation button becomes available after the countdown finishes.

> The countdown is a frontend workflow and should not be treated as a real security mechanism in a production system.

---

# 🔑 Password Management

The application includes a password-management page accessible from the student dashboard.

The navigation contains:

```text
Change Password
```

Passwords are handled using PHP password hashing mechanisms rather than plain-text storage.

---

# 🚪 Logout

The student can terminate the authenticated session through:

```text
Logout
```

This returns the user to the unauthenticated state.

---

# 🗄️ Database

The application uses **MySQL** as the database system.

The application communicates with the database through PHP and PDO.

### Main Student Data

The student record contains information such as:

```text
candidate_name
enrollment_no
roll_no
father_name
mother_name
dob
gender
nationality
religion
course
admission_year
email
password_hash
image_path
```

### Examination Data

Examination records contain:

```text
student_id
course
semester
type
subjects
created_at
```

---

# 🏗️ System Architecture

```text
                        ┌──────────────────────┐
                        │       STUDENT        │
                        └──────────┬───────────┘
                                   │
                                   ▼
                        ┌──────────────────────┐
                        │ Registration / Login │
                        └──────────┬───────────┘
                                   │
                                   ▼
                        ┌──────────────────────┐
                        │ Authentication Layer │
                        │   PHP + Sessions     │
                        └──────────┬───────────┘
                                   │
                                   ▼
                        ┌──────────────────────┐
                        │      Dashboard       │
                        └──────────┬───────────┘
                                   │
               ┌───────────────────┼───────────────────┐
               │                   │                   │
               ▼                   ▼                   ▼
       ┌──────────────┐    ┌───────────────┐    ┌─────────────┐
       │ Student Data │    │ Exam Form     │    │ Password    │
       │              │    │               │    │ Management  │
       └──────┬───────┘    └───────┬───────┘    └─────────────┘
              │                    │
              ▼                    ▼
       ┌──────────────┐    ┌───────────────┐
       │   Students   │    │  Exam Forms   │
       │    Table     │    │     Table     │
       └──────┬───────┘    └───────┬───────┘
              │                    │
              └──────────┬─────────┘
                         ▼
                 ┌───────────────┐
                 │     MySQL     │
                 │    Database   │
                 └───────────────┘
```

---

# 🔄 Complete User Workflow

```text
                    START
                      │
                      ▼
                Open Website
                      │
                      ▼
               Student Registration
                      │
                      ▼
             Account Created
                      │
                      ▼
                  Login
                      │
             ┌────────┴────────┐
             │                 │
           Valid             Invalid
             │                 │
             ▼                 ▼
         Dashboard            Error
             │
             ├───────────────┐
             │               │
             ▼               ▼
       Student Data      Exam Form
                             │
                             ▼
                      Select Semester
                             │
                             ▼
                    Load Subjects
                             │
                             ▼
                  Select Exam Type
                             │
                             ▼
                     Select Subjects
                             │
                             ▼
                  Submit Examination
                             │
                             ▼
                     Store in MySQL
                             │
                             ▼
                  Examination Preview
                             │
                    ┌────────┴────────┐
                    ▼                 ▼
                  Print             PDF
                    │                 │
                    └────────┬────────┘
                             ▼
                       Admit Card
                         Workflow
```

---

# 🛠️ Technology Stack

## Backend

```text
PHP
```

## Database

```text
MySQL
```

## Frontend

```text
HTML5
CSS3
JavaScript
Bootstrap 5
```

## UI Libraries

```text
Bootstrap
Font Awesome
Animate.css
```

## PDF Generation

```text
html2canvas
jsPDF
```

## Authentication

```text
PHP Sessions
password_hash()
password_verify()
```

---

# 🎨 User Interface

The application uses a responsive academic portal-style interface.

UI elements include:

- Responsive navigation
- Sidebar dashboard
- Animated components
- Bootstrap cards
- Form controls
- Student information tables
- Examination confirmation cards
- Responsive layouts
- Font Awesome icons
- Animated page transitions

---

# 📱 Responsive Design

The interface uses responsive Bootstrap layouts.

The application is designed to work across:

```text
Desktop
Laptop
Tablet
Mobile
```

Responsive elements include:

- Navigation
- Registration forms
- Login forms
- Dashboard
- Student data
- Examination forms
- Subject lists
- Buttons
- Tables

---

# 📂 Suggested Project Structure

```text
student-examination-portal/
│
├── index.php
├── config.php
│
├── login.php
├── register.php
├── logout.php
├── forget_password.php
│
├── student_info.php
├── student_data.php
├── exam_form.php
├── admit_card.php
├── contact.php
│
├── asset/
│   └── logo.svg
│
├── uploads/
│   └── student-images/
│
├── admin/
│   └── ...
│
├── database/
│   └── database.sql
│
└── README.md
```

> Adjust this structure to exactly match the files in your GitHub repository.

---

# ⚙️ Requirements

Before running the application, install:

- PHP 8.x
- MySQL
- Apache
- XAMPP / WAMP / Laragon
- Modern web browser

Recommended local development environments:

```text
XAMPP
WAMP
Laragon
```

---

# 🚀 Installation

## Step 1: Clone the Repository

```bash
git clone YOUR_REPOSITORY_URL
```

---

## Step 2: Enter the Project Directory

```bash
cd student-examination-portal
```

---

## Step 3: Start Apache and MySQL

If using XAMPP:

```text
Apache → Start
MySQL  → Start
```

---

## Step 4: Move the Project

For XAMPP:

```text
C:\xampp\htdocs\
```

Place the project inside:

```text
C:\xampp\htdocs\student-examination-portal
```

---

# 🗄️ Database Setup

Open:

```text
http://localhost/phpmyadmin
```

Create a database:

```sql
CREATE DATABASE student_portal;
```

Then import the project's SQL file:

```text
database/database.sql
```

---

# 🔧 Database Configuration

Open:

```text
config.php
```

Configure your MySQL connection.

Example:

```php
<?php

$host = "localhost";
$db   = "student_portal";
$user = "root";
$pass = "";

$pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8mb4",
    $user,
    $pass
);

$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);
```

> Use your actual configuration structure if `config.php` in the repository differs.

---

# ▶️ Run the Application

After starting Apache and MySQL, open:

```text
http://localhost/student-examination-portal/
```

---

# 🔒 Security

The project implements several basic security mechanisms.

## Password Hashing

Passwords are stored using:

```php
password_hash($password, PASSWORD_DEFAULT)
```

Passwords are verified using:

```php
password_verify($password, $password_hash)
```

---

## Prepared Statements

Database operations use prepared statements for user-related queries.

Example:

```php
$stmt = $pdo->prepare(
    "SELECT * FROM students
     WHERE email = ? OR enrollment_no = ?"
);

$stmt->execute([
    $username,
    $username
]);
```

---

## Output Escaping

User-controlled values are escaped before being inserted into HTML where appropriate:

```php
htmlspecialchars($value)
```

---

## Session Protection

Protected pages check for an authenticated student session before displaying student-specific information.

---

# ⚠️ Production Security Improvements

This project is suitable as an academic/portfolio application, but additional hardening should be performed before treating it as a production examination system.

Recommended improvements:

```text
CSRF protection
Rate limiting
Secure session cookies
Session regeneration after login
Strict file upload validation
MIME-type validation
Maximum upload size
File storage outside public web root
Role-based access control
Input validation
Audit logging
HTTPS
Environment-based secrets
Database least-privilege accounts
Security headers
Error handling
Production logging
```

---

# 🧪 Testing Checklist

Before deployment, test the following:

```text
[ ] Student registration
[ ] Required field validation
[ ] Email validation
[ ] Password validation
[ ] Profile image upload
[ ] Invalid image extension
[ ] Login using email
[ ] Login using enrollment number
[ ] Invalid credentials
[ ] Session creation
[ ] Protected dashboard
[ ] Logout
[ ] Student data display
[ ] Examination form
[ ] Semester selection
[ ] Subject loading
[ ] Exam type selection
[ ] Subject selection
[ ] Examination submission
[ ] Examination confirmation
[ ] Latest exam form retrieval
[ ] Print functionality
[ ] PDF generation
[ ] Admit-card workflow
[ ] Password management
[ ] Mobile responsiveness
[ ] Desktop responsiveness
[ ] Database connection
```

---

# 📸 Screenshots

Create a `screenshots` directory:

```text
screenshots/
├── home.png
├── register.png
├── login.png
├── dashboard.png
├── student-data.png
├── examination-form.png
├── confirmation.png
└── admit-card.png
```

Then add them to this README.

---

## 🏠 Home Page

![Home Page](screenshots/home.png)

---

## 📝 Student Registration

![Student Registration](screenshots/register.png)

---

## 🔐 Student Login

![Student Login](screenshots/login.png)

---

## 📊 Student Dashboard

![Student Dashboard](screenshots/dashboard.png)

---

## 👤 Student Data

![Student Data](screenshots/student-data.png)

---

## 📝 Examination Form

![Examination Form](screenshots/examination-form.png)

---

## 📄 Examination Confirmation

![Examination Confirmation](screenshots/confirmation.png)

---

## 🎫 Admit Card

![Admit Card](screenshots/admit-card.png)

---

# 🎯 Project Objectives

The project demonstrates practical knowledge of:

- PHP backend development
- MySQL database integration
- PDO
- Authentication
- PHP sessions
- Password hashing
- Form validation
- File upload handling
- Database-driven applications
- Dynamic frontend interactions
- Semester-wise subject management
- Examination form processing
- PDF generation
- Browser print workflows
- Responsive UI development

---

# 🧠 Learning Outcomes

Through this project, the following concepts were implemented:

### Backend

```text
PHP
HTTP POST requests
Sessions
Authentication
Database queries
Prepared statements
Form processing
File uploads
```

### Database

```text
MySQL
PDO
Student records
Examination records
JSON data storage
Record retrieval
```

### Frontend

```text
HTML
CSS
Bootstrap
JavaScript
Dynamic DOM manipulation
Responsive design
```

### Document Generation

```text
Browser Print API
html2canvas
jsPDF
```

---

# 📈 Future Improvements

The project can be extended into a complete academic management platform.

## 👨‍💼 Admin Panel

Add:

- Admin authentication
- Student management
- Student search
- Student editing
- Student deletion
- Subject management
- Semester management
- Course management
- Examination management

---

## 📚 Academic Management

Add:

- Branch management
- Course management
- Semester management
- Subject CRUD
- Subject prerequisites
- Theory/practical categorization

---

## 📝 Examination Management

Add:

- Examination scheduling
- Exam fee management
- Application approval
- Form editing
- Form rejection
- Form verification
- Examination status
- Admit-card approval

---

## 🎓 Result Management

Future versions can include:

- Marks entry
- Grade calculation
- Result publishing
- Grade-card generation
- Transcript generation
- Result history
- CGPA calculation

---

## 🔔 Notifications

Add:

- Email notifications
- SMS integration
- Examination reminders
- Result notifications
- Application status notifications

---

## 🔐 Advanced Security

Add:

- Two-factor authentication
- OTP verification
- CSRF protection
- Rate limiting
- Security logs
- Role-based access control
- Account lockout
- Secure file storage

---

# 🌐 Deployment

The application requires a PHP-compatible hosting environment.

### Server Requirements

```text
Apache / compatible web server
PHP
MySQL
HTTPS
```

Possible deployment environments:

```text
Shared PHP Hosting
VPS
Cloud VM
Docker
```

Before deployment:

```text
1. Configure production database
2. Secure database credentials
3. Enable HTTPS
4. Disable development errors
5. Configure file permissions
6. Validate file uploads
7. Enable backups
8. Test authentication
9. Test examination submission
10. Test PDF generation
```

---

# 📌 Project Status

```text
Status: Academic / Portfolio Project
```

### Current Implementation

```text
✅ Student Registration
✅ Student Login
✅ Session Authentication
✅ Student Dashboard
✅ Student Data
✅ Profile Image Upload
✅ Semester Selection
✅ Examination Type Selection
✅ Subject Selection
✅ Examination Form Submission
✅ Examination Confirmation
✅ Print Functionality
✅ PDF Download
✅ Admit Card Workflow
```

### Planned

```text
🔲 Complete Admin Panel
🔲 Advanced Role Management
🔲 Result Management
🔲 Grade Card Generation
🔲 OTP Authentication
🔲 Email Notifications
🔲 Advanced Security
🔲 Production Deployment
```

---

# ⚠️ Disclaimer

This is an **independent academic/personal software project** created for learning, demonstration, and portfolio purposes.

The project may use university-style terminology, interface design, logos, or publicly available campus imagery for demonstration.

It should **not be represented as an officially deployed university system or an officially affiliated application without explicit authorization**.

---

# 👨‍💻 Developer

## Zakir Hussain

**Computer Engineering | Software Developer**

### GitHub

https://github.com/Ahan-07

### LinkedIn

YOUR_LINKEDIN_URL

### Portfolio

YOUR_PORTFOLIO_URL

### Email

YOUR_EMAIL

---

# ⭐ Support

If you found this project useful or interesting, consider giving the repository a ⭐.

---

# 📄 License

This project is intended for educational and portfolio purposes.

If you want others to freely use, modify, and distribute the source code, add an appropriate open-source license such as the MIT License.

---

## 🙌 Acknowledgements

Built using:

- PHP
- MySQL
- Bootstrap
- JavaScript
- Font Awesome
- Animate.css
- html2canvas
- jsPDF

---

**Made with PHP, MySQL, JavaScript and an unreasonable number of forms. 😄**
