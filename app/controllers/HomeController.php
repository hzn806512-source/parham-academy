<?php
declare(strict_types=1);

final class HomeController
{
    public static function index(array $params = []): void
    {
        $featured = Database::selectAll(
            "SELECT c.*, (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS lesson_count
             FROM courses c
             WHERE c.status = 'published'
             ORDER BY c.is_featured DESC, c.sort_order ASC, c.id DESC
             LIMIT 3"
        );

        $stats = [
            'courses' => (int) Database::scalar("SELECT COUNT(*) FROM courses WHERE status = 'published'"),
            'lessons' => (int) Database::scalar('SELECT COUNT(*) FROM lessons'),
            'minutes' => (int) Database::scalar('SELECT COALESCE(SUM(duration_minutes), 0) FROM lessons'),
            'students' => (int) Database::scalar("SELECT COUNT(*) FROM users WHERE role = 'user'"),
        ];

        view('home', [
            'title'    => Config::get('app.name') . ' — ' . Config::get('app.tagline'),
            'featured' => $featured,
            'stats'    => $stats,
        ]);
    }

    public static function guide(array $params = []): void
    {
        view('guide', ['title' => 'راهنمای استفاده از سایت']);
    }
}