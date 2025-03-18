<?php

require_once __DIR__ . '/../config/dbConfig.php';
require_once __DIR__ . '/../models/User.php';

session_start();

class AuthController
{

    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Register a new user
    public function register()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->name) || !isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required fields."));
            return;
        }

        $user = new User($this->db);

        if ($user->getUserByEmail($data->email)->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(array("message" => "Email already exists."));
            return;
        }

        $stmt = $user->createUser($data->name, $data->email, $data->password, $data->avatar);

        if ($stmt->rowCount() > 0) {

            $otp = OtpHelper::generateOtp();
            OtpHelper::storeOtp($data->email, $otp);

            EmailHelper::sendEmail($data->email, $otp);

            http_response_code(201);
            echo json_encode(array("message" => "User created successfully. Please check your email for verification."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to create user."));
        }
    }

    // Login an existing user
    public function login()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required fields."));
            return;
        }

        $user = new User($this->db);
        $stmt = $user->getUserByEmail($data->email);

        if ($stmt->rowCount() > 0) {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($data->password, $user_data['password'])) {

                $token = JWTHelper::createToken(array(
                    'id' => $user_data['id'],
                    'email' => $user_data['email'],
                    'name' => $user_data['name'],
                    'avatar' => $user_data['avatar']
                ));

                http_response_code(200);
                echo json_encode(array("message" => "Login successful.", "token" => $token));
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Invalid credentials."));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "User not found."));
        }
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Validate login data.
     *
     * @param array $data The login data
     *
     * @return bool True if the data is valid, false otherwise
     */
/******  d914277d-8339-4ef5-af64-f940c61941b6  *******/    }

    // Verify an email
    public function verifyEmail()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->email) || !isset($data->otp)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required fields."));
            return;
        }

        if (OtpHelper::verifyOtp($data->email, $data->otp)) {
            if ($data->type === 'register') {
                $user = new User($this->db);
                $user->verifyEmail($data->email);
                http_response_code(200);
                echo json_encode(array("message" => "Email verified successfully.", "redirect" => "login"));
            } elseif ($data->type === 'forgot-password') {
                http_response_code(200);
                echo json_encode(array("message" => "Email verified successfully.", "redirect" => "reset-password"));
            }
        }
        else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid OTP."));
        }
    }

    // Forgot password
    public function forgotPassword()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->email)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required fields."));
            return;
        }

        $user = new User($this->db);
        if (!$user->getUserByEmail($data->email)->rowCount() > 0) {
            http_response_code(404);
            echo json_encode(array("message" => "User not found."));
            return;
        }

        $otp = OtpHelper::generateOtp();
        OtpHelper::storeOtp($data->email, $otp);

        EmailHelper::sendEmail($data->email, $otp);

        http_response_code(200);
        echo json_encode(array("message" => "OTP sent successfully."));
    }

    // Reset password
    public function resetPassword()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->email) || !isset($data->newPassword)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required fields."));
            return;
        }

        $user = new User($this->db);
        if ($user->resetPassword($data->email, $data->newPassword)) {
            http_response_code(200);
            echo json_encode(array("message" => "Password reset successful."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to reset password."));
        }
    }
}
