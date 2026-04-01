<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Application;
use App\Models\Course;

class AdminController extends Controller
{
    public function index()
    {
        $this->requireAdmin();

        $status = trim(isset($_GET['status']) ? $_GET['status'] : '');
        $search = trim(isset($_GET['search']) ? $_GET['search'] : '');
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $filters = [
            'status' => $status,
            'search' => $search,
        ];

        $result = Application::paginateForAdmin($filters, $page, 8);

        $this->view('admin/index', [
            'title' => 'Админ-панель',
            'applications' => $result['items'],
            'filters' => $filters,
            'pagination' => $result,
            'statuses' => app_config('application_statuses'),
            'courses' => Course::all(),
        ]);
    }

    public function updateStatus()
    {
        $this->requireAdmin();
        $this->validateCsrf();

        $data = [
            'application_id' => isset($_POST['application_id']) ? $_POST['application_id'] : '',
            'status' => isset($_POST['status']) ? trim($_POST['status']) : '',
        ];

        $errors = Validator::adminStatus($data);

        if ($errors) {
            Session::setFlash('error', 'Не удалось обновить статус заявки.');
            $this->redirect('/admin/applications');
        }

        $application = Application::findWithUser((int) $data['application_id']);

        if (!$application) {
            Session::setFlash('error', 'Заявка не найдена.');
            $this->redirect('/admin/applications');
        }

        Application::updateStatus((int) $data['application_id'], $data['status']);
        Session::setFlash('success', 'Статус заявки обновлён.');

        $query = [];
        if (!empty($_POST['return_status'])) {
            $query['status'] = $_POST['return_status'];
        }
        if (!empty($_POST['return_search'])) {
            $query['search'] = $_POST['return_search'];
        }
        if (!empty($_POST['return_page'])) {
            $query['page'] = $_POST['return_page'];
        }

        $target = '/admin/applications';
        if ($query) {
            $target .= '?' . http_build_query($query);
        }

        $this->redirect($target);
    }

    public function storeCourse()
    {
        $this->requireAdmin();
        $this->validateCsrf();

        $data = [
            'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
        ];

        $errors = Validator::course($data);

        if ($errors) {
            Session::setOld(['course_name' => $data['name']]);
            Session::setFlash('error', reset($errors));
            $this->redirect('/admin/applications');
        }

        Course::create($data['name']);
        Session::clearOld();
        Session::setFlash('success', 'Новый курс добавлен. Он уже доступен в форме заявки.');
        $this->redirect('/admin/applications');
    }
}
