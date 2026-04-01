<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Course;

class HomeController extends Controller
{
    public function entry()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        if (Auth::isAdmin()) {
            $this->redirect('/admin/applications');
        }

        $this->redirect('/home');
    }

    public function index()
    {
        if (!Auth::check()) {
            Session::setFlash('error', 'Сначала войдите в систему.');
            $this->redirect('/login');
        }

        if (Auth::isAdmin()) {
            $this->redirect('/admin/applications');
        }

        $courses = Course::names();

        $this->view('home/index', [
            'title' => 'Корочки.есть — портал онлайн-курсов ДПО',
            'courses' => is_array($courses) ? $courses : [],
            'courseCount' => is_array($courses) ? count($courses) : 0,
        ]);
    }
}
