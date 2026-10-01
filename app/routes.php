<?php
declare(strict_types=1);

$router = new Router();

/* ---------- صفحات عمومی ---------- */
$router->get('/',                 [HomeController::class, 'index']);
$router->get('/courses',          [CourseController::class, 'index']);
$router->get('/course/{slug}',    [CourseController::class, 'show']);
$router->get('/guide',            [HomeController::class, 'guide']);

/* ---------- احراز هویت ---------- */
$router->get('/login',            [AuthController::class, 'showLogin']);
$router->post('/login',           [AuthController::class, 'login']);
$router->get('/register',         [AuthController::class, 'showRegister']);
$router->post('/register',        [AuthController::class, 'register']);
$router->get('/register/verify',  [AuthController::class, 'showVerifyOtp']);
$router->post('/register/verify', [AuthController::class, 'verifyOtp']);
$router->post('/logout',          [AuthController::class, 'logout']);
$router->post('/api/auto-login',  [AuthController::class, 'autoLogin']);

/* ---------- پنل کاربر ---------- */
$router->get('/panel',                         [PanelController::class, 'dashboard']);
$router->get('/panel/profile',                 [PanelController::class, 'profile']);
$router->post('/panel/profile',                [PanelController::class, 'updateProfile']);
$router->post('/panel/password',               [PanelController::class, 'changePassword']);
$router->get('/watch/{lesson}',                [PanelController::class, 'watch']);
$router->get('/panel/stream-ticket/{lesson}',  [PanelController::class, 'streamTicket']);

/* ---------- پخش امن ویدیو ---------- */
$router->get('/media/lesson/{lesson}', [VideoController::class, 'stream']);

/* ---------- پنل مدیریت ---------- */
$router->get('/admin',                        [AdminController::class, 'dashboard']);

$router->get('/admin/users',                  [AdminUserController::class, 'index']);
$router->get('/admin/users/create',           [AdminUserController::class, 'create']);
$router->post('/admin/users/create',          [AdminUserController::class, 'store']);
$router->get('/admin/users/{id}/edit',        [AdminUserController::class, 'edit']);
$router->post('/admin/users/{id}/edit',       [AdminUserController::class, 'update']);
$router->post('/admin/users/{id}/delete',     [AdminUserController::class, 'destroy']);

$router->get('/admin/courses',                [AdminCourseController::class, 'index']);
$router->get('/admin/courses/create',         [AdminCourseController::class, 'create']);
$router->post('/admin/courses/create',        [AdminCourseController::class, 'store']);
$router->get('/admin/courses/{id}/edit',      [AdminCourseController::class, 'edit']);
$router->post('/admin/courses/{id}/edit',     [AdminCourseController::class, 'update']);
$router->post('/admin/courses/{id}/delete',   [AdminCourseController::class, 'destroy']);

$router->get('/admin/courses/{id}/lessons',   [AdminCourseController::class, 'lessons']);
$router->post('/admin/courses/{id}/lessons',  [AdminCourseController::class, 'storeLesson']);
$router->post('/admin/lessons/{id}/update',   [AdminCourseController::class, 'updateLesson']);
$router->post('/admin/lessons/{id}/delete',   [AdminCourseController::class, 'destroyLesson']);

$router->get('/admin/access',                 [AdminAccessController::class, 'index']);
$router->post('/admin/access/grant',          [AdminAccessController::class, 'grant']);
$router->post('/admin/access/{id}/unlimited', [AdminAccessController::class, 'toggleUnlimited']);
$router->post('/admin/access/{id}/toggle',    [AdminAccessController::class, 'toggleActive']);
$router->post('/admin/access/{id}/extend',    [AdminAccessController::class, 'extend']);
$router->post('/admin/access/{id}/delete',    [AdminAccessController::class, 'destroy']);

$router->get('/admin/profile',                [AdminController::class, 'profile']);
$router->post('/admin/profile',               [AdminController::class, 'updateProfile']);
$router->get('/admin/help',                   [AdminController::class, 'help']);

return $router;