<?php
// Include the database connection
require_once 'db_connection.php';

class Database
{
    private $conn;

    public function __construct()
    {
        $databaseConnection = new DatabaseConnection();
        $this->conn = $databaseConnection->getConnection();

        // Check if the connection is successful
        if (!$this->conn) {
            die("❌ Database connection failed: " . mysqli_connect_error());
        }
    }

    public function createTables()
    {
        $queries = [
            // Locations table must be created first because it is referenced by companies and mechanics
            "CREATE TABLE IF NOT EXISTS locations (
                location_id VARCHAR(36) PRIMARY KEY,
                address TEXT NOT NULL,
                latitude DECIMAL(10,8),
                longitude DECIMAL(11,8),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB",

            // Users table (referenced by mechanics, bookings, appointments, and support_tickets)
            "CREATE TABLE IF NOT EXISTS users (
                user_id VARCHAR(36) PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                phone_number VARCHAR(20),
                user_type ENUM('customer', 'mechanic', 'admin') NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB",

            // Specializations table (referenced by mechanics)
            "CREATE TABLE IF NOT EXISTS specializations (
                specialization_id VARCHAR(36) PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB",

            // Companies table (references location_id)
            "CREATE TABLE IF NOT EXISTS companies (
                company_id VARCHAR(36) PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                location_id VARCHAR(36),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (location_id) REFERENCES locations(location_id) ON DELETE SET NULL
            ) ENGINE=InnoDB",

            // Mechanics table (references users, companies, specializations, locations)
            "CREATE TABLE IF NOT EXISTS mechanics (
                mechanic_id VARCHAR(36) PRIMARY KEY,
                user_id VARCHAR(36) NOT NULL,
                company_id VARCHAR(36),
                experience_years INT,
                availability_status VARCHAR(20),
                rating DECIMAL(3,2),
                specialization_id VARCHAR(36),
                location_id VARCHAR(36),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                FOREIGN KEY (company_id) REFERENCES companies(company_id) ON DELETE SET NULL,
                FOREIGN KEY (specialization_id) REFERENCES specializations(specialization_id) ON DELETE SET NULL,
                FOREIGN KEY (location_id) REFERENCES locations(location_id) ON DELETE SET NULL
            ) ENGINE=InnoDB",

            // Services table (references mechanic_id)
            "CREATE TABLE IF NOT EXISTS services (
                service_id VARCHAR(36) PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                price DECIMAL(10,2) NOT NULL,
                category VARCHAR(50),
                estimated_time INT,
                mechanic_id VARCHAR(36) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (mechanic_id) REFERENCES mechanics(mechanic_id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            // Service add-ons table (references service_id)
            "CREATE TABLE IF NOT EXISTS service_add_ons (
                add_on_id VARCHAR(36) PRIMARY KEY,
                service_id VARCHAR(36) NOT NULL,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            // Schedule table (references mechanic_id)
            "CREATE TABLE IF NOT EXISTS schedule (
                schedule_id VARCHAR(36) PRIMARY KEY,
                mechanic_id VARCHAR(36) NOT NULL,
                available_date DATE NOT NULL,
                available_time TIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (mechanic_id) REFERENCES mechanics(mechanic_id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            // Bookings table (references customer_id, service_id, mechanic_id)
            "CREATE TABLE IF NOT EXISTS bookings (
                booking_id VARCHAR(36) PRIMARY KEY,
                customer_id VARCHAR(36) NOT NULL,
                service_id VARCHAR(36) NOT NULL,
                mechanic_id VARCHAR(36) NOT NULL,
                appointment_date DATE NOT NULL,
                status VARCHAR(20),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES users(user_id) ON DELETE CASCADE,
                FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE,
                FOREIGN KEY (mechanic_id) REFERENCES mechanics(mechanic_id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            // Appointments table (references user_id, mechanic_id, schedule_id, service_id, location_id)
            "CREATE TABLE IF NOT EXISTS appointments (
                appointment_id VARCHAR(36) PRIMARY KEY,
                user_id VARCHAR(36) NOT NULL,
                mechanic_id VARCHAR(36) NOT NULL,
                schedule_id VARCHAR(36) NOT NULL,
                service_id VARCHAR(36) NOT NULL,
                location_id VARCHAR(36),
                status VARCHAR(20),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                FOREIGN KEY (mechanic_id) REFERENCES mechanics(mechanic_id) ON DELETE CASCADE,
                FOREIGN KEY (schedule_id) REFERENCES schedule(schedule_id) ON DELETE CASCADE,
                FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE,
                FOREIGN KEY (location_id) REFERENCES locations(location_id) ON DELETE SET NULL
            ) ENGINE=InnoDB",

            // Payments table (references booking_id)
            "CREATE TABLE IF NOT EXISTS payments (
                payment_id VARCHAR(36) PRIMARY KEY,
                booking_id VARCHAR(36) NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                payment_status VARCHAR(20),
                transaction_date DATE NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            // Support tickets table (references user_id)
            "CREATE TABLE IF NOT EXISTS support_tickets (
                ticket_id VARCHAR(36) PRIMARY KEY,
                user_id VARCHAR(36) NOT NULL,
                message TEXT,
                status VARCHAR(20),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
            ) ENGINE=InnoDB",

            // Profile pictures table (references user_id)
            "CREATE TABLE IF NOT EXISTS profile_pictures (
                profile_picture_id VARCHAR(36) PRIMARY KEY,
                user_id VARCHAR(36) NOT NULL,
                image_url TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
            ) ENGINE=InnoDB"
        ];

        foreach ($queries as $query) {
            if (!$this->conn->query($query)) {
                echo "❌ Table creation failed: " . $this->conn->error . "<br>";
            } else {
                echo "✅ Table created successfully.<br>";
            }
        }
    }

    public function close()
    {
       
        $this->conn->close();
    }
}

// Example usage:
$database = new Database();
$database->createTables();
$database->close();
?>
