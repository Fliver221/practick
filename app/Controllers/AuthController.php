<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister()
    {
        $this->requireGuest();
        $this->view('auth/register', ['title' => 'Регистрация']);
    }

    public function register()
    {
        $this->requireGuest();
        $this->validateCsrf();

        $data = [
            'login' => trim(isset($_POST['login']) ? $_POST['login'] : ''),
            'password' => isset($_POST['password']) ? (string) $_POST['password'] : '',
            'full_name' => trim(isset($_POST['full_name']) ? $_POST['full_name'] : ''),
            'phone' => trim(isset($_POST['phone']) ? $_POST['phone'] : ''),
            'email' => trim(isset($_POST['email']) ? $_POST['email'] : ''),
        ];

        $errors = Validator::registration($data);

        if ($errors) {
            Session::setOld($data);
            $this->view('auth/register', [
                'title' => 'Регистрация',
                'errors' => $errors,
            ]);
            return;
        }

        $userId = User::create($data);
        $user = User::find($userId);

        Auth::login($user);
        Session::clearOld();
        Session::setFlash('success', 'Регистрация прошла успешно. Добро пожаловать в систему.');

        $this->redirect('/applications');
    }

    public function showLogin()
    {
        $this->requireGuest();
        $this->view('auth/login', ['title' => 'Авторизация']);
    }

    public function login()
    {
        $this->requireGuest();
        $this->validateCsrf();

        $data = [
            'login' => trim(isset($_POST['login']) ? $_POST['login'] : ''),
            'password' => isset($_POST['password']) ? (string) $_POST['password'] : '',
        ];

        $errors = Validator::login($data);

        if ($errors) {
            Session::setOld($data);
            $this->view('auth/login', [
                'title' => 'Авторизация',
                'errors' => $errors,
            ]);
            return;
        }

        if (!Auth::attempt($data['login'], $data['password'])) {
            Session::setOld($data);
            $this->view('auth/login', [
                'title' => 'Авторизация',
                'errors' => ['common' => 'Неверный логин или пароль.'],
            ]);
            return;
        }

        Session::clearOld();
        Session::setFlash('success', 'Вы успешно вошли в систему.');

        if (Auth::isAdmin()) {
            $this->redirect('/admin/applications');
        }

        $this->redirect('/applications');
    }

    public function logout()
    {
        $this->validateCsrf();
        Auth::logout();
        Session::setFlash('success', 'Вы вышли из системы.');
        $this->redirect('/login');
    }
}
