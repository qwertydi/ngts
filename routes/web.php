<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
Route::get('/', 'WelcomeController@welcome')->name('welcome');

// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'Auth\ActivateController@exceeded']);

    // Socialite 'Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity']], function () {
    // Activation Routes
    Route::get('/activation-required', ['uses' => 'Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'UserController@index']);

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/devices', ['uses' => 'DevicesController@devicesByUser'])->name('devices');

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/devices/add', ['uses' => 'DevicesController@addDevice'])->name('devices.add');

    //  Video Streaming
    Route::get('/stream', ['uses' => 'VideoController@streamVideo'])->name('stream');

    // Show users profile - viewable by other users.
    Route::get('/devices/{id}', [
        'as'   => '{id}',
        'uses' => 'DevicesController@deviceHistory',
    ]);

    Route::get('/devices/{id}/surveillance', [ 'uses' => 'DevicesController@surveillance'])->name('surveillance');

    Route::get('/devices/{id}/surveillance/picture', [ 'uses' => 'DevicesController@pictureButton'])->name('surveillance.picture');

    Route::get('/alarms', [ 'uses' => 'DevicesController@alarms'])->name('alarms');

    Route::get('/alarms/add', [ 'uses' => 'DevicesController@addAlarms'])->name('alarms.add');

    Route::delete('/alarms/{id}/delete', [ 'uses' => 'DevicesController@deleteAlarm'])->name('alarms.delete');

    Route::get('/alarms/{id}/edit', [ 'uses' => 'DevicesController@editAlarm'])->name('alarms.edit');

    Route::put('/alarms/{id}/updateAlarm', ['uses' => 'DevicesController@updateUserAccount'])->name('alarms.update');

    Route::post('/alarms/store', [ 'uses' => 'DevicesController@alarmStore']);
    
    Route::post('/devices/store', [ 'uses' => 'DevicesController@store']);

    Route::post('/email', [ 'uses' => 'DevicesController@sendMail'])->name('email');
    
    Route::delete('/devices/deleteDevice/{id}', [ 'uses' => 'DevicesController@deleteDevice'])->name('delete.device');

    Route::delete('/devices/{id}/deleteHistory/{m_id}', [ 'uses' => 'DevicesController@motionDeleteHistory'])->name('delete.motion');

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@show',
    ]);
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        'ProfilesController', [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity']], function () {
    Route::resource('/users/deleted', 'SoftDeletesController', [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', 'UsersManagementController', [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::resource('themes', 'ThemesManagementController', [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('php', 'AdminDetailsController@listPHPInfo');
    Route::get('routes', 'AdminDetailsController@listRoutes');
});
