<?php
namespace Core;

/**
 * Class View
 * @package Core
 */
class View
{
    /**
     * Render template.
     *
     * @param string $template
     * @param array $args
     * 
     * @return mixed
     */
    public static function render(string $template, array $args = [])
    {
        $file = dirname(__DIR__) . "/App/Views/$template";

        if (is_readable($file)) {
            ob_start();
            extract($args);

            include $file;
            
            echo ob_get_clean();
        } else {
            throw new \Exception("$file not found");
        }
    }
}