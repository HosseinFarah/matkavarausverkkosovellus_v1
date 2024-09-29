# Tour Guide Reservation System

This is a comprehensive web application for managing and reserving tour guides for various tours. It features user authentication, tour management, guide assignment, reservation and payment systems, user reviews, and administrative functions.

## Features

### 1. User Authentication
- **Account Registration & Login**: Users can register, log in, and reset their passwords.
- **Password Reset**: The system emails a reset link when requested.
  
### 2. Tour Management
- **Database-Driven Tours**: Tours are dynamically generated from a MySQL database.
- **Map Integration**: Tour locations are shown using Mapbox for better user experience.
- **Availability Control**: Tours are automatically marked unavailable if fully booked or if the date has passed.

### 3. Tour Reservation and Payment
- **Stripe Payment Gateway**: Users can pay for tours using Stripe.
- **Reservation Invoice**: Upon successful booking, users receive a reservation invoice by email.
- **Tour Date Validation**: Only tours within the current available dates can be reserved.

### 4. User Reviews
- **Submit & Update Reviews**: Users who book tours can write and update reviews.
- **One Review Per User Per Tour**: A user can only review a tour once.
- **Email Confirmation**: After review submission, users receive a confirmation email.

### 5. User Profile
- **Personal Information Management**: Users can update their profile details and password.
- **View Reservations & Reviews**: Users can view their tour reservations (with reservation date and transaction ID) and reviews.
  
### 6. Guide Role
- **Tour Access**: Guides can view the tours they are assigned to.
- **Participant Overview**: Guides can see the users registered for their assigned tours.

### 7. Admin Role
- **User Management**: Administrators can activate/deactivate, view, update, and delete users.
- **Tour Management**: Admins can add, update, delete tours and assign guides to tours.
- **Review & Booking Management**: Admins can view and delete all user reviews and bookings.
- **Search & Filter**: Admins can search users, bookings, and filter users by roles.
  
### 8. Form Validation
- **Frontend & Server-Side Validation**: Ensures data integrity and prevents invalid submissions.

## Installation

1. **Clone the Repository**:
   ```bash
   git clone git@github.com:HosseinFarah/matkavarausverkkosovellus_v1.git
