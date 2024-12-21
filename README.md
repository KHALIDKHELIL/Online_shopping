# Shopping Website

A dynamic and user-friendly shopping platform built with PHP, featuring comprehensive user and admin functionalities. This project is designed to streamline shopping experiences while providing robust management and reporting tools for administrators.

---

## Features

### User Features
- **User Registration & Login:** Secure login system with password encryption.
- **Profile Management:** Edit user details with password verification.
- **Shopping Cart:** Add, update, and remove items.
- **Order Management:** View and manage previous orders.

### Admin Features
- **Admin Dashboard:** Centralized control for managing products, categories, and sales.
- **Sales Reporting:** Generate daily and monthly reports with insightful statistics.
- **Product Management:** Add, update, or delete products with stock and category management.
- **Secure Admin Access:** Only authorized admins can log in.

---

## Tech Stack

- **Backend:** PHP 8.4
- **Frontend:** HTML5, CSS3, JavaScript (with responsive design).
- **Database:** MySQL
- **Server:** WampServer on port 7880.

---

## Database Structure

### Key Tables
1. **Products Table**
   - Columns: `Id`, `ProductCatID`, `Prodsubcatid`, `Productname`, `productImage`, `ProductDetail`, `AdminID`.

2. **Categories Table**
   - Columns: `Id`, `categoryname`.

3. **Payments Table**
   - Columns: `PayId`, `PUserid`, `POrderId`, `amount`, `Currency`, `OrderStatus`.

4. **Order Details Table**
   - Columns: `OrderDId`, `Orderid`, `Productid`, `Product_Name`, `Quantity`, `productPrice`.

5. **Admin Table**
   - Used for authenticating admin users.

---

## Installation

1. **Clone the Repository**
   
   git clone https://github.com/khalidkhelil/online_shopping.git
2. **Set Up the Environment
        Install PHP (8.4 or higher) and MySQL.
        Configure WampServer to run on port 7880.

3. **Database Configuration
        Import the database.sql file included in the repository.
        Update database credentials in config.php.

4. **Start the Server
        Place the project in the WampServer www directory.
        Access the site at http://localhost:7880/Final_project/login/.

##Future Enhancements

    Add product reviews and ratings.
    Implement advanced search with filters.
    Develop an integrated payment gateway.
    Optimize performance for large-scale use.

#Contribution

Contributions are welcome! Please fork the repository and submit a pull request with a detailed explanation of your changes.
License

This project is licensed under the MIT License.
Contact

For questions or collaboration:

    Name: Khalid
    Email: [khalidkhelil19.com]
    GitHub: khalidkhelil
