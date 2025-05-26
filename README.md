## Judge Scoring System

## Project Description

LAMP Stack Implementation: Your solution must be built entirely on a LAMP stack. Please demonstrate your understanding of how these components work together. You are free to use a local development environment (e.g., XAMPP, WAMP, Dockerized LAMP).
Admin Panel for Judge Management:
Create an administrator interface (login not required for this task, but consider how it might be secured in a real application).
Admins should be able to add new "Judges" to the system. Each judge should have a unique identifier (e.g., username/ID) and display name.
Judge Portal & Scoring:
Create a separate interface for Judges (login not required for this task, but consider the implications).
Judges should be able to view a list of all participating "Users" (event participants). For simplicity, you can assume these users are pre-registered or manually added for the purpose of this demo.
Judges must be able to select an individual User and assign points to them. Points should be numerical (e.g., 1-100).
Public Scoreboard Display:
Create a public-facing scoreboard page.
This scoreboard should display all Users and their total accumulated points.
The scoreboard must be dynamically updated (e.g., auto-refreshing every few seconds/minutes, or demonstrate how it would update if new scores were added).
The scoreboard should highlight all users based on their total points.
The scoreboard should be sorted in descending order of total points.
  
## Author

This project was developed by 
- Jeff Mwaura


ctfroom Thank you and do enjoy.


## Project Setup Instructions

To set up and run the Judging Scoring System project locally, follow these steps:
- **Cloning** Clone the repository using the following command:
```
git clone https://github.com/Mbogz/judge-compe.git
```
1. Navigate to the project directory: `cd judge-compe`
2. Install dependencies:
This project uses:
- PHP 8.1.2-1ubuntu2.21 (cli) 
- MySQL
- Apache
- (Optional) XAMPP
3.Import the Database Schema
  - Open phpMyAdmin
- Create a new database, e.g. judging_system
- `mysql -u root -p judging_system < schema.sql`
4. Configure Database Connection: `$mysqli = new mysqli("localhost", "root", "", "judging_system");`
5. Run the Project
  - Place the project folder inside your web server’s root directory (e.g., htdocs for XAMPP).
- Visit: http://localhost/judging-system/ in your browser.


- **Database Schema**  
`-- Judges Table
CREATE TABLE judges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL
);

-- Users (participants) Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Scores Table
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judge_id INT,
    user_id INT,
    points INT CHECK (points BETWEEN 1 AND 100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (judge_id) REFERENCES judges(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);`

##Assumptions
- Judges have unique usernames.
- User names are not enforced to be unique, assuming participants might have identical names.
- No authentication system is implemented — all judge and user management is done via open forms for simplicity.
- Deleting all judges also removes associated scores.


## Design Choices
- Separate Judges, Users, and Scores Tables: Ensures normalized data structure and avoids duplication.
- Foreign Keys: Enforced referential integrity between judges, users, and scores.
- Prepared Statements (PHP prepare and bind_param): Mitigates SQL injection and ensures type safety.
- Sanitization via htmlspecialchars and trim: Protects against XSS when handling form inputs.
- Error Reporting: Enabled for easier debugging during development.
- Clean, Consistent CSS Styling: Keeps UI modern, simple, and user-friendly without third-party CSS frameworks.





##  Potential Features to Add (if time allowed)
- Judge login with password authentication.
- View existing judges and users in sortable tables because as of now they can only be seen in the terminal.
- Soft delete (using is_active flags) instead of permanent TRUNCATE to preserve historical data.
- Add pagination for managing large lists of judges, users, or scores.
-  Responsive design adjustments for mobile and tablet views.



## Copyright and License

Judging Scoring System is licensed under the MIT License. 
You are free to use, modify, and distribute the code as long as you include the appropriate copyright notice and adhere to the terms of the MIT License.

> Note: Data used in the application is for demonstration purposes *only* and should not be used for any Judge scoring system.

GOD Bless You. Happy Coding!!🤗
