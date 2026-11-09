<?php

declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    return $GLOBALS['app_config'][$key] ?? $default;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function ident(string $name): string
{
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $name)) {
        throw new InvalidArgumentException('Invalid identifier.');
    }
    return $name;
}

function base_url(string $path = ''): string
{
    static $base = null;
    if ($base === null) {
        $configured = (string) config('url', '');
        if ($configured !== '') {
            $base = rtrim($configured, '/');
        } else {
            $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            $script = (string) preg_replace('#/public$#', '', $script);
            $base = ($script === '/' || $script === '.' || $script === '') ? '' : rtrim($script, '/');
        }
    }

    if ($path === '') {
        return $base === '' ? '/' : $base;
    }

    return ($base === '' ? '' : $base) . '/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    return base_url($path);
}

function absolute_url(string $path = ''): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return ($https ? 'https://' : 'http://') . $host . base_url($path);
}

function asset(string $path): string
{
    $relative = ltrim($path, '/');
    $full = ROOT_PATH . '/public/assets/' . $relative;
    $version = is_file($full) ? (string) filemtime($full) : '1';
    return base_url('assets/' . $relative) . '?v=' . $version;
}

function media_url(?string $path): string
{
    if ($path === null || $path === '') {
        return asset('images/placeholder.svg');
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return base_url(ltrim($path, '/'));
}

function redirect(string $path): never
{
    if (!preg_match('#^https?://#i', $path)) {
        $path = str_starts_with($path, '/') ? base_url(ltrim($path, '/')) : base_url($path);
    }
    header('Location: ' . $path);
    exit;
}

function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = rtrim(base_url(), '/');
    if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base)) ?: '/';
    }
    $path = '/' . trim($path, '/');
    return $path === '/' ? '/' : rtrim($path, '/');
}

function is_active(string $path): bool
{
    $current = current_path();
    $path = parse_url($path, PHP_URL_PATH) ?: '/';
    $path = '/' . trim($path, '/');
    if ($path === '/') {
        return $current === '/';
    }
    return $current === $path || str_starts_with($current, $path . '/');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    $known = $_SESSION['csrf'] ?? '';
    if (!is_string($token) || $known === '' || !hash_equals($known, $token)) {
        http_response_code(419);
        exit('Invalid security token. Go back, refresh the page, and try again.');
    }
}

function flash(?string $key = null, ?string $message = null): mixed
{
    if ($key === null) {
        return null;
    }
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function set_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function field_error(string $key): string
{
    return (string) ($_SESSION['_errors'][$key] ?? '');
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-') ?: 'item';
}

function excerpt(?string $html, int $length = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $html)) ?? '');
    if (strlen($text) <= $length) {
        return $text;
    }
    return rtrim(substr($text, 0, $length - 1)) . '…';
}

function clean_html(string $html): string
{
    $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? '';
    $html = preg_replace('#<iframe\b[^>]*>.*?</iframe>#is', '', $html) ?? '';
    $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
    $html = preg_replace('/javascript\s*:/i', '', $html) ?? '';
    return $html;
}

function format_date(?string $date, string $format = 'd M Y'): string
{
    if ($date === null || $date === '') {
        return '';
    }
    $time = strtotime($date);
    return $time ? date($format, $time) : '';
}

function settings(?string $key = null, ?string $default = null): mixed
{
    static $cache = null;
    if ($cache === null) {
        $rows = \App\Core\Model::fetchAll('SELECT setting_key, setting_value FROM settings');
        $cache = [];
        foreach ($rows as $row) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    }
    if ($key === null) {
        return $cache;
    }
    return $cache[$key] ?? $default;
}

function setting_url(string $key, string $fallback = '#'): string
{
    $value = trim((string) settings($key, ''));
    return $value !== '' ? $value : $fallback;
}

function menu_tree(string $slug): array
{
    $menu = \App\Core\Model::fetch('SELECT id FROM menus WHERE slug = ?', [$slug]);
    if (!$menu) {
        return [];
    }
    $items = \App\Core\Model::fetchAll(
        "SELECT * FROM menu_items WHERE menu_id = ? AND status = 'published' ORDER BY sort_order ASC, id ASC",
        [(int) $menu['id']]
    );
    $grouped = [];
    foreach ($items as $item) {
        $grouped[(int) ($item['parent_id'] ?? 0)][] = $item;
    }
    $build = function (int $parent) use (&$build, $grouped): array {
        $branch = [];
        foreach ($grouped[$parent] ?? [] as $item) {
            $item['children'] = $build((int) $item['id']);
            $branch[] = $item;
        }
        return $branch;
    };
    return $build(0);
}

function nav_href(string $href): string
{
    if ($href === '' || $href === '#') {
        return '#';
    }
    if (preg_match('#^(https?:)?//#i', $href) || str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:')) {
        return $href;
    }
    return base_url(ltrim($href, '/'));
}

function abort(int $code): never
{
    http_response_code($code);
    $file = $code === 404 ? 'errors/404' : 'errors/500';
    view($file, ['metaTitle' => $code === 404 ? 'Page not found' : 'Something went wrong'], 'layouts/main');
    exit;
}

function view(string $name, array $data = [], ?string $layout = 'layouts/main'): void
{
    $data['metaTitle'] = $data['metaTitle'] ?? settings('site_name', 'SLGTI');
    $data['metaDescription'] = $data['metaDescription'] ?? settings('meta_description', '');
    extract($data, EXTR_SKIP);
    $viewFile = APP_PATH . '/Views/' . $name . '.php';
    if (!is_file($viewFile)) {
        throw new RuntimeException('View not found: ' . $name);
    }
    ob_start();
    require $viewFile;
    $content = ob_get_clean();
    if ($layout === null) {
        echo $content;
        return;
    }
    require APP_PATH . '/Views/' . $layout . '.php';
}

function paginate(int $total, int $page, int $perPage): array
{
    $pages = max(1, (int) ceil($total / $perPage));
    $page = min(max(1, $page), $pages);
    return [
        'total' => $total,
        'page' => $page,
        'pages' => $pages,
        'per_page' => $perPage,
        'offset' => ($page - 1) * $perPage,
    ];
}

function render_pagination(array $pager, string $basePath, array $query = []): string
{
    if ($pager['pages'] < 2) {
        return '';
    }
    $html = '<nav class="pager" aria-label="Pagination"><ul>';
    for ($i = 1; $i <= $pager['pages']; $i++) {
        $query['page'] = $i;
        $href = base_url(ltrim($basePath, '/')) . '?' . http_build_query($query);
        $current = $i === $pager['page'] ? ' aria-current="page"' : '';
        $html .= '<li><a class="' . ($i === $pager['page'] ? 'is-current' : '') . '" href="' . e($href) . '"' . $current . '>' . $i . '</a></li>';
    }
    $html .= '</ul></nav>';
    return $html;
}

function client_ip(): string
{
    return substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
}

function log_activity(string $action, string $description): void
{
    \App\Core\Model::insert('activity_logs', [
        'user_id' => $_SESSION['user_id'] ?? null,
        'action' => $action,
        'description' => mb_substr($description, 0, 500),
        'ip_address' => client_ip(),
        'created_at' => date('Y-m-d H:i:s'),
    ]);
}

function require_admin(): void
{
    if (empty($_SESSION['user_id'])) {
        flash('error', 'Please sign in to continue.');
        redirect('/admin/login');
    }
}

function can(string $permission): bool
{
    if (($_SESSION['role'] ?? '') === 'super_admin') {
        return true;
    }
    $perms = $_SESSION['permissions'] ?? null;
    if (!is_array($perms)) {
        $perms = [
            'editor' => ['content.manage', 'messages.view', 'applications.manage', 'media.manage'],
            'staff' => ['applications.view', 'messages.view'],
        ][$_SESSION['role'] ?? ''] ?? [];
    }
    if (in_array($permission, $perms, true)) {
        return true;
    }
    $grantedByContent = ['news.manage', 'events.manage', 'departments.manage', 'programmes.manage', 'gallery.manage', 'staff.manage', 'pages.manage', 'slides.manage'];
    return in_array('content.manage', $perms, true) && in_array($permission, $grantedByContent, true);
}

function require_permission(string $permission): void
{
    require_admin();
    if (!can($permission)) {
        http_response_code(403);
        exit('You do not have permission to open this area.');
    }
}

function upload_private_file(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('The document could not be uploaded.');
    }
    if (($file['size'] ?? 0) > (int) config('upload_max', 5242880)) {
        throw new RuntimeException('Documents must be 5 MB or smaller.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']) ?: '';
    $allowed = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Upload a PDF, JPG, PNG, or WEBP file.');
    }
    if ($mime === 'application/pdf') {
        $head = file_get_contents($file['tmp_name'], false, null, 0, 5);
        if ($head !== '%PDF-') {
            throw new RuntimeException('The PDF could not be verified.');
        }
    }
    $directory = STORAGE_PATH . '/applications';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('The document folder is not available.');
    }
    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $destination = $directory . '/' . $filename;
    if ($mime === 'application/pdf') {
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('The document could not be stored.');
        }
    } elseif (!store_safe_image($file['tmp_name'], $mime, $destination)) {
        throw new RuntimeException('The image could not be verified.');
    }
    return [
        'path' => 'applications/' . $filename,
        'mime' => $mime,
        'size' => filesize($destination) ?: 0,
        'original_name' => mb_substr((string) ($file['name'] ?? $filename), 0, 180),
    ];
}

function upload_image(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException('Choose an image to upload.');
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('The upload could not be completed.');
    }
    $max = (int) config('upload_max', 5242880);
    if (($file['size'] ?? 0) > $max) {
        throw new RuntimeException('Images must be 5 MB or smaller.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']) ?: '';
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Upload a JPG, PNG, WEBP, or GIF image.');
    }

    $folder = date('Y/m');
    $directory = ROOT_PATH . '/public/uploads/' . $folder;
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('The upload folder is not available.');
    }
    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $destination = $directory . '/' . $filename;
    if (!store_safe_image($file['tmp_name'], $mime, $destination)) {
        throw new RuntimeException('The image could not be verified.');
    }

    $relative = 'uploads/' . $folder . '/' . $filename;
    \App\Core\Model::insert('media', [
        'filename' => $filename,
        'original_name' => mb_substr((string) ($file['name'] ?? $filename), 0, 180),
        'path' => $relative,
        'mime' => $mime,
        'size' => filesize($destination) ?: 0,
        'uploaded_by' => $_SESSION['user_id'] ?? null,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
    return $relative;
}

function store_safe_image(string $source, string $mime, string $destination): bool
{
    if (!function_exists('imagecreatetruecolor')) {
        return false;
    }
    $image = match ($mime) {
        'image/jpeg' => @imagecreatefromjpeg($source),
        'image/png' => @imagecreatefrompng($source),
        'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
        'image/gif' => @imagecreatefromgif($source),
        default => false,
    };
    if (!$image) {
        return false;
    }
    $saved = match ($mime) {
        'image/jpeg' => imagejpeg($image, $destination, 86),
        'image/png' => imagepng($image, $destination, 6),
        'image/webp' => function_exists('imagewebp') ? imagewebp($image, $destination, 82) : false,
        'image/gif' => imagegif($image, $destination),
        default => false,
    };
    imagedestroy($image);
    return (bool) $saved;
}

function youtube_id(string $url): ?string
{
    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{6,})~', $url, $matches)) {
        return $matches[1];
    }
    return null;
}

function wants_json(): bool
{
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    return str_contains($accept, 'application/json') || strcasecmp($xhr, 'XMLHttpRequest') === 0;
}

function json_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}
