<?php
namespace WPTwig;

use Twig_Environment;
use Twig_Loader_filesystem;
use Twig_SimpleFunction;

/**
 * Manage the Twig set up and teardown
 */
class Twig
{

    /**
     * The twig instance we are using to render
     */
    private $twig = null;

    public function __construct($viewpath, $cache = false, $debug = false, array $mappings = [])
    {
        if (!isset($viewpath)) {
            throw new Exception("The view path must be set");
        }
        $this->twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem($viewpath),
            [
                'cache' => $cache,
                'debug' => $debug,

            ]
        );
        $this->addWordpressFunctions($mappings);
    }

    private function addWordpressFunctions()
    {
        $this->twig->addFunction(new Twig_SimpleFunction('settings_fields', function ($name) {
            return settings_fields($name);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('do_settings_sections', function ($name) {
            return do_settings_sections($name);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('get_option', function ($name) {
            return get_option($name);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('get_the_post_thumbnail', function ($post, $size = 'medium', $attr = '') {
            return get_the_post_thumbnail($post, $size, $attr);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('setup_postdata', function ($post) {
            return setup_postdata($post);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('wp_nonce_field', function ($action = -1, $name = '_wpnonce', $referrer = true) {
            return wp_nonce_field($action, $name, $referrer, false);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('get_post_meta', function ($post, $name, $single) {
            return get_post_meta($post, $name, $single);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('get_the_title', function ($post = 0) {
            return get_the_title($post);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('has_post_thumbnail', function ($post = null) {
            return has_post_thumbnail($post);
        }));
        $this->twig->addFunction(new Twig_SimpleFunction('submit_button', function () {
            return submit_button();
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
