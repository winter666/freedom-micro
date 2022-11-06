<?php


namespace Freedom\Modules\Render;



class Layout
{
    public static function view(string $layout, array $templates): Render {
        $root = preg_replace('/public/', '', $_SERVER['DOCUMENT_ROOT'], 1);
        $__layout = $root . 'resources/layouts/'. $layout .  '/index.php';
        if (!file_exists($__layout)) {
            throw new \Exception('Layout doesn\'t found!');
        }

        $__templates = [];
        foreach ($templates as $name => $template) {
            $__templates[$name] = $root . 'resources/layouts/' . $layout . '/' . preg_replace('/[.]/', '/', $template) . '.php';

            if (!file_exists($__templates[$name])) {
                throw new \Exception('Template `'.$name.'` doesn\'t found!');
            }
        }

        return new Render($__layout, $__templates);
    }
}
