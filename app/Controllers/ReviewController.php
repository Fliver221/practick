<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Application;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create()
    {
        $this->requireAuth();

        if (Auth::isAdmin()) {
            $this->redirect('/admin/applications');
        }

        $applicationId = isset($_GET['application_id']) ? (int) $_GET['application_id'] : 0;
        $application = Application::findOwnedByUser($applicationId, Auth::id());

        if (!$application) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Заявка не найдена']);
            return;
        }

        if ($application['status'] !== 'Обучение завершено') {
            Session::setFlash('error', 'Отзыв можно оставить только после завершения обучения.');
            $this->redirect('/applications');
        }

        if (Review::findByApplicationId($applicationId)) {
            Session::setFlash('error', 'Для этой заявки отзыв уже оставлен.');
            $this->redirect('/applications');
        }

        $this->view('reviews/create', [
            'title' => 'Оставить отзыв',
            'application' => $application,
        ]);
    }

    public function store()
    {
        $this->requireAuth();

        if (Auth::isAdmin()) {
            $this->redirect('/admin/applications');
        }

        $this->validateCsrf();

        $applicationId = isset($_POST['application_id']) ? (int) $_POST['application_id'] : 0;
        $application = Application::findOwnedByUser($applicationId, Auth::id());

        if (!$application) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Заявка не найдена']);
            return;
        }

        if ($application['status'] !== 'Обучение завершено') {
            Session::setFlash('error', 'Отзыв можно оставить только после завершения обучения.');
            $this->redirect('/applications');
        }

        if (Review::findByApplicationId($applicationId)) {
            Session::setFlash('error', 'Для этой заявки отзыв уже оставлен.');
            $this->redirect('/applications');
        }

        $data = [
            'review_text' => trim(isset($_POST['review_text']) ? $_POST['review_text'] : ''),
        ];

        $errors = Validator::review($data);

        if ($errors) {
            Session::setOld($data);
            $this->view('reviews/create', [
                'title' => 'Оставить отзыв',
                'application' => $application,
                'errors' => $errors,
            ]);
            return;
        }

        Review::create([
            'user_id' => Auth::id(),
            'application_id' => $applicationId,
            'review_text' => $data['review_text'],
        ]);

        Session::clearOld();
        Session::setFlash('success', 'Спасибо! Ваш отзыв сохранён.');
        $this->redirect('/applications');
    }
}
