<?php
declare(strict_types=1);

class ContactController extends Controller
{
    public function index(array $params = []): void
    {
        $this->redirect('/');
    }

    public function submit(array $params = []): void
    {
        CSRF::check();

        $name    = $this->sanitize($this->post('name', ''));
        $email   = filter_var((string)$this->post('email', ''), FILTER_VALIDATE_EMAIL);
        $phone   = $this->sanitize($this->post('phone', ''));
        $subject = $this->sanitize($this->post('subject', 'General enquiry'));
        $message = trim(strip_tags((string)$this->post('message', '')));
        $trap    = trim((string)$this->post('website', ''));

        if ($trap !== '') {
            $this->redirect('/');
        }

        if ($name === '' || !$email || $message === '') {
            Session::flash('error', 'Please enter your name, a valid email, and your message — or reach us on WhatsApp.');
            $this->redirect('/');
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        try {
            $model = new MessageModel();
            if (!$model->rateLimitCheck($ip)) {
                Session::flash('error', 'Please wait before sending another message.');
                $this->redirect('/');
            }

            $model->insert([
                'name'       => $name,
                'email'      => (string)$email,
                'phone'      => $phone,
                'subject'    => $subject,
                'message'    => $message,
                'ip_address' => $ip,
            ]);
        } catch (\Throwable $e) {
            Production::log('contact submit: ' . $e->getMessage());
            Session::flash('error', 'We could not save your message just now. Please try WhatsApp or email.');
            $this->redirect('/');
        }

        Session::flash('success', 'Thank you. We have your message and will reply shortly. WhatsApp is fastest if you need us today.');
        $this->redirect('/');
    }
}
