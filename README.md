# Form Builder Application

A comprehensive form builder application built with **Laravel** (backend) and **Vue.js** (frontend) that allows users to create, manage, preview, and track form submissions.

---

## Features

### Form Management
-  Create forms with custom titles
-  Add multiple field types (text, textarea, checkbox, radio, select)
-  Configure field options and validation rules
-  Drag-and-drop field reordering
-  Edit and delete existing forms

### Form Preview & Submission
-  Preview forms exactly as users will see them
-  User-friendly form submission interface
-  Validation for required fields
-  Support for various field types

### Submission Management
-  View all submissions across all forms
-  Filter submissions by form
-  Detailed submission viewing with all field responses
-  Search functionality across submissions

### User Interface
-  Responsive design with Bootstrap
-  Intuitive navigation
-  Dashboard with overview statistics
-  Clean, modern interface

---

#### User Interface Design
---
Here are some screenshots of the application:

### ***Dashboard***
<img width="1365" height="619" alt="image" src="https://github.com/user-attachments/assets/69fb8470-8da9-42a5-a765-a4e02ed94759" />

### ***Create Form***
<img width="1361" height="590" alt="image" src="https://github.com/user-attachments/assets/dae173d8-8fa5-499d-be7d-8f44b694de1b" />

### ***Form List***
<img width="1366" height="578" alt="image" src="https://github.com/user-attachments/assets/8ee45fdb-3b3d-4c66-adc6-31932e43c5c9" />

### ***Edit Form***
<img width="1347" height="627" alt="image" src="https://github.com/user-attachments/assets/c59c37fa-db92-4065-9a1a-1a4712dc52da" />

### ***Form Preview***
<img width="1366" height="633" alt="image" src="https://github.com/user-attachments/assets/137dcff6-862c-45d2-b0f5-afd28aaea14d" />

### ***Form Submissions***
<img width="1346" height="632" alt="image" src="https://github.com/user-attachments/assets/fc4d49ab-45a7-42a7-a782-c616d0836c4e" />

#### Submission Details 
<p float="left">
  <img src="https://github.com/user-attachments/assets/bc34b916-1683-4ad0-9ef4-9fe42e199824" width="45%" />
  <img src="https://github.com/user-attachments/assets/b5c0bdaf-61b5-4be4-b4ba-f2dbaf675862" width="45%" />
</p>


### ***Alert Management***
<img width="1359" height="628" alt="image" src="https://github.com/user-attachments/assets/5899bb59-145d-423d-93ba-41d4c6057a76" />



---

## Technology Stack

**Backend**
- Laravel 9+ - PHP framework
- MySQL - Database
- RESTful API - JSON API endpoints

**Frontend**
- Vue.js 3 - Progressive JavaScript framework
- Vue Router - Client-side routing
- Axios - HTTP client for API requests
- Bootstrap 5 - UI framework
- Vite - Build tool and development server

---

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 16 or higher
- MySQL 5.7 or higher

### Step-by-Step Installation

1. **Clone the repository**
```bash
git clone https://github.com/OshadiPalliyaguruge/Form-Builder.git
cd form-builder
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies**
```bash
npm install
```
4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
Edit .env file with your database credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=form_builder
DB_USERNAME=root
DB_PASSWORD=
```
6. **Run migrations**
```bash
php artisan migrate
```

7. **Build assets**
```bash
npm run build
```

8. **Start the development server**
```bash
php artisan serve
```

---

#### ***Access the application*** - http://localhost:8000

---

## Usage

### Creating Forms
- Navigate to "Create Form" from the main menu
- Enter a form title
- Add fields using the field type buttons
- Configure each field (label, options, required status)
- Reorder fields using drag-and-drop
- Save the form

### Previewing Forms
- Go to "Forms" to see all created forms
- Click "Preview" next to any form
- The form will open in preview mode exactly as users will see it

### Managing Submissions
- View all submissions from the "Submissions" page
- Filter submissions by form using the sidebar
- Click on any submission to view detailed responses
- Use the search box to find specific submissions

---

## API Endpoints

### Forms
- `GET /api/forms` - List all forms
- `POST /api/forms` - Create a new form
- `GET /api/forms/{id}` - Get a specific form
- `PUT /api/forms/{id}` - Update a form
- `DELETE /api/forms/{id}` - Delete a form

### Submissions
- `POST /api/forms/{id}/submissions` - Submit form responses
- `GET /api/forms/{id}/submissions` - Get submissions for a form
- `GET /api/submissions` - Get all submissions across forms
- `GET /api/submissions/{id}` - Get a specific submission
