<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Admin;
use App\Models\Service;
use App\Models\Review;
use App\Models\Contact;
use App\Models\Page;
use App\Models\Setting;

class AdminController extends Controller
{
    public function __construct()
    {
        if (!in_array($_SERVER['REQUEST_URI'], ['/admin/login', '/admin/auth'])) {
            $this->requireAuth();
        }
    }

    public function login(): void
    {
        if ($this->auth()) {
            $this->redirect('/admin/dashboard');
            return;
        }

        $this->view('admin.login', [
            'pageTitle' => 'Вхід до адмін-панелі',
        ]);
    }

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/login');
            return;
        }

        $username = $this->sanitize($this->post('username'));
        $password = $this->post('password');

        $admin = Admin::authenticate($username, $password);

        if ($admin) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $this->redirect('/admin/dashboard');
        } else {
            $this->flash('error', 'Невірний логін або пароль');
            $this->redirect('/admin/login');
        }
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/admin/login');
    }

    public function dashboard(): void
    {
        $stats = [
            'services' => count(Service::getAllForAdmin()),
            'reviews_pending' => count(Review::pending()),
            'contacts_unread' => Contact::countUnread(),
        ];

        $recentContacts = array_slice(Contact::all(), 0, 5);
        $pendingReviews = array_slice(Review::pending(), 0, 5);

        $this->view('admin.dashboard', [
            'pageTitle' => 'Панель управління',
            'stats' => $stats,
            'recentContacts' => $recentContacts,
            'pendingReviews' => $pendingReviews,
        ]);
    }

    // Services Management
    public function services(): void
    {
        $services = Service::getAllForAdmin();
        $this->view('admin.services.index', [
            'pageTitle' => 'Управління послугами',
            'services' => $services,
        ]);
    }

    public function serviceCreate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $this->sanitize($this->post('title')),
                'slug' => $this->sanitize($this->post('slug')),
                'description' => $this->sanitize($this->post('description')),
                'content' => $this->post('content'),
                'icon' => $this->sanitize($this->post('icon')),
                'sort_order' => (int)$this->post('sort_order', 0),
                'is_active' => (int)$this->post('is_active', 1),
            ];

            Service::create($data);
            $this->flash('success', 'Послугу додано');
            $this->redirect('/admin/services');
        }

        $this->view('admin.services.create', [
            'pageTitle' => 'Додати послугу',
        ]);
    }

    public function serviceEdit(int $id): void
    {
        $service = Service::find($id);
        if (!$service) {
            $this->flash('error', 'Послугу не знайдено');
            $this->redirect('/admin/services');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $this->sanitize($this->post('title')),
                'slug' => $this->sanitize($this->post('slug')),
                'description' => $this->sanitize($this->post('description')),
                'content' => $this->post('content'),
                'icon' => $this->sanitize($this->post('icon')),
                'sort_order' => (int)$this->post('sort_order', 0),
                'is_active' => (int)$this->post('is_active', 1),
            ];

            Service::update($id, $data);
            $this->flash('success', 'Послугу оновлено');
            $this->redirect('/admin/services');
        }

        $this->view('admin.services.edit', [
            'pageTitle' => 'Редагувати послугу',
            'service' => $service,
        ]);
    }

    public function serviceDelete(int $id): void
    {
        Service::delete($id);
        $this->flash('success', 'Послугу видалено');
        $this->redirect('/admin/services');
    }

    // Reviews Management
    public function reviews(): void
    {
        $reviews = Review::all();
        $this->view('admin.reviews.index', [
            'pageTitle' => 'Управління відгуками',
            'reviews' => $reviews,
        ]);
    }

    public function reviewApprove(int $id): void
    {
        Review::approve($id);
        $this->flash('success', 'Відгук схвалено');
        $this->back();
    }

    public function reviewToggleFeatured(int $id): void
    {
        $review = Review::find($id);
        if ($review) {
            Review::update($id, ['is_featured' => $review['is_featured'] ? 0 : 1]);
            $this->flash('success', 'Статус оновлено');
        }
        $this->back();
    }

    public function reviewDelete(int $id): void
    {
        Review::delete($id);
        $this->flash('success', 'Відгук видалено');
        $this->back();
    }

    // Contacts Management
    public function contacts(): void
    {
        $contacts = Contact::all();
        $this->view('admin.contacts.index', [
            'pageTitle' => 'Заявки на зв\'язок',
            'contacts' => $contacts,
        ]);
    }

    public function contactView(int $id): void
    {
        $contact = Contact::find($id);
        if (!$contact) {
            $this->flash('error', 'Заявку не знайдено');
            $this->redirect('/admin/contacts');
            return;
        }

        Contact::markAsRead($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notes = $this->sanitize($this->post('notes'));
            Contact::markAsProcessed($id, $notes);
            $this->flash('success', 'Заявку оброблено');
            $this->redirect('/admin/contacts');
        }

        $this->view('admin.contacts.view', [
            'pageTitle' => 'Перегляд заявки',
            'contact' => $contact,
        ]);
    }

    public function contactDelete(int $id): void
    {
        Contact::delete($id);
        $this->flash('success', 'Заявку видалено');
        $this->redirect('/admin/contacts');
    }

    // Pages Management
    public function pages(): void
    {
        $pages = Page::all();
        $this->view('admin.pages.index', [
            'pageTitle' => 'Управління сторінками',
            'pages' => $pages,
        ]);
    }

    public function pageEdit(int $id): void
    {
        $page = Page::find($id);
        if (!$page) {
            $this->flash('error', 'Сторінку не знайдено');
            $this->redirect('/admin/pages');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $this->sanitize($this->post('title')),
                'content' => $this->post('content'),
                'meta_title' => $this->sanitize($this->post('meta_title')),
                'meta_description' => $this->sanitize($this->post('meta_description')),
                'is_active' => (int)$this->post('is_active', 1),
            ];

            Page::update($id, $data);
            $this->flash('success', 'Сторінку оновлено');
            $this->redirect('/admin/pages');
        }

        $this->view('admin.pages.edit', [
            'pageTitle' => 'Редагувати сторінку',
            'page' => $page,
        ]);
    }

    // Settings Management
    public function settings(): void
    {
        $settings = Setting::getAllForAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'setting_') === 0) {
                    $settingKey = substr($key, 8);
                    Setting::set($settingKey, $this->sanitize($value));
                }
            }
            $this->flash('success', 'Налаштування збережено');
            $this->redirect('/admin/settings');
        }

        $this->view('admin.settings.index', [
            'pageTitle' => 'Налаштування сайту',
            'settings' => $settings,
        ]);
    }
}
