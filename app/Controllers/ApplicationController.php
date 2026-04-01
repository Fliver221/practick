<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Application;
use App\Models\Course;

class ApplicationController extends Controller
{
    public function index()
    {
        $this->requireAuth();

        if (Auth::isAdmin()) {
            $this->redirect('/admin/applications');
        }

        $applications = Application::allByUserId(Auth::id());

        $this->view('applications/index', [
            'title' => 'Мои заявки',
            'applications' => $applications,
        ]);
    }

    public function create()
    {
        $this->requireAuth();

        if (Auth::isAdmin()) {
            $this->redirect('/admin/applications');
        }

        $this->view('applications/create', [
            'title' => 'Новая заявка',
            'courses' => Course::names(),
            'paymentMethods' => app_config('payment_methods'),
        ]);
    }

    public function store()
    {
        $this->requireAuth();

        if (Auth::isAdmin()) {
            $this->redirect('/admin/applications');
        }

        $this->validateCsrf();

        $data = [
            'course_name' => trim(isset($_POST['course_name']) ? $_POST['course_name'] : ''),
            'desired_start_date' => trim(isset($_POST['desired_start_date']) ? $_POST['desired_start_date'] : ''),
            'payment_method' => trim(isset($_POST['payment_method']) ? $_POST['payment_method'] : ''),
        ];

        $errors = Validator::application($data);

        if ($errors) {
            Session::setOld($data);
            $this->view('applications/create', [
                'title' => 'Новая заявка',
                'errors' => $errors,
                'courses' => Course::names(),
                'paymentMethods' => app_config('payment_methods'),
            ]);
            return;
        }

        $date = null;
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['desired_start_date'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $data['desired_start_date']);
        } else {
            $date = \DateTime::createFromFormat('d.m.Y', $data['desired_start_date']);
        }

        Application::create([
            'user_id' => Auth::id(),
            'course_name' => $data['course_name'],
            'desired_start_date' => $date->format('Y-m-d'),
            'payment_method' => $data['payment_method'],
            'status' => 'Новая',
        ]);

        Session::clearOld();
        Session::setFlash('success', 'Заявка успешно отправлена на рассмотрение.');
        $this->redirect('/applications');
    }
}
