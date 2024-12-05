<?php
// utils/AuthMiddleware.php
class AuthMiddleware
{
    public static function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                header('Location: ' . BASE_URL . '/login');
                exit;
            } else {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No autorizado'
                ]);
                exit;
            }
        }
        return true;
    }

}