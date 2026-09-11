<?php
declare(strict_types=1);

class GalleryController extends Controller
{
    public function index(array $params = []): void
    {
        $this->redirect('/projects');
    }
}
