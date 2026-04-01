<?php
namespace App\Core;

class Controller
{
    protected function view($view, array $data = [], $layout = 'main')
    {
        View::render($view, $data, $layout);
    }

    protected function redirect($path)
    {
        header('Location: ' . url($path));
        exit;
    }

    protected function requireGuest()
    {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                $this->redirect('/admin/applications');
            }
            $this->redirect('/applications');
        }
    }

    protected function requireAuth()
    {
        if (!Auth::check()) {
            Session::setFlash('error', 'Сначала войдите в систему.');
            $this->redirect('/login');
        }
    }

    protected function requireAdmin()
    {
        if (!Auth::check()) {
            Session::setFlash('error', 'Сначала войдите в систему.');
            $this->redirect('/login');
        }

        if (!Auth::isAdmin()) {
            http_response_code(403);
            $this->view('errors/403', ['title' => 'Доступ запрещен']);
            exit;
        }
    }

    protected function validateCsrf()
    {
        if (!Csrf::check(isset($_POST['_token']) ? $_POST['_token'] : '')) {
            http_response_code(419);
            $this->view('errors/419', ['title' => 'Сессия истекла']);
            exit;
        }
    }
}
