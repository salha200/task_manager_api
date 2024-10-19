# Advanced Task Management API System
## Overview:
This project is an advanced API system for managing tasks, including features such as real-time notifications, task dependencies, and advanced security mechanisms. The system supports different types of tasks (Bug, Feature, Improvement), task dependencies, and performance analysis through periodic reports. Security is enhanced with JWT authentication, role-based authorization, and protection against common security threats.
## Features:
- Task management (create, update, delete, reassign tasks)
- Real-time notifications
- Task dependencies
- Task status tracking
- Advanced security mechanisms (JWT authentication, rate limiting, XSS/SQL - - - Injection protection)
- Role-based authorization
- Error handling and performance analysis
## Requirements:
### Models:
- Task:Fields: title, description, type (Bug, Feature, Improvement), status (Open, In Progress, Completed, Blocked), priority (Low, Medium, High), due_date, assigned_to (User ID).
- Attachment:Polymorphic relation to handle file attachments for tasks.
- TaskStatusUpdate:To track changes in task status, has a hasMany relation with Task
- User:Users are assigned tasks and linked to them using a belongsTo relationship.
## installation and Setup
### Prerequisites
PHP >= 8.0
Composer
Laravel >= 9.x
MySQL or another compatible database
