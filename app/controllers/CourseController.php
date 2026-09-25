<?php
declare(strict_types=1);

final class CourseController
{
    /** لیست پکیج‌های آموزشی */
    public static function index(array $params = []): void
    {
        $search = get_val('q');
        $user   = Auth::user();

        $sql = "SELECT c.*,
                       (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS lesson_count,
                       (SELECT COALESCE(SUM(l.duration_minutes),0) FROM lessons l WHERE l.course_id = c.id) AS total_minutes
                FROM courses c
                WHERE c.status <> 'draft'";
        $args = [];

        if ($search !== '') {
            $sql   .= ' AND (c.title LIKE ? OR c.short_description LIKE ?)';
            $args[] = '%' . $search . '%';
            $args[] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY c.is_featured DESC, c.sort_order ASC, c.id DESC';

        $courses = Database::selectAll($sql, $args);

        // وضعیت دسترسی هر دوره برای کاربر جاری (سمت سرور)
        foreach ($courses as &$course) {
            $course['access'] = Access::status($user, (int) $course['id']);
        }

        view('courses', [
            'title'   => 'دوره‌های آموزشی',
            'courses' => $courses,
            'search'  => $search,
        ]);
    }

    /** جزئیات دوره + لیست دروس + وضعیت دسترسی */
    public static function show(array $params = []): void
    {
        $slug = (string) ($params['slug'] ?? '');

        $course = Database::selectOne(
            "SELECT * FROM courses WHERE slug = ? AND status <> 'draft' LIMIT 1",
            [$slug]
        );

        if ($course === null) {
            abort(404, 'دورهٔ مورد نطر یافت نشد یا منتشر نشده است.');
        }

        $user   = Auth::user();
        $access = Access::status($user, (int) $course['id']);

        $lessons = Database::selectAll(
            'SELECT id, title, description, duration_minutes, sort_order, is_free_preview
             FROM lessons WHERE course_id = ? ORDER BY sort_order ASC, id ASC',
            [(int) $course['id']]
        );

        view('course', [
            'title'   => $course['title'],
            'course'  => $course,
            'lessons' => $lessons,
            'access'  => $access,
            'user'    => $user,
        ]);
    }
}