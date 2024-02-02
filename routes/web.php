<?php

use App\Models\Page;
use Illuminate\Support\Facades\Route;
URL::forcescheme('https');
use App\Http\Controllers\TestimonialController;
// Route::get('/', function () {
//     $page = Page::find(1);
//     return view('index',compact('page'));
// })->name('home');

Route::get('/', [App\Http\Controllers\LoginController::class, 'login']);
// Route::get('/', [App\Http\Controllers\LoginController::class, 'admin'])->name('home');
Route::post('/ajax', [App\Http\Controllers\Admin\AjaxController::class, 'ajax'])->name('ajax')->middleware('isAdmin');

Route::get('/lang/{lang}', [App\Http\Controllers\LangController::class, 'lang'])->name('lang');

Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
Route::get('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\LoginController::class, 'loginCheck'])->name('login.check');
Route::get('/register', [App\Http\Controllers\LoginController::class, 'registerUser'])->name('register.user');
Route::post('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register');

Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {
   
    Route::get('/home', [App\Http\Controllers\LoginController::class, 'admin'])->name('home');
    Route::post('/media/storeMedia', [App\Http\Controllers\Admin\FileController::class, 'storeMedia'])->name('media.storeMedia');
    Route::post('/gallery/storeMedia', [App\Http\Controllers\Admin\GalleryController::class, 'storeMedia'])->name('gallery.storeMedia');
    Route::resource('/media', 'App\Http\Controllers\Admin\FileController');
    Route::resource('/gallery', 'App\Http\Controllers\Admin\GalleryController');
    Route::resource('/category', 'App\Http\Controllers\Admin\CategoryController');
    Route::resource('/slide', 'App\Http\Controllers\Admin\SlideController');

    Route::get('/article/switch', [App\Http\Controllers\Admin\ArticleController::class, 'switch'])->name('article.switch');
    Route::get('/article/trash', [App\Http\Controllers\Admin\ArticleController::class, 'trash'])->name('article.trash');
    Route::get('/article/delete/{id}', [App\Http\Controllers\Admin\ArticleController::class, 'delete'])->name('article.delete');
    Route::get('/article/recover/{id}', [App\Http\Controllers\Admin\ArticleController::class, 'recover'])->name('article.recover');
    Route::resource('/article', 'App\Http\Controllers\Admin\ArticleController');


    Route::get('/newsletter/switch', [App\Http\Controllers\Admin\NewsletterController::class, 'switch'])->name('newsletter.switch');
    Route::get('/newsletter/trash', [App\Http\Controllers\Admin\NewsletterController::class, 'trash'])->name('newsletter.trash');
    Route::get('/newsletter/delete/{id}', [App\Http\Controllers\Admin\NewsletterController::class, 'delete'])->name('newsletter.delete');
    Route::get('/newsletter/recover/{id}', [App\Http\Controllers\Admin\NewsletterController::class, 'recover'])->name('newsletter.recover');

    Route::get('/social/Addmedia', [App\Http\Controllers\Admin\SocialmediaController::class, 'index'])->name('social.index');
    Route::get('/social/Editmedia', [App\Http\Controllers\Admin\SocialmediaController::class, 'edit'])->name('social.edit');


    Route::post('/socialmediastore', [App\Http\Controllers\Admin\SocialmediaController::class, 'socialStore'])->name('socialStore');

    Route::post('/socialupdate', [App\Http\Controllers\Admin\SocialmediaController::class, 'socialupdate'])->name('socialupdate');


   Route::resource('/newletter', 'App\Http\Controllers\Admin\NewsletterController');

    Route::resource('/resource', 'App\Http\Controllers\Admin\ResourceController');

    Route::get('/resource/switch', [App\Http\Controllers\Admin\ResourceController::class, 'show'])->name('resource.switch');
   
    Route::get('/resource/get/trash', [App\Http\Controllers\Admin\ResourceController::class, 'gettrash'])->name('resource.trash');

    Route::get('/resource/delete/{id}', [App\Http\Controllers\Admin\ResourceController::class, 'delete'])->name('resource.delete');
    Route::get('/resource/recover/{id}', [App\Http\Controllers\Admin\ResourceController::class, 'recover'])->name('resource.recover');

    Route::get('/ourteam/switch', [App\Http\Controllers\Admin\OurteamController::class, 'switch'])->name('ourteam.switch');
    Route::get('/ourteam/trash', [App\Http\Controllers\Admin\OurteamController::class, 'trash'])->name('ourteam.trash');
    Route::get('/ourteam/delete/{id}', [App\Http\Controllers\Admin\OurteamController::class, 'delete'])->name('ourteam.delete');
    Route::get('/ourteam/recover/{id}', [App\Http\Controllers\Admin\OurteamController::class, 'recover'])->name('ourteam.recover');
    Route::resource('/ourteam', 'App\Http\Controllers\Admin\OurteamController');



    Route::get('/page/switch', [App\Http\Controllers\Admin\PageController::class, 'switch'])->name('page.switch');
    Route::get('/page/trash', [App\Http\Controllers\Admin\PageController::class, 'trash'])->name('page.trash');
    Route::get('/page/delete/{id}', [App\Http\Controllers\Admin\PageController::class, 'delete'])->name('page.delete');
    Route::get('/page/recover/{id}', [App\Http\Controllers\Admin\PageController::class, 'recover'])->name('page.recover');
    Route::resource('/page', 'App\Http\Controllers\Admin\PageController');

    Route::get('/comment/switch', [App\Http\Controllers\Admin\CommentController::class, 'switch'])->name('comment.switch');
    Route::get('/comment/trash', [App\Http\Controllers\Admin\CommentController::class, 'trash'])->name('comment.trash');
    Route::get('/comment/delete/{id}', [App\Http\Controllers\Admin\CommentController::class, 'delete'])->name('comment.delete');
    Route::get('/comment/recover/{id}', [App\Http\Controllers\Admin\CommentController::class, 'recover'])->name('comment.recover');
    Route::resource('/comment', 'App\Http\Controllers\Admin\CommentController');
    Route::resource('/contact', 'App\Http\Controllers\ContactController');
    Route::get('/contact/delete/{id}',[App\Http\Controllers\ContactController::class,'delete'])->name('contact.delete');
    Route::get('/contact/trash', [App\Http\Controllers\ContactController::class, 'trashed'])->name('contact.trash');
    Route::get('/contact/sendmail/{id}', [App\Http\Controllers\ContactController::class, 'sendmail'])->name('contact.sendmail');
    Route::get('/contact/recover/{id}', [App\Http\Controllers\ContactController::class, 'recover'])->name('contact.recover');

    
    Route::get('/user/trash', [App\Http\Controllers\Admin\UserController::class, 'trash'])->name('user.trash');
    Route::get('/user/delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'delete'])->name('user.delete');
    Route::get('/user/recover/{id}', [App\Http\Controllers\Admin\UserController::class, 'recover'])->name('user.recover');
    Route::resource('/user', 'App\Http\Controllers\Admin\UserController');

    Route::prefix('/tutor')->name('tutor.')->group(function(){
        Route::resource('/category', 'App\Http\Controllers\Admin\TutorCategoryController');
        Route::resource('/course', 'App\Http\Controllers\Admin\TutorCourseController');
        Route::resource('/student', 'App\Http\Controllers\Admin\TutorStudentController');
        Route::resource('/announcement', 'App\Http\Controllers\Admin\TutorAnnouncementController');
        Route::get('/course/{course}/delete}', [App\Http\Controllers\Admin\TutorCourseController::class, 'destroy'])->name('course.delete');
        Route::get('/course/{course}/topic/{topic}', [App\Http\Controllers\Admin\TutorLessonController::class, 'create'])->name('lesson.create');
        Route::get('/lesson/{lesson}', [App\Http\Controllers\Admin\TutorLessonController::class, 'edit'])->name('lesson.edit');
        Route::post('/lesson/{lesson}', [App\Http\Controllers\Admin\TutorLessonController::class, 'update'])->name('lesson.update');
        Route::get('/lesson/{lesson}/delete', [App\Http\Controllers\Admin\TutorLessonController::class, 'delete'])->name('lesson.delete');
        Route::post('/course/{course}/topic/{topic}', [App\Http\Controllers\Admin\TutorLessonController::class, 'store'])->name('lesson.store');
        Route::get('/course/{course}/topic/{topic}/zoom', [App\Http\Controllers\Admin\TutorZoomController::class, 'create'])->name('zoom.create');
        Route::get('/zoom', [App\Http\Controllers\Admin\TutorZoomController::class, 'index'])->name('zoom.index');
        Route::get('/zoom/{lesson}', [App\Http\Controllers\Admin\TutorZoomController::class, 'edit'])->name('zoom.edit');
        Route::post('/zoom/{lesson}', [App\Http\Controllers\Admin\TutorZoomController::class, 'update'])->name('zoom.update');
        Route::get('/zoom/{lesson}/delete', [App\Http\Controllers\Admin\TutorZoomController::class, 'delete'])->name('zoom.delete');
        Route::post('/course/{course}/topic/{topic}/zoom', [App\Http\Controllers\Admin\TutorZoomController::class, 'store'])->name('zoom.store');
    });

    Route::prefix('/option')->name('option.')->group(function(){
        Route::get('/index', [App\Http\Controllers\Admin\OptionController::class, 'index'])->name('index');
        Route::post('/update', [App\Http\Controllers\Admin\OptionController::class, 'update'])->name('update');

        Route::get('/contact', [App\Http\Controllers\Admin\OptionController::class, 'contact'])->name('contact');
        Route::post('/contactUpdate', [App\Http\Controllers\Admin\OptionController::class, 'contactUpdate'])->name('contactUpdate');

        Route::get('/social', [App\Http\Controllers\Admin\OptionController::class, 'social'])->name('social');
        Route::post('/socialUpdate', [App\Http\Controllers\Admin\OptionController::class, 'socialUpdate'])->name('socialUpdate');

        Route::get('/menu/position', [App\Http\Controllers\Admin\MenuController::class, 'position'])->name('menu.position');
        Route::get('/menu/delete/{menu}', [App\Http\Controllers\Admin\MenuController::class, 'delete'])->name('menu.delete');
        Route::post('/menu/menu-name', [App\Http\Controllers\Admin\MenuController::class, 'menuName'])->name('menu.menuName');
        Route::resource('/menu', 'App\Http\Controllers\Admin\MenuController');

        Route::get('/widget', [App\Http\Controllers\Admin\OptionController::class, 'widget'])->name('widget');
        Route::post('/widgetUpdate', [App\Http\Controllers\Admin\OptionController::class, 'widgetUpdate'])->name('widgetUpdate');

        Route::resource('/redirect', 'App\Http\Controllers\Admin\RedirectController');
        Route::resource('/link', 'App\Http\Controllers\Admin\LinkController');
    });

   
      Route::resource('/activitie', 'App\Http\Controllers\Admin\ActivitieController');
      Route::get('/activitie/delete/{id}',[App\Http\Controllers\Admin\ActivitieController::class, 'delete'])->name('activitie.delete');
      Route::get('/activitie/trash',[App\Http\Controllers\Admin\ActivitieController::class,'trashed'])->name('activitie.trashed');
      Route::get('/activitie/recover/{id}',[App\Http\Controllers\Admin\ActivitieController::class,'recover'])->name('activitie.recover');
      Route::get('/switch/activity',[App\Http\Controllers\Admin\ActivitieController::class,'switchdata'])->name('activitie.statusdata');



 Route::resource('/mainmenu', 'App\Http\Controllers\Admin\MainMenuController');
 Route::get('/mainmenu/delete/{id}',[App\Http\Controllers\Admin\MainMenuController::class,'delete'])->name('mainmenu.delete');
 Route::get('/mainmenu/trash',[App\Http\Controllers\Admin\MainMenuController::class,'show'])->name('mainmenu.trashed');
 Route::get('/editmainmenu',[App\Http\Controllers\Admin\MainMenuController::class,'editmainmenu'])->name('mainmenu.editmain');

 Route::get('/status',[App\Http\Controllers\Admin\MainMenuController::class,'switch'])->name('mainmenu.statusupdate');
 Route::get('/mainmenu/recover/{id}',[App\Http\Controllers\Admin\MainMenuController::class,'recover'])->name('mainmenu.recover');
 Route::post('/mainmenu/updateorder',[App\Http\Controllers\Admin\MainMenuController::class,'updateorder'])->name('mainmenu.updateorder');

 Route::resource('/submenu', 'App\Http\Controllers\Admin\SubmenuController');
 Route::get('/submenu/delete/{id}',[App\Http\Controllers\Admin\SubmenuController::class,'delete'])->name('submenu.delete');
 Route::get('/submenu/trash',[App\Http\Controllers\Admin\SubmenuController::class,'show'])->name('submenu.trashed');
 Route::get('/editsubmenu',[App\Http\Controllers\Admin\SubmenuController::class,'editsubmenu'])->name('submenu.editmain');
 Route::get('/status/switch',[App\Http\Controllers\Admin\SubmenuController::class,'switch'])->name('submenu.switch');
 Route::get('/submenu/recover/{id}',[App\Http\Controllers\Admin\SubmenuController::class,'recover'])->name('submenu.recover');
 Route::post('/submenu/updateorder',[App\Http\Controllers\Admin\SubmenuController::class,'updateorder'])->name('submenu.updateorder');
 Route::resource('testimonial', TestimonialController::class);
 Route::get('/testimonial/delete/{id}',[TestimonialController::class,'show'])->name('testimonial.delete');
 Route::get('/trash/testimonial',[TestimonialController::class,'trashed'])->name('testimonial.trash');
 Route::get('/testimonial/recover/{id}',[TestimonialController::class,'recover'])->name('testimonial.recover');
 Route::get('/changestatus',[TestimonialController::class,'switch'])->name('testimonial.dataswitch');


});

Route::get('/{url}/{url2?}/{url3?}/', [App\Http\Controllers\RouteController::class, 'route'])->middleware('slashes')->middleware('redirect')->name('route');


