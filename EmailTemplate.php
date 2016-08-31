<?php

class EmailTemplate
{
    private $templatePath = '';

    private $variables = [];

    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }

    public function parse()
    {
        ob_start();
        extract($this->variables);
        include $this->templatePath;

        return ob_get_clean();
    }
}
