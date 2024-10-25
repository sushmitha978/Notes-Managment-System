# Notes Management System

Welcome to the **Notes Management System**, a simple yet powerful web application built with PHP and MySQL. This application allows users to create, update, delete, and manage their notes efficiently.

## Table of Contents

- Features
- Technologies Used
- Installation
- Usage
- Configuration
- License

## Features

- **User Authentication**: Secure login to manage personal notes.
- **CRUD Operations**: Create, Read, Update, and Delete notes seamlessly.
- **Rich Text Editing**: Integrated with Quill.js for rich text formatting.
- **Error Handling**: User-friendly error messages to enhance usability.
- **Logging**: Detailed logging for operations performed by users.

## Technologies Used

- **PHP**: Server-side scripting language.
- **MySQL**: Database management system for storing notes.
- **HTML/CSS**: Frontend structure and styling.
- **Bootstrap**: Responsive design framework.
- **Quill.js**: Rich text editor for creating formatted notes.

## Installation

Follow these steps to set up the Notes Management System on your local machine:

1. **Clone the Repository**:

   git clone https://github.com/sushmitha978/Notes-Managment-System.git
   cd notes-management-system

2. **Set Up Database**:

   - Create a new MySQL database (e.g., `notes_db`).
   - Import the given database file into your MySQL.

3. **Update Configuration**:

   - Edit `system/config.php` to set your database connection details:

4. **Run the Application**:

   - Start a local PHP server and first create a new account by registering and done!

## Usage

1. **Login**: Access the application by logging in. If you don’t have an account, please create one.
2. **Manage Notes**:
   - Create a new note by entering a title and content.
   - Edit existing notes using the edit button.
   - Delete notes using the delete button.

## Configuration

Before running the application, ensure you have the following configuration in place:

- **PHP 7.2 or higher**: Ensure your server is running a compatible version of PHP.
- **MySQL Database**: Ensure MySQL is installed and running on your system.

## License

This project is licensed under the MIT License. See the LICENSE file for details.
