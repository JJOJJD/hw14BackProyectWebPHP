<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Core\Response;

class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            Response::json(true, $user, "Login successful");
        }

        Response::json(false, null, "Invalid credentials", 401);
    }

    public function googleLogin(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $firstName = $input['firstName'] ?? '';
        $lastName = $input['lastName'] ?? '';

        if (!$email) {
            Response::json(false, null, "Correo de Google requerido", 400);
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            Response::json(true, $user, "Login con Google exitoso");
        } else {
            $hashedPassword = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
            
            // Generar RUC aleatorio de 13 dígitos para evitar colisiones
            $randomRuc = sprintf('%013d', mt_rand(1, 999999999));
            
            $data = [
                'ruc' => $randomRuc,
                'firstName' => $firstName ?: 'Usuario',
                'lastName' => $lastName ?: 'Google',
                'email' => $email,
                'password' => $hashedPassword
            ];

            try {
                $newUser = $this->userRepository->create($data);
                if ($newUser) {
                    Response::json(true, $newUser, "Usuario creado y logueado con Google", 201);
                }
            } catch (\Exception $e) {
                Response::json(false, null, "Fallo al registrar usuario con Google", 500);
            }
        }
        Response::json(false, null, "Error inesperado en Google Login", 500);
    }

    public function register(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $ruc = $input['ruc'] ?? '';
        $firstName = $input['firstName'] ?? '';
        $lastName = $input['lastName'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if (!$ruc || !$firstName || !$lastName || !$email || !$password) {
            Response::json(false, null, "Todos los campos son obligatorios", 400);
        }

        if (!preg_match('/^\d{13}$/', $ruc)) {
            Response::json(false, null, "El RUC debe tener exactamente 13 dígitos numéricos", 400);
        }

        if (preg_match('/[0-9]/', $firstName) || preg_match('/[0-9]/', $lastName)) {
            Response::json(false, null, "El nombre y apellido no deben contener números", 400);
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $data = [
            'ruc' => $ruc,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $hashedPassword
        ];

        try {
            $newUser = $this->userRepository->create($data);
            if ($newUser) {
                Response::json(true, $newUser, "User registered successfully", 201);
            }
        } catch (\Exception $e) {
            Response::json(false, null, "Registration failed", 500);
        }

        Response::json(false, null, "Registration failed", 500);
    }

    public function index(): void
    {
        $users = $this->userRepository->findAll();
        Response::json(true, $users);
    }

    public function resetPassword(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $newPassword = $input['newPassword'] ?? '';

        if (!$email || !$newPassword) {
            Response::json(false, null, "Todos los campos son obligatorios", 400);
        }

        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            Response::json(false, null, "No existe un usuario con ese correo", 404);
        }

        try {
            $user->password = password_hash($newPassword, PASSWORD_BCRYPT);
            $user->save();
            Response::json(true, null, "Contraseña actualizada exitosamente");
        } catch (\Exception $e) {
            Response::json(false, null, "Error al actualizar la contraseña: " . $e->getMessage(), 500);
        }
    }

    public function update(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? '';
        
        if (!$id) {
            Response::json(false, null, "El ID del usuario es requerido", 400);
        }

        $updateData = [];
        if (isset($input['firstName'])) {
            $updateData['firstName'] = $input['firstName'];
        }
        if (isset($input['lastName'])) {
            $updateData['lastName'] = $input['lastName'];
        }
        if (isset($input['email'])) {
            $updateData['email'] = $input['email'];
        }
        
        if (empty($updateData)) {
            Response::json(false, null, "No hay datos para actualizar", 400);
        }

        $success = $this->userRepository->update($id, $updateData);
        if ($success) {
            Response::json(true, null, "Usuario actualizado correctamente");
        }
        
        Response::json(false, null, "No se pudo actualizar el usuario", 500);
    }

    public function delete(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? '';
        
        if (!$id) {
            Response::json(false, null, "El ID del usuario es requerido", 400);
        }

        $success = $this->userRepository->delete($id);
        if ($success) {
            Response::json(true, null, "Usuario eliminado correctamente");
        }
        
        Response::json(false, null, "No se pudo eliminar el usuario", 500);
    }
}
