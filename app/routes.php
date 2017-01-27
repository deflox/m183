<?php

$app->get('/', 'PageController:getIndex')->setName('index');

/*
 * Movies Secure
 */
$app->group('', function() {

    $this->get('/movies/secure', 'MovieSecureController:getMoviesSecure')->setName('movies-secure');

    $this->get('/movies/insecure', 'MovieSecureController:getMoviesInsecure')->setName('movies-insecure');

})->add(new App\Middleware\AuthSecureMiddleware($container));

$app->group('', function() {

    $this->post('/movies/secure/api/add/movie', 'MovieSecureController:addMovie')->setName('movies-secure-add-movie');
    $this->get('/movies/secure/api/delete/movie/{id}', 'MovieSecureController:deleteMovieSecure')->setName('movies-secure-delete-movie');

    $this->get('/movies/insecure/api/delete/movie/{id}', 'MovieSecureController:deleteMovieInsecure')->setName('movies-insecure-delete-movie');

})->add(new App\Middleware\AjaxMiddleware($container));

/*
 * Auth secure
 */
$app->group('/auth/secure', function() {

    $this->get('/signin', 'AuthSecureController:getSignIn')->setName('auth-secure-sign-in');
    $this->post('/signin', 'AuthSecureController:postSignIn');

    $this->get('/signup', 'AuthSecureController:getSignUp')->setName('auth-secure-sign-up');
    $this->post('/signup', 'AuthSecureController:postSignUp');

    $this->get('/forgot-password', 'AuthSecureController:getForgotPassword')->setName('auth-secure-forgot-password');
    $this->post('/forgot-password', 'AuthSecureController:postForgotPassword');

    $this->get('/reset/{token}', 'AuthSecureController:getResetPassword')->setName('auth-secure-reset-password');
    $this->post('/reset/{token}', 'AuthSecureController:postResetPassword');

})->add(new App\Middleware\RedirectIfLoggedInMiddleware($container));

$app->get('/signout', 'AuthSecureController:getSignOut')->setName('auth-secure-sign-out');

/*
 * Auth insecure
 */
$app->group('/auth/insecure', function() {

    $this->get('/signin', 'AuthSecureController:getSignIn')->setName('auth-insecure-sign-in');
    $this->post('/signin', 'AuthSecureController:postSignIn');

    $this->get('/signup', 'AuthSecureController:getSignUp')->setName('auth-insecure-sign-up');
    $this->post('/signup', 'AuthSecureController:postSignUp');

});

