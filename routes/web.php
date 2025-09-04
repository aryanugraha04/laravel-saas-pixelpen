<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isUser;
use App\Http\Controllers\Backend\Admin\AdminController;
use App\Http\Controllers\Backend\Admin\PlanController;
use App\Http\Controllers\Backend\Admin\TemplateController;
use App\Http\Controllers\Backend\Admin\DocumentController;

use App\Http\Controllers\Backend\Client\UserController;
use App\Http\Controllers\Backend\Client\UserTemplateController;
use App\Http\Controllers\Backend\Client\CheckoutController;

use App\Http\Controllers\Frontend\HomeController;

Route::get('/', function () {
    return view('home.index');
})->name('home.index');


/// User Routes
Route::prefix('user')->middleware(['auth', isUser::class])-> group(function() {

Route::get('/dashboard', function () {
    return view('client.index');
})->name('dashboard');

Route::get('/logout', [UserController::class, 'UserLogout'])->name('user.logout');
Route::get('/profile', [UserController::class, 'UserProfile'])->name('user.profile');
Route::post('/profile/store', [UserController::class, 'UserProfileStore'])->name('user.profile.store');
Route::get('/change/password', [UserController::class, 'UserChangePassword'])->name('user.change.password');
Route::post('/password/update', [UserController::class, 'UserPasswordUpdate'])->name('user.password.update');

Route::controller(UserTemplateController::class)->group(function(){
    Route::get('/template', 'UserTemplate')->name('user.template');
    Route::get('/details/template/{id}', 'UserDetailsTemplate')->name('user.details.template');
    Route::post('/content/generate/{id}', 'UserContentGenerate')->name('user.content.generate');

    Route::get('/document', 'UserDocument')->name('user.document');
    Route::get('/edit/document/{id}', 'EditUserDocument')->name('edit.user.document');
    Route::post('/update/document/{id}', 'UserUpdateDocument')->name('user.update.document');
    Route::get('/delete/document/{id}', 'UserDeleteDocument')->name('user.delete.document');
});

Route::controller(CheckoutController::class)->group(function(){
    Route::get('/checkout', 'UserCheckout')->name('user.checkout');
    Route::post('/process/checkout', 'UserProcessCheckout')->name('user.process.checkout');
    Route::get('/payment/success', 'PaymentSuccess')->name('payment.success');
    Route::get('/invoice/generate/{id}', 'InvoiceGenerate')->name('invoice.generate');
});

});
/// End User Routes

/// Admin Routes
Route::prefix('admin')->middleware(['auth', isAdmin::class])-> group(function() {

Route::get('/dashboard', function () {
    return view('admin.index');
})->name('admin.dashboard');

Route::get('/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
Route::get('/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
Route::post('/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
Route::get('/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
Route::post('/password/update', [AdminController::class, 'AdminPasswordUpdate'])->name('admin.password.update');

Route::controller(PlanController::class)->group(function(){
    Route::get('/all/plans', 'AllPlans')->name('all.plans');
    Route::get('/add/plans', 'AddPlans')->name('add.plans');
    Route::post('/store/plans', 'StorePlans')->name('store.plans');
    Route::get('/edit/plans/{id}', 'EditPlans')->name('edit.plans');
    Route::post('/update/plans', 'UpdatePlans')->name('update.plans');
    Route::get('/delete/plans/{id}', 'DeletePlans')->name('delete.plans');
});

Route::controller(TemplateController::class)->group(function(){
    Route::get('/template', 'AdminTemplate')->name('admin.template');
    Route::get('/add/template', 'AddTemplate')->name('add.template');
    Route::post('/store/template', 'StoreTemplate')->name('store.template');
    Route::get('/edit/template/{id}', 'EditTemplate')->name('edit.template');
    Route::post('/update/template/{id}', 'UpdateTemplate')->name('update.template');
    Route::get('/details/template/{id}', 'DetailsTemplate')->name('details.template');
    Route::post('/content/generate/{id}', 'AdminContentGenerate')->name('content.generate');
});

Route::controller(DocumentController::class)->group(function(){
    Route::get('/document', 'AdminDocument')->name('admin.document');
    Route::get('/edit/document/{id}', 'EditAdminDocument')->name('edit.admin.document');
    Route::post('/update/document/{id}', 'AdminUpdateDocument')->name('admin.update.document');
    Route::get('/delete/document/{id}', 'AdminDeleteDocument')->name('admin.delete.document');
});

Route::controller(AdminController::class)->group(function(){
    Route::get('/all/orders', 'AllOrders')->name('all.orders');
    Route::get('/update/order/status/{id}', 'UpdateOrderStatus')->name('update.order.status');

});

Route::controller(HomeController::class)->group(function(){
    Route::get('/home/slider', 'HomeSlider')->name('home.slider');
    Route::post('/update/slider', 'UpdateSlider')->name('update.slider');
    Route::get('/all/heading', 'AllHeading')->name('all.heading');
    Route::get('/add/heading', 'AddHeading')->name('add.heading');
    Route::post('/store/heading', 'StoreHeading')->name('store.heading');
    Route::get('/edit/heading/{id}', 'EditHeading')->name('edit.heading');
    Route::post('/update/heading', 'UpdateHeading')->name('update.heading');
    Route::get('/delete/heading/{id}', 'DeleteHeading')->name('delete.heading');
    Route::get('/contact/message', 'ContactMessage')->name('contact.message');
    Route::get('/delete/contact/message/{id}', 'DeleteContactMessage')->name('delete.contact.message');
});

});
/// End Admin Routes

////////// HOME FRONTEND ////////////
Route::controller(HomeController::class)->group(function(){
    Route::get('/usecase', 'UseCase')->name('usecase');
    Route::get('/features', 'Features')->name('features');
    Route::get('/pricing', 'Pricing')->name('pricing');
    Route::get('/contact', 'Contact')->name('contact');
    Route::post('/store/contact', 'StoreContact')->name('store.contact');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
