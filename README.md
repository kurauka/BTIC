# Bandari Tech & Innovation Club (BTIC) Website

Welcome to the official repository for the **Bandari Tech & Innovation Club** website. This platform serves as the digital hub for the club, showcasing projects, events, programs, and the team driving innovation at Bandari Maritime Academy.

## 🚀 Features

-   **Dynamic Homepage**:
    -   **Projects**: Showcases recent student projects fetched from the database.
    -   **Events**: Displays upcoming events with dates and details.
    -   **Programs**: Lists the core activities and workshops offered.
    -   **Team**: "Meet the Organizers" section highlighting the leadership team.
    -   **Partners**: Section displaying industry partners and sponsors.
    -   **Stats**: Real-time statistics (Students Trained, Projects, etc.).

-   **Comprehensive Admin Panel**:
    -   **Dashboard**: Overview of key metrics (Total Projects, Active Programs, etc.).
    -   **Content Management**: CRUD (Create, Read, Update, Delete) interfaces for:
        -   Projects
        -   Events
        -   Programs
        -   Organizers
        -   Partners
    -   **Message Center**: View inquiries submitted via the contact form.
    -   **Security**: Secure admin authentication and password hashing.

## 🛠️ Tech Stack

-   **Frontend**: HTML5, CSS3 (Custom Variables & Glassmorphism), JavaScript (GSAP for animations).
-   **Backend**: PHP (PDO for database interactions).
-   **Database**: MySQL.
-   **Icons**: Remix Icon.

## ⚙️ Installation & Setup

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/kurauka/BTIC.git
    cd BTIC
    ```

2.  **Database Setup**:
    -   Create a new MySQL database named `bandari_tech_club`.
    -   Import the provided `database.sql` file to set up the tables and initial data.
    -   Command line example:
        ```bash
        mysql -u root -p bandari_tech_club < database.sql
        ```

3.  **Configuration**:
    -   Verify the database credentials in `db_connect.php`.
    -   Default configuration:
        ```php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bandari_tech_club";
        ```

4.  **Run the Project**:
    -   Place the project folder in your web server's root directory (e.g., `/var/www/html/` for Apache).
    -   Access the site via `http://localhost/BTIC/`.

## 🔐 Admin Access

To access the admin panel, navigate to `/admin/`.

-   **Default Username**: `admin`
-   **Default Password**: `password`

> **Note**: Please change the password immediately after your first login via the **Settings** page.

## 🤝 Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.

---
*Powered by Students of Bandari Maritime Academy.*
