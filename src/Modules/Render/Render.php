<?php


namespace Freedom\Modules\Render;


use Freedom\Modules\Render\Exceptions\TemplateNotFoundException;

class Render
{
    private string $layout;
    private array $templates;
    private array $vars;
    private array $scripts = [];
    private array $styles = [];

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

    public function addJs(string $group, string $path)
    {
        $this->scripts[$group][] =$this->formatFrontendItem($path, 'js');
    }

    public function addCss(string $group, string $path)
    {
        $this->styles[$group][] = $this->formatFrontendItem($path, 'css');
    }

    private function formatFrontendItem(string $path, string $ext): string
    {
        $root = $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' . $_SERVER['HTTP_HOST'] . '/';
        $file = preg_replace('/[.]/', '/', $path) . '.' . $ext;
        return $root . $file;
    }

    public function yieldJs(string $group)
    {
        foreach ($this->scripts[$group] ?? [] as $script) {
            echo "<script src=\"{$script}\"></script>";
        }
    }

    public function yieldCss(string $group)
    {
        foreach ($this->styles[$group] ?? [] as $style) {
            echo "<link rel=\"stylesheet\" href=\"{$style}\"/>";
        }
    }
}
