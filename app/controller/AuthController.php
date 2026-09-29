<?php

/**
 * AuthController
 * Handles authentication views and actions
 */
class AuthController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function showLogin()
    {
        $pageTitle = 'Masuk';
        $contentView = __DIR__ . '/../../views/auth/login.php';
        require __DIR__ . '/../../views/layouts/auth.php';
    }

    public function showRegister()
    {
        $pageTitle = 'Daftar Akun';
        $contentView = __DIR__ . '/../../views/auth/register.php';
        require __DIR__ . '/../../views/layouts/auth.php';
    }

    public function showForgotPassword()
    {
        $pageTitle = 'Lupa Kata Sandi';
        $contentView = __DIR__ . '/../../views/auth/forgot-password.php';
        require __DIR__ . '/../../views/layouts/auth.php';
    }

    public function showResetPassword()
    {
        $pageTitle = 'Atur Ulang Kata Sandi';
        $contentView = __DIR__ . '/../../views/auth/reset-password.php';
        require __DIR__ . '/../../views/layouts/auth.php';
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $errorMessage = 'Silakan isi email dan kata sandi Anda.';
            $pageTitle = 'Masuk';
            $old = ['email' => $email];
            $contentView = __DIR__ . '/../../views/auth/login.php';
            require __DIR__ . '/../../views/layouts/auth.php';
            return;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        // Support both hashed password and seed plaintext password from database.sql
        $valid = false;
        if ($user) {
            if (password_verify($password, $user['password']) || $user['password'] === $password) {
                $valid = true;
            }
        }

        if ($valid) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: ' . base_url('dashboard'));
            exit;
        }

        $errorMessage = 'Email atau kata sandi tidak cocok.';
        $pageTitle = 'Masuk';
        $old = ['email' => $email];
        $contentView = __DIR__ . '/../../views/auth/login.php';
        require __DIR__ . '/../../views/layouts/auth.php';
    }

    public function register()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($name) || empty($email) || empty($password)) {
            $errorMessage = 'Semua field wajib diisi.';
            $pageTitle = 'Daftar Akun';
            $old = ['name' => $name, 'email' => $email];
            $contentView = __DIR__ . '/../../views/auth/register.php';
            require __DIR__ . '/../../views/layouts/auth.php';
            return;
        }

        // Check if email already exists
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $errorMessage = 'Alamat email ini sudah terdaftar.';
            $pageTitle = 'Daftar Akun';
            $old = ['name' => $name, 'email' => $email];
            $contentView = __DIR__ . '/../../views/auth/register.php';
            require __DIR__ . '/../../views/layouts/auth.php';
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password, created_at, updated_at) 
            VALUES (:name, :email, :password, NOW(), NOW())
        ");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        $userId = $this->pdo->lastInsertId();
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        header('Location: ' . base_url('dashboard'));
        exit;
    }

    public function logout()
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header('Location: ' . base_url('login'));
        exit;
    }
}
