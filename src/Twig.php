<?php
namespace WPTwig;

use Twig_Environment;
use Twig_Loader_filesystem;
use Twig_Function;

/**
 * Manage the Twig set up and teardown
 */
class Twig
{

    /**
     * The twig instance we are using to render
     */
    private $twig = null;

    public function __construct($viewpath = null, $cache = false, $debug = false)
    {
        $viewpath = $viewpath ?: dirname(__DIR__). '/views';
        $this->twig = new \Twig_Environment(
            new \Twig_Loader_filesystem($viewpath),
            [
                // 'cache' => __DIR__ . '/cache',
                'cache' => $cache,
                'debug' => $debug,

            ]
        );
        $this->addWordpressFunctions();
    }

    private function addWordpressFunctions()
    {
        $this->twig->addFunction(new Twig_Function('settings_fields', function ($name) {
            return settings_fields($name);
        }));
        $this->twig->addFunction(new Twig_Function('do_settings_sections', function ($name) {
            return do_settings_sections($name);
        }));
        $this->twig->addFunction(new Twig_Function('get_option', function ($name) {
            return get_option($name);
        }));
    }

    public function getTwig()
    {
        return $this->twig;
    }

    public function render($path, $attributes = false)
    {
        if ($attributes) {
            return $this->twig->render($path, $attributes);
        } else {
            return $this->twig->render($path);
        }
    }
}
