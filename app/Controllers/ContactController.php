<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Contact;
use App\Models\Review;

class ContactController extends Controller
{
    public function submit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contact');
            return;
        }

        $data = [
            'company_name' => $this->sanitize($this->post('company_name')),
            'contact_name' => $this->sanitize($this->post('contact_name')),
            'email' => $this->sanitize($this->post('email')),
            'phone' => $this->sanitize($this->post('phone')),
            'subject' => $this->sanitize($this->post('subject')),
            'message' => $this->sanitize($this->post('message')),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];

        $errors = $this->validate($data, [
            'company_name' => 'required|min:2|max:200',
            'contact_name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'required|min:10|max:20',
            'message' => 'required|min:10',
        ]);

        if (!empty($errors)) {
            $this->flash('errors', $errors);
            $this->flash('old', $data);
            $this->back();
            return;
        }

        try {
            Contact::create($data);
            $this->flash('success', 'Дякуємо за звернення! Ми зв\'яжемося з вами найближчим часом.');
            $this->redirect('/contact');
        } catch (\Exception $e) {
            $this->flash('error', 'Виникла помилка. Спробуйте пізніше.');
            $this->back();
        }
    }

    public function submitReview(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reviews');
            return;
        }

        $data = [
            'company_name' => $this->sanitize($this->post('company_name')),
            'author_name' => $this->sanitize($this->post('author_name')),
            'author_position' => $this->sanitize($this->post('author_position')),
            'email' => $this->sanitize($this->post('email')),
            'phone' => $this->sanitize($this->post('phone')),
            'rating' => (int)$this->post('rating', 5),
            'content' => $this->sanitize($this->post('content')),
            'is_approved' => 0,
        ];

        $errors = $this->validate($data, [
            'company_name' => 'required|min:2|max:200',
            'author_name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'content' => 'required|min:20',
        ]);

        if (!empty($errors)) {
            $this->flash('errors', $errors);
            $this->flash('old', $data);
            $this->back();
            return;
        }

        try {
            Review::create($data);
            $this->flash('success', 'Дякуємо за відгук! Він буде опублікований після модерації.');
            $this->redirect('/reviews');
        } catch (\Exception $e) {
            $this->flash('error', 'Виникла помилка. Спробуйте пізніше.');
            $this->back();
        }
    }
}
