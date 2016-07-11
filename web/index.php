<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

$config = Spyc::YAMLLoad(__DIR__.'/../config/config.yaml'); // Load config.

$app = new Silex\Application();

// Uncomment the line below while debugging your app.
// $app['debug'] = true;

// Twig initialization.
$loader = new Twig_Loader_Filesystem(__DIR__.'/../templates');
$twig = new Twig_Environment($loader, array(
    'cache' => __DIR__.'/../cache',
));

/*
 * Route actions.
 */

$app->get('/', function () use ($twig, $config) {
    return $twig->render('index.html.twig', $config);
});

$app->get('/contact', function () use ($twig, $config) {
    return $twig->render('contact.html.twig', $config);
});

$app->post('/contact', function () use ($twig, $config) {
    // Collect form fields.
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validate form.
    $errors = [];
    if (strlen($name) < 2) {
        $errors[] = 'The name you provide needs to 2 or more characters in length.';
    }
    if (strlen($message) < 30) {
        $errors[] = 'The message you submit needs to be 30 or more characters in length.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'The email address you provided is invalid.';
    }

    // Send message if no errors.
    if (sizeof($errors) == 0) {
        mail($config['contact_form_target'], 'Website contact', "Name: $name\r\nEmail: $email\r\nMessage: $message");
    }

    // Render contact page with success/error message.
    $vars = array_merge($config, array(
        'success' => sizeof($errors) == 0,
        'errors' => $errors,
        'post' => $_POST
    ));
    return $twig->render('contact.html.twig', $vars);
});

$app->get('/linkedin', function () use ($twig, $config) {
    header('Location: ' . $config['linkedin_url']);
    die();
});

$app->get('/twitter', function () use ($twig, $config) {
    header('Location: ' . $config['twitter_url']);
    die();
});

$app->get('/github', function () use ($twig, $config) {
    header('Location: ' . $config['github_url']);
    die();
});

$app->get('/project-1', function () use ($twig, $config) {
    return $twig->render('project-1.html.twig', $config);
});

$app->get('/project-2', function () use ($twig, $config) {
    return $twig->render('project-2.html.twig', $config);
});

$app->get('/project-3', function () use ($twig, $config) {
    return $twig->render('project-3.html.twig', $config);
});

$app->get('/blog', function () use ($twig, $config) {
    return $twig->render('blog.html.twig', $config);
});

$app->run();