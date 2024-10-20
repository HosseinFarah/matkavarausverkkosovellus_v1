# Tour Management System

## Overview

This system is a comprehensive web application designed to facilitate user authentication, tour bookings, payments, reviews, and management. It provides three user access levels (Admin, Tour Guide, and User) and supports multilingual content, online payments, and user feedback.

## Features

### 1. User Authentication and Security
- User registration and login.
- Password recovery via email for forgotten passwords.
- Session timeout: users are logged out after 10 minutes of inactivity.

### 2. Multilingual Support
- Full multilingual site with static and dynamic content translation based on user-selected language.

### 3. User Access Levels
- **Admin**: Complete control over the system, including user and tour management.
- **Tour Guide**: Access to assigned tours and buyer details.
- **User**: Can book tours, submit reviews, and manage personal profile.

### 4. Tour Booking and Payment
- Integrated with **Stripe** for secure online payments.
- Automatic disabling of the payment button when tour registration is full or the tour date has passed.
- Invoice and transaction details sent via email after a successful purchase.
- PDF invoice generation using the **FPDF** library.

### 5. Tour Reviews and Ratings
- Registered users can submit reviews and ratings for booked tours.
- Visitors can view all reviews and the average rating for each tour.
- Users can update their reviews, and the system sends a notification email with the submitted review.

### 6. Interactive Map Display
- Tour locations are displayed using **Mapbox**, providing an interactive and detailed map view.

### 7. Admin Dashboard
- View and manage all users, update their profiles, reset passwords, and change access levels (User, Guide, Admin).
- Manage all tours (create, update, or delete tours).
- Manage user reviews and remove inappropriate reviews if necessary.
- View all reservations and transaction details for each tour.
- Translate tour details into multiple languages.

### 8. Profile Management
- Users can update their personal information and change their password after verifying the current one.
- Purchased tours and submitted reviews are available in the user profile, with the option to download invoices in PDF format.

### 9. Additional Features
- Google login enabled for quick access.
- After 7 incorrect login attempts, the login system is disabled for 1 minute with a live countdown displayed to the user.
- Contact form with server and client-side validation, sending email notifications to both the admin and the user.
  
## Technology Stack

- **PHP**: Backend development and server-side processing.
- **MySQL**: Database for storing user, tour, review, and transaction data.
- **Stripe**: Online payment processing.
- **Mapbox**: Interactive map integration for tour locations.
- **FPDF**: PDF generation for invoices.
- **HTML/CSS/JavaScript**: Frontend development.
- **Bootstrap**: Responsive design for mobile-first UI.
- **Multilingual Support**: Static and dynamic content translation.

## Installation

1. Clone the repository:
   ```bash
   git clone git@github.com:HosseinFarah/matkavarausverkkosovellus_v1.git
   cd tour-management-system
   
You can view the live site at https://farah.fi.
   
