<?php
declare(strict_types=1);

/** ---------- خروجی امن ---------- */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** ---------- مسیرها ---------- */
function url(string $path = '/'): string
{
    $baseUrl = Config::get('app.url', '');
    if ($path === '/' || $path === '') {
        return $baseUrl !== '' ? $baseUrl : '/';
    }
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    $baseUrl = Config::get('app.url', '');
    return rtrim($baseUrl, '/') . '/assets/' . ltrim($path, '/') . '?v=1.0.0';
}

function current_path(): string
{
    return '/' . trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
}

function redirect(string $path): never
{
    header('Location: ' . url($path), true, 302);
    exit;
}

/** ---------- ورودی‌ها ---------- */
function is_post(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function post_val(string $key, string $default = ''): string
{
    $value = $_POST[$key] ?? $default;
    return is_string($value) ? trim($value) : $default;
}

function get_val(string $key, string $default = ''): string
{
    $value = $_GET[$key] ?? $default;
    return is_string($value) ? trim($value) : $default;
}

/** ---------- پیام‌های یک‌بارمصرف ---------- */
function flash(string $type, string $message): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
}

function flashes(): array
{
    $items = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return is_array($items) ? $items : [];
}

/** ---------- CSRF ---------- */
function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $token = (string) ($_POST['_csrf'] ?? '');
    if ($token === '' || !hash_equals(csrf_token(), $token)) {
        abort(419, 'اعتبار فرم منقضی شده است. لطفاً صفحه را دوباره باز کنید و مجدداً تلاش کنید.');
    }
}

/** ---------- رندر قالب ---------- */
function view(string $template, array $data = [], string $layout = 'layouts/main'): void
{
    $file = BASE_PATH . '/app/views/' . $template . '.php';
    if (!is_file($file)) {
        http_response_code(500);
        exit('View not found: ' . $template);
    }

    extract($data, EXTR_SKIP);
    ob_start();
    require $file;
    $content = (string) ob_get_clean();

    if ($layout === '') {
        echo $content;
        return;
    }

    require BASE_PATH . '/app/views/' . $layout . '.php';
}

function abort(int $code, string $message = ''): never
{
    http_response_code($code);
    $titles = [
        403 => 'دسترسی غیرمجاز',
        404 => 'صفحه پیدا نشد',
        419 => 'نشست منقضی شد',
        500 => 'خطای سرور',
    ];
    view('error', [
        'title'   => $titles[$code] ?? 'خطا',
        'code'    => $code,
        'message' => $message !== '' ? $message : 'مشکلی پیش آمده است.',
    ]);
    exit;
}

/** ---------- قالب‌بندی نمایش ---------- */
function money(int|float|string|null $amount): string
{
    $amount = (float) $amount;
    if ($amount <= 0) {
        return 'توافقی';
    }
    return fa_digits(number_format($amount)) . ' تومان';
}

function fa_digits(string $text): string
{
    return str_replace(
        ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
        $text
    );
}

function duration_label(int $minutes): string
{
    if ($minutes <= 0) {
        return '—';
    }
    $h = intdiv($minutes, 60);
    $m = $minutes % 60;
    if ($h > 0) {
        return fa_digits((string) $h) . ' ساعت' . ($m ? ' و ' . fa_digits((string) $m) . ' دقیقه' : '');
    }
    return fa_digits((string) $m) . ' دقیقه';
}

/** ---------- تاریخ شمسی ---------- */
function gregorian_to_jalali(int $gy, int $gm, int $gd): array
{
    $gDaysInMonth = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    $gy2  = ($gm > 2) ? ($gy + 1) : $gy;
    $days = 355666 + (365 * $gy) + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100)
          + intdiv($gy2 + 399, 400) + $gd + $gDaysInMonth[$gm - 1];

    $jy    = -1595 + (33 * intdiv($days, 12053));
    $days %= 12053;
    $jy   += 4 * intdiv($days, 1461);
    $days %= 1461;

    if ($days > 365) {
        $jy  += intdiv($days - 1, 365);
        $days = ($days - 1) % 365;
    }

    if ($days < 186) {
        $jm = 1 + intdiv($days, 31);
        $jd = 1 + ($days % 31);
    } else {
        $jm = 7 + intdiv($days - 186, 30);
        $jd = 1 + (($days - 186) % 30);
    }

    return [$jy, $jm, $jd];
}

function jalali_date(?string $date, bool $withTime = false): string
{
    if ($date === null || $date === '' || str_starts_with($date, '0000')) {
        return '—';
    }

    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return '—';
    }

    [$jy, $jm, $jd] = gregorian_to_jalali(
        (int) date('Y', $timestamp),
        (int) date('n', $timestamp),
        (int) date('j', $timestamp)
    );

    $months = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
                'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];

    $out = fa_digits((string) $jd) . ' ' . $months[$jm - 1] . ' ' . fa_digits((string) $jy);

    if ($withTime) {
        $out .= ' — ساعت ' . fa_digits(date('H:i', $timestamp));
    }

    return $out;
}

/** ---------- متن ---------- */
function make_slug(string $text): string
{
    $text = trim($text);
    $text = preg_replace('/[\s\x{200c}]+/u', '-', $text) ?? $text;
    $text = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $text) ?? $text;
    $text = trim(preg_replace('/-+/', '-', $text) ?? $text, '-');
    return mb_strtolower($text !== '' ? $text : 'course-' . bin2hex(random_bytes(3)));
}

function excerpt(?string $text, int $length = 140): string
{
    $text = trim(strip_tags((string) $text));
    return mb_strlen($text) <= $length ? $text : mb_substr($text, 0, $length) . '…';
}

function client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

/** ---------- تشخیص و تولید آدرس تصاویر و رسانه‌ها ---------- */
function is_url(?string $value): bool
{
    if ($value === null || $value === '') {
        return false;
    }
    return str_starts_with($value, 'http://')
        || str_starts_with($value, 'https://')
        || str_starts_with($value, '//');
}

function cover_url(?string $cover): string
{
    if ($cover === null || $cover === '') {
        return '';
    }
    if (is_url($cover)) {
        return $cover;
    }
    $baseUrl = Config::get('app.url', '');
    return rtrim($baseUrl, '/') . '/uploads/courses/' . basename($cover);
}
