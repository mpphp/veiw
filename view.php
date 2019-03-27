<?php

/**
 * @param string $template
 * @param array $config
 * @return string
 */
function view__resolve_directory(string $template, array $config):string
{
    $stripped_template = str_replace('.phtml', '', $template);

    if (strpos($stripped_template, '::') > 0) {
        $decoupled_template = explode('::', $stripped_template);

        return $config[$decoupled_template[0]]['views'] . '/' . $decoupled_template[1] . '.phtml';
    }

    return $config['app']['views'] . '/' . $stripped_template . '.phtml';
}

/**
 * @param string $template
 */
function view__extends(string $template)
{
    global $app;
    require view__resolve_directory($template, $app['configs']);
}

/**
 * @param string $template
 * @return mixed
 */
function view__nests(string $template)
{
    return call_user_func($template);
}

/**
 * @param string $template
 * @param array $vars
 */
function view(string $template, array $vars = [])
{
    global $app;
    extract($vars);
    require view__resolve_directory($template, $app['configs']);
}
