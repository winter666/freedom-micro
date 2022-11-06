<?php


namespace Freedom\Modules\Render;


class Render
{
    private string $layout;
    private array $templates;
    private array $vars;

    public function __construct(string $layout, array $templates)
    {
        $this->layout = $layout;
        $this->templates = $templates;
    }

    public function render(array $vars = [])
    {
        $this->vars = $vars;
        ob_start();
        foreach ($this->vars as $varName => $varVal) {
            if (preg_match('/[a-zA-Z]+/', $varName)) {
                $$varName = $varVal;
            }
        }
        include $this->layout;
        echo ob_get_clean();
    }

    public function renderTemplate(string $name)
    {
        ob_start();
        foreach ($this->vars as $varName => $varVal) {
            if (preg_match('/[a-zA-Z]+/', $varName)) {
                $$varName = $varVal;
            }
        }
        include $this->templates[$name];
        return ob_get_clean();
    }
}
