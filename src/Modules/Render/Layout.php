<?php


namespace Freedom\Modules\Render;



use Freedom\Modules\Render\Exceptions\LayoutNotFoundException;
use Freedom\Modules\Render\Exceptions\TemplateNotFoundException;

class Layout
{
    public static function view(string $layout, array $templates, array $bind = []): Render {
        $root = preg_replace('/public/', '', $_SERVER['DOCUMENT_ROOT'], 1);
        $__layout = $root . 'resources/layouts/'. $layout .  '/index.php';
        if (!file_exists($__layout)) {
            throw new LayoutNotFoundException($layout);
        }

        $__templates = [];
        foreach ($templates as $name => $template) {
            $__templates[$name] = $root . 'resources/layouts/' . $layout . '/' . preg_replace('/[.]/', '/', $template) . '.php';

            if (!file_exists($__templates[$name])) {
                throw new TemplateNotFoundException($name);
            }
        }

        return new Render($__layout, $__templates, $bind);
    }
}
