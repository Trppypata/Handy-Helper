<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$request = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Database connection
require_once '../config/db.php';
$database = new Database();
$db = $database->getConnection();

// Controllers
require_once '../controllers/UserController.php';
require_once '../controllers/ServiceController.php';
require_once '../controllers/BookingController.php';
require_once '../controllers/AppointmentController.php';
require_once '../controllers/PaymentController.php';
require_once '../controllers/SupportTicketController.php';
require_once '../controllers/LocationController.php';
require_once '../controllers/CompanyController.php';
require_once '../controllers/ProfilePictureController.php';
require_once '../controllers/SpecializationController.php';
require_once '../controllers/MechanicController.php';

$userController = new UserController($db);
$serviceController = new ServiceController($db);
$bookingController = new BookingController($db);
$appointmentController = new AppointmentController($db);
$paymentController = new PaymentController($db);
$supportController = new SupportTicketController($db);
$locationController = new LocationController($db);
$companyController = new CompanyController($db);
$profilePictureController = new ProfilePictureController($db);
$specializationController = new SpecializationController($db);
$mechanicController = new MechanicController($db);

// Route handling
switch($path) {
    // User routes
    case '/api/users/register':
        if($request == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($userController->register($data));
        }
        break;

    case '/api/users':
        switch($request) {
            case 'GET':
                echo json_encode($userController->getUsers());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($userController->register($data));
                break;
        }
        break;

    case (preg_match('/^\/api\/users\/(\w+)$/', $path, $matches) ? true : false):
        $userId = $matches[1];
        switch($request) {
            case 'GET':
                echo json_encode($userController->getUser($userId));
                break;
            case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($userController->updateUser($userId, $data));
                break;
            case 'DELETE':
                echo json_encode($userController->deleteUser($userId));
                break;
        }
        break;

    // Service routes
    case '/api/services':
        switch($request) {
            case 'GET':
                echo json_encode($serviceController->getServices());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($serviceController->createService($data));
                break;
        }
        break;

    // Booking routes
    case '/api/bookings':
        switch($request) {
            case 'GET':
                echo json_encode($bookingController->getBookings());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($bookingController->createBooking($data));
                break;
        }
        break;

    // Appointment routes
    case '/api/appointments':
        switch($request) {
            case 'GET':
                $id = $_GET['id'] ?? null;
                echo json_encode($appointmentController->getAppointment($id));
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($appointmentController->createAppointment($data));
                break;
        }
        break;

    // Payment routes
    case '/api/payments':
        switch($request) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($paymentController->processPayment($data));
                break;
            case 'GET':
                $booking_id = $_GET['booking_id'] ?? null;
                echo json_encode($paymentController->getPaymentHistory($booking_id));
                break;
        }
        break;

    // Support ticket routes
    case '/api/support-tickets':
        switch($request) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($supportController->createTicket($data));
                break;
            case 'PUT':
                $ticket_id = $_GET['ticket_id'] ?? null;
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($supportController->updateTicketStatus($ticket_id, $data['status']));
                break;
        }
        break;

    // Location routes
    case '/api/locations':
        switch($request) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($locationController->addLocation($data));
                break;
            case 'GET':
                $lat = $_GET['lat'] ?? null;
                $lng = $_GET['lng'] ?? null;
                $radius = $_GET['radius'] ?? 10;
                echo json_encode($locationController->findNearbyMechanics($lat, $lng, $radius));
                break;
        }
        break;

    // Company routes
    case '/api/companies':
        switch($request) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($companyController->createCompany($data));
                break;
        }
        break;

    // Profile picture routes
    case '/api/profile-pictures':
        switch($request) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($profilePictureController->uploadProfilePicture($data));
                break;
            case 'PUT':
                $user_id = $_GET['user_id'] ?? null;
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($profilePictureController->updateProfilePicture($user_id, $data));
                break;
        }
        break;

    // Specialization routes
    case '/api/specializations':
        switch($request) {
            case 'GET':
                echo json_encode($specializationController->getAllSpecializations());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($specializationController->createSpecialization($data));
                break;
        }
        break;

    // Mechanic routes
    case '/api/mechanics':
        switch($request) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($mechanicController->registerMechanic($data));
                break;
            case 'GET':
                $service_id = $_GET['service_id'] ?? null;
                echo json_encode($mechanicController->getAvailableMechanics($service_id));
                break;
        }
        break;

    // Add routes for other entities here
}
?>
