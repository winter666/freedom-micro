<?php


namespace Freedom\Modules\Render;


use Freedom\Modules\Render\Exceptions\TemplateNotFoundException;

class Render
{
    private string $layout;
    private array $templates;
    private array $vars;
    private array $scripts = [];

    public function __construct(string $layout, array $templates, array $vars = [])
    {
        $this->layout = $layout;
        $this->templates = $templates;
        $this->vars = $vars;
    }

    public function render(array $vars = [])
    {
        if (!empty($vars)) {
            $this->vars = $vars;
        }

        ob_start();
        foreach ($this->vars as $varName => $varVal) {
            if (preg_match('/[a-zA-Z]+/', $varName)) {
                $$varName = $varVal;
            }
        }
        include $this->layout;
        echo ob_get_clean();
    }

    public function hasTemplate(string $name): bool
    {
        return isset($this->templates[$name]);
    }

    public function renderTemplate(string $name): string
    {
        if (!isset($this->templates[$name])) {
            throw new TemplateNotFoundException($name);
        }

        ob_start();
        foreach ($this->vars as $varName => $varVal) {
            if (preg_match('/[a-zA-Z]+/', $varName)) {
                $$varName = $varVal;
            }
        }

        include $this->templates[$name];
        return ob_get_clean();
    }

    public function addJs(string $name, string $path, string $source_dir = 'public')
    {
        $root = $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER['HTTP_HOST'] . '/';
        $file = preg_replace('/[.]/', '/', $path) . '.js';
        $__full = $root . $file;
        $this->scripts[$name][] = $__full;
    }

    public function yieldJs(string $name)
    {
        foreach ($this->scripts[$name] ?? [] as $script) {
            echo "<script src=\"{$script}\"></script>";
        }
    }
}
