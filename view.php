<?php

/**
 * This function searches the application for the location
 * of the template using the configuration passed as the second
 * argument as a navigation.
 * 
 * @param string $template <p> The template that should be rendered
 * </p>
 * @param array $config <p> The config hold the path to the view
 * template. </p>
 * 
 * @return string <p> Return the
 */
function _view__resolve_directory(string $template, array $config):string
{
    // We strip the file of .phtml extension in case the user added it.
    // as you read futher down you will notice we add .phtml to the
    // template and we woldn't want to end up with a file that has
    // double extension like so: template.phtml.phtml.
    $stripped_template = str_replace('.phtml', '', $template);

    // The next four lines of code helps navigate other external directories
    // specifically in external packages.
    // To load a template file located in another package, the template is 
    // called like so: "packagename::template".
    // Technically we can call a template from our "resources/views" directory
    // like so "app::template" and our view would still work because that is 
    // the same exact thing we are trying to achieve on code line 44.
    if (strpos($stripped_template, '::') > 0) {
        // so we decouple our package name from the template name
        // using "::" as a delimeter which should return an array
        // that has the value on the left side of the "::" inposition "0"
        // and the value on the right on position "1" like so:
        // ['packagename', 'template'] then we asign it to a
        // $decoupled_template variable.
        $decoupled_template = explode('::', $stripped_template);

        // Finally return a full file path to our template.
        return $config[$decoupled_template[0]]['views'] . '/' . $decoupled_template[1] . '.phtml';
    }

    // Finally return a full file path to our template.
    return $config['app']['views'] . '/' . $stripped_template . '.phtml';
}

/**
 * This function will iclude the view we want to nest 
 * the corrent view in. In a perfect wourld of OOP
 * templating, this is called Template Inheritance.
 * 
 * @param string $template <p> The template that should be rendered
 * </p>
 * @return void
 */
function _view__extends(string $template):void
{
    // Make the $app available in this scope.
    global $app;

    // Find the view requested and require it.
    require _view__resolve_directory($template, $app['configs']);
}

/**
 * When a template (child) extends another template (parent)
 * the template (parent) extended will nest the child by
 * passing in the child function name.
 * 
 * @param string $template <p> The template that should be rendered
 * </p>
 * @return mixed
 */
function _view__nest(string $template)
{
    // call_user_function basical calls the argument passed as a function
    // so call_user_function('child_template') is equivalent to "child_template()".
    return call_user_func($template);
}

/**
 * Include a template into another template.
 *
 * @param string $template
 * @param array $vars
 * @return void
 */
function _view__include(string $template, array $vars = [])
{
    // Make the $app available in this scope.
    global $app;

    // Extract the variables and have them available for our view.
    extract($vars);

    require _view__resolve_directory($template, $app['configs']);
}

/**
 * This function is used to include our template in
 * our controller.
 * 
 * @param string $template <p> The template that should be rendered
 * </p>
 * @param array $vars <p> The variables that should be passed into the view.
 * </p>
 */
function _view(string $template, array $vars = [])
{
    // Make the $app available in this scope.
    global $app;

    // Extract the variables and have them available for our view.
    extract($vars);

    // Require our template.
    require _view__resolve_directory($template, $app['configs']);
}
