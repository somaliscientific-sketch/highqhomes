<?php
declare(strict_types=1);

class ContactController extends Controller
{
    public function index(array $params = []): void
    {
        $settings = [];
        $sections = [];
        $seo      = [
            'meta_title' => 'Contact HighQ Homes',
            'meta_description' => 'Contact HighQ Homes for construction, architecture, interiors, paints, and project consultation.',
        ];

        $this->tryLoad(function () use (&$settings, &$sections, &$seo): void {
            $settings = (new SettingModel())->getAllAsMap();
            $sections = (new PageSectionModel())->getByPage('contact');
            $seo      = (new SeoModel())->findBySlug('contact') ?? $seo;
        });

        $this->render('contact/index', compact('settings', 'seo', 'sections'));
    }

    public function submit(array $params = []): void
    {
        CSRF::check();

        $name    = $this->sanitize($this->post('name', ''));
        $email   = filter_var((string)$this->post('email', ''), FILTER_VALIDATE_EMAIL);
        $phone   = $this->sanitize($this->post('phone', ''));
        $subject = $this->sanitize($this->post('subject', 'Project Consultation'));
        $message = trim(strip_tags((string)$this->post('message', '')));
        $trap    = trim((string)$this->post('website', ''));

        if ($trap !== '') {
            $this->redirect('/contact');
        }

        if ($name === '' || !$email || $message === '') {
            Session::flash('error', 'Please enter your name, a valid email, and your message.');
            $this->redirect('/contact');
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        try {
            $model = new MessageModel();
            if (!$model->rateLimitCheck($ip)) {
                Session::flash('error', 'Please wait before sending another message.');
                $this->redirect('/contact');
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
            Session::flash('error', 'We could not save your message right now. Please try WhatsApp or email.');
            $this->redirect('/contact');
        }

        Session::flash('success', 'Thank you. Your message has been sent to our team.');
        $this->redirect('/contact');
    }
}
