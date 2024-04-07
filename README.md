# Inventory Management System

## Description

Inventory Management System is a full-stack application developed using Laravel and Vue.js. The system allows users to manage their inventory effectively by providing features such as inventory creation, item addition, deletion, and update. The interface is designed to be user-friendly and responsive, ensuring seamless inventory management.

## Prerequisites

Before running Inventory Management System locally, make sure you have the following prerequisites installed:

- PHP
- Composer
- Node.js
- npm
- MySQL

## Setup

Follow these steps to set up the Inventory Management System locally:

1. Clone the repository:

   ```
   git clone <repository-url>
   ```
2. Navigate into the server directory:

   ```
   cd server
   ```
3. Install the PHP dependencies:

   ```
   composer update
   ```
4. Edit the database variables in the `.env` file.
5. Run the database migrations:

   ```
   php artisan migrate
   ```
6. Start the PHP server:

   ```
   php artisan serve
   ```
7. In a new terminal window, navigate into the client directory:

   ```
   cd client
   ```
8. Install the Node.js dependencies:

   ```
   npm install
   ```
9. Start the development server:

   ```
   npm run dev
   ```

Now, you should be able to access the Inventory Management System in your web browser.

## Features

- User-friendly interface for managing inventory, including item addition, deletion, and update
- Secure authentication system with JWT for secure login and access control
- Responsive design for optimal user experience across devices
- Integration with Laravel backend and Vue.js frontend for seamless interaction

## Technologies Used

- **Backend:**

  - PHP
  - Laravel
  - MySQL
- **Frontend:**

  - JavaScript
  - HTML/CSS
  - Vue.js
  - Tailwind
- **Authentication:**

  - Laravel Sanctum(token-based) for secure authentication
- **Development Tools:**

  - Composer (PHP package manager)
  - npm (Node Package Manager)
  - Git

## API Documentation

Explore the Inventory Management System API using the [Postman API documentation](https://documenter.getpostman.com/view/23180955/2sA35MxdX4).

## Live Link

The live version of the application is hosted on [Vercel](https://vercel.com). Additionally the backend is hosted on [000webhost](https://www.000webhost.com) and the Mysql Database is on [Aiven](https://aiven.io)

Access the live version of the application at [https://inventory-management-minimal.vercel.app](https://inventory-management-minimal.vercel.app)

To access the application, use the following demo credentials:

- Email: arif@gmail.com
- Password: arifPass

## Contributing

We welcome contributions from the community! If you'd like to contribute to this project, please follow these guidelines:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes.
4. Test your changes thoroughly.
5. Commit your changes.
6. Push to your branch.
7. Submit a pull request.

We appreciate your contributions!
