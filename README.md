# Veiw

MPPHP view library.

A view is the file that contains the code that gets printed on the browser period. And the MVC architecture demands that we keep our business logic away from these view files. Traditionally beginners i thought to make database calls or process their forms on the same file as the form:

~~~
<?php 
    if (isset($_POST['submit'])) {
        // Process the form...
        // ...and maybe redirect to somewhere else if they succeed.
        // Else display the for again
    }
?>

<form method="post">
    // Required inputs

    <input type="submit" name="submit" value="Sign in">
</form>
~~~

This would be a bad idea because now your View now has knowledge of your backend code. Now most people at this point would say theu write their logic in a different file and include it like so:

~~~
<?php  include ('/form-processing.php') ?>

<form method="post">
    // Required inputs

    <input type="submit" name="submit" value="Sign in">
</form>
~~~

Well technically, the two versions are the same, the view is still aware of the back end logic.

There are lots of third party Templating Engines out there like Smarty, Latte, Mustache, but most PHP frameworks come bundled with their own Templating engine, for example Symfony has Twig, Laravel has Blade and Phalcon has Volt. And MpPHP well, it just has a single file containing functions that mimic the important features of a Templating Engine  like Inheritance and Sections/Blocks that we like to call VIEW.

## Defining A Layout

Two of the primary benefits of using a templating engine are template inheritance and sections. To get started, let's take a look at a simple example. First, we will examine a "master" page layout. Since most web applications maintain the same general layout across various pages, it's convenient to define this layout as a single Blade view:

```
<!-- Stored in resources/views/layouts/app.phtml -->

<html>
    <head>
        <title>App Name - <?= _view__nest('title') ?></title>
    </head>
    <body>

        <?= _view__include('sidebar') ?>

        <div class="container">
            <?= _view__nest('content') ?>
        </div>
    </body>
</html> 
```

As you can see, this file contains typical HTML mark-up. However, take note of the _view__nest() and the _view__include() functions. The _view__include function, as the name implies, includes a template, while the _view__nest() function is used to display the contents of a given section.

Now that we have defined a layout for our application, let's define a child page that inherits the layout.

## Extending A Layout

When defining a child vtemplate, use the _view__extends() function to specify which layout the child template should "inherit". Templates which extend a layout may inject content into the parent template by  wrapping its content in a function with the same name passed into it's parents _view__nest() function. Remember, as seen in the example above, the contents of these sections will be displayed in the layout _view__nest('content'):

~~~
<!-- Stored in resources/views/child.phtml -->

<?php _view__extends('layouts/app') ?>

<?php function title() { ?>
    Child Page
<?php } ?>

<?php function content() { ?>
    <p>This is my body content.</p>
<?php } ?>
~~~

`From this section down, we would be talking about best practices for using PHP in a view or a template file.`

## Displaying Data

To display a PHP value to the browser, we would use `echo` or `print` but if the only thing your doing withing a PHP opening an closing tag is an `echo` or `print` you can use the shorthand version like so:

~~~
<?= $data ?>
~~~

Much simpler than:
~~~
<?php print $data ?> or <?php echo $data ?>
~~~
 Dont you think?

## Control Structures

It is common for young developers to mix their view with business logic in a way that makes it impossible for a front end developer who has little knowledge of PHP to work with their projects, for example it is comon to find a view that has the following code:

~~~
<body>

    <?php

    foreach ($data as $info) {
        $output  = '<div>';
        $output .= '<p>'. $info . '</p>';
        $output .= '</div>;
    }

    echo $output;
    ?>

</body>


~~~

If your working on a school project with your friends and your job is at the back end of things, i guess we can all agree the above could be alot for the person handling the front. A cleaner way to get this do would be to drop out of PHP as soon as we don't need it:

~~~
<body>

    <?php foreach ($data as $info) { ?>
        <div>
            <p> 
                <?= $info ?>
            </p>
        </div>
    <?php } ?>

</body>
~~~

This would be easier for any front end personel to work on your project without having to worry about  PHP, I mean it's there but they can easily be ignored compared to the previous version.

This step would work for both Control structures and logical expressions likewise.