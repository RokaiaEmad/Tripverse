<?php
require_once __DIR__. '/../models/Trip.php';

class HomeController
{
    public function index(): void
    {
        $is_logged_in = isset($_SESSION['user_id']);

        if ($is_logged_in) {
            $user_id   = $_SESSION['user_id'];
            $user_name = $_SESSION['user_name'] ?? 'User';

            $tripModel = new Trip();

            $trips          = $tripModel->getByUser($user_id);
            $total_trips    = count($trips);
            $upcoming_count = $tripModel->countUpcoming($user_id);

            $hour = (int) date('H');
            $greet = $hour < 12 ? 'Good morning'
                : ($hour < 18 ? 'Good afternoon'
                    : 'Good evening');

            $this->render('home/index', compact(
                'is_logged_in',
                'user_name',
                'greet',
                'trips',
                'total_trips',
                'upcoming_count'
            ));
        } else {
            $this->render('home/index', compact('is_logged_in'));
        }
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . "/../views/$view.php";
    }
}
