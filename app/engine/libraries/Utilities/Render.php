<?php

namespace Engine\Libraries\Utilities;

class Render {

    private $sections = [];
    private $layout = null;
    private $currentSection = null;
    private string $path;

    protected string $content = '';

    public function __construct(string $view, array $data = [], array $sections = []) {
        $this->path = $path = PATH . "app/src/views/$view.php";
        if (!file_exists($path)) {
            throw new \RuntimeException("File doesn't exist at path {$path}.");
        }
        $this->sections = $sections;
        $this->content = $this->fetchContent($data);
    }

    protected function fetchContent(array $data): string {
        ob_start();
        (function () use ($data) {
            extract($data, EXTR_SKIP);
            unset($data);
            include $this->path;
        })();
        $content = ob_get_clean();

        if ($this->layout) {
            $layoutEngine = new Render(
                view: $this->layout,
                data: [],
                sections: $this->sections
            );
            return $layoutEngine->getContent();
        }

        return $content;
    }

    public function extends($layout): void {
        $this->layout = $layout;
    }

    public function section($name, $content = null): void {
        if ($content !== null) {
            $this->sections[$name] = $content;
        } else {
            $this->currentSection = $name;
            ob_start();
        }
    }

    public function endSection(): void {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
    }

    public function getSection($section, $default = ''): string {
        return isset($this->sections[$section]) ? $this->sections[$section] : $default;
    }

    public function getContent(): string {
        return $this->content;
    }
}
