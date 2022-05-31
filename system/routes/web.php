<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');
Route::group(['middleware'=>'domain.setup'], function() {
    Route::group(['namespace' => 'App\Http\Controllers'], function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('contact-us', 'HomeController@contactUs')->name('contact.us');
        Route::post('contact-us', 'HomeController@contactUsPost')->name('contact.us.post');
        Route::get('price', 'HomeController@price')->name('pricing');
        Route::get('terms-and-conditions', 'HomeController@terms')->name('terms');
        Route::post('terms-and-conditions', 'HomeController@termsPost')->name('terms.post');
        Route::get('sign-up', 'HomeController@signUp')->name('sign.up');
        Route::post('sign-up', 'HomeController@signUpPost')->name('sign.up.post');
        Route::get('subscribe', 'HomeController@subscribe')->name('contact.subscribe');
        Route::get('unsubscribe', 'HomeController@unsubscribe')->name('contact.unsubscribe');
        Route::post('unsubscribe', 'HomeController@unsubscribe_update')->name('contact.unsubscribe.post');
        Route::get('unsubscribe_success', 'HomeController@unsubscribe_success')->name('contact.unsubscribe_success');
    });

    Route::group(['prefix' => 'smspanel', 'namespace' => 'App\Http\Controllers'], function () {
        Route::get('smsapi', 'ApiMessageController@sendMessage')->name('api.message.get');
        Route::post('smsapi', 'ApiMessageController@sendMessage')->name('api.message.post');
        Route::get('miscapi/{apikey}/getBalance', 'ApiMessageController@getBalance')->name('api.getBalance');
        Route::get('miscapi/{apikey}/getDLR/{SMS_SHOOT_ID}', 'ApiMessageController@getDLR')->name('api.getDLR');
        Route::get('getkey/{username}/{password}', 'ApiMessageController@getkey')->name('api.getkey');
    });

    Route::group(['prefix' => 'smspanel'], function () {
        Auth::routes(['register' => false]);
        Route::get('/', function () {
            return redirect()->route('login');
        })->name('login.redirect');


        Route::get('forget-password', 'App\Http\Controllers\Auth\ForgotPasswordController@showForgetPasswordForm')->name('forget.password.get');
        Route::post('forget-password', 'App\Http\Controllers\Auth\ForgotPasswordController@submitForgetPasswordForm')->name('forget.password.post');
        Route::get('reset-password/{token}', 'App\Http\Controllers\Auth\ForgotPasswordController@showResetPasswordForm')->name('reset.password.get');
        Route::post('reset-password', 'App\Http\Controllers\Auth\ForgotPasswordController@submitResetPasswordForm')->name('reset.password.post');


        Route::group(['middleware' => ['auth', 'role'], 'namespace' => 'App\Http\Controllers\Admin'], function () {
            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
            Route::get('/dashboard/channel-status', 'DashboardController@channel_status')->name('dashboard.channel_status');
            Route::get('/dashboard/outbox-status', 'DashboardController@outbox_status')->name('dashboard.outbox_status');

            // Menus Route
            Route::get('/menu-list', 'MenuController@index')->name('menu.list');
            Route::get('/menu-add', 'MenuController@add')->name('menu.add');
            Route::post('/menu-add', 'MenuController@store')->name('menu.add.post');
            Route::get('/menu-edit/{id}', 'MenuController@edit')->name('menu.edit');
            Route::post('/menu-edit/{id}', 'MenuController@update')->name('menu.edit.post');
            Route::get('/menu-view/{id}', 'MenuController@view')->name('menu.view');
            Route::post('/menu-view/{id}', 'MenuController@addSub')->name('menu.add.sub.post');
            Route::post('menu-change-status/{id}', 'MenuController@changeStatus')->name('menu.status');

            //Config Route
            Route::get('setting', [\App\Http\Controllers\Admin\ConfigController::class, 'setting'])->name('setting');
            Route::post('setting', [\App\Http\Controllers\Admin\ConfigController::class, 'store'])->name('setting.store');

            // Reseller Route
            Route::get('reseller-list', 'ResellerController@index')->name('reseller.list');
            Route::get('reseller-add', 'ResellerController@add')->name('reseller.add');
            Route::post('reseller-add', 'ResellerController@store')->name('reseller.add.post');
            Route::get('reseller-edit/{id}', 'ResellerController@edit')->name('reseller.edit');
            Route::post('reseller-edit/{id}', 'ResellerController@update')->name('reseller.edit.post');
            Route::post('reseller-change-status/{id}', 'ResellerController@changeStatus')->name('reseller.status');

            // User Group Route
            Route::get('user-group-list', 'UserGroupController@index')->name('usergroup.list');
            Route::get('user-group-add', 'UserGroupController@create')->name('usergroup.add');
            Route::post('user-group-add', 'UserGroupController@store')->name('usergroup.add.post');
            Route::get('user-group-edit/{id}', 'UserGroupController@edit')->name('usergroup.edit');
            Route::post('user-group-edit/{id}', 'UserGroupController@update')->name('usergroup.edit.post');

            // User Route
            Route::get('user-list', 'UserController@index')->name('user.list');
            Route::get('active-user-list', 'UserController@activeUsers')->name('user.list.active');
            Route::get('inactive-user-list', 'UserController@inactiveUsers')->name('user.list.inactive');
            Route::get('user-add', 'UserController@create')->name('user.add');
            Route::post('user-add', 'UserController@store')->name('user.add.post');
            Route::get('user-view/{id}', 'UserController@show')->name('user.view');
            Route::get('user-edit/{id}', 'UserController@edit')->name('user.edit');
            Route::post('user-edit/{id}', 'UserController@update')->name('user.edit.post');
            Route::post('user-edit-password/{id}', 'UserController@updatePassword')->name('user.password.edit.post');
            Route::post('user-change-status/{id}', 'UserController@changeStatus')->name('user.status');
            Route::post('user-change-dipping/{id}', 'UserController@changeDeeping')->name('user.dipping');

            Route::get('profile-edit/{id}', 'UserController@profileEdit')->name('profile.edit');
            Route::post('profile-edit/{id}', 'UserController@profileEditStore')->name('profile.edit.post');
            Route::post('password-change/{id}', 'UserController@passwordChange')->name('password.change.post');

            Route::get('user-wallet-list', 'UserController@userWallet')->name('user.wallet.list');

            Route::post('user-loginas', 'UserController@loginAs')->name('login.asuser');

            //Balance Route
            Route::get('reseller-transfer-list', 'BalanceController@resellerTransferList')->name('balance.transfer.list.reseller');
            Route::get('user-transfer-list', 'BalanceController@userTransferList')->name('balance.transfer.list.user');
            Route::get('user-balance-add', 'BalanceController@addUserBalance')->name('balance.add.user');
            Route::get('reseller-balance-add', 'BalanceController@addResellerBalance')->name('balance.add.reseller');
            Route::post('balance-add', 'BalanceController@store')->name('balance.add.post');
            Route::get('balance-edit/{id}', 'BalanceController@edit')->name('balance.edit');
            Route::post('balance-edit/{id}', 'BalanceController@update')->name('balance.edit.post');
            Route::post('balance-change-status-user/{id}', 'BalanceController@changeStatusUser')->name('balance.status.user');
            Route::post('balance-change-status-reserller/{id}', 'BalanceController@changeStatusReseller')->name('balance.status.reseller');
            Route::get('buy-bundle', 'BalanceController@buyBundle')->name('balance.buy.bundle');
            Route::post('buy-bundle', 'BalanceController@buyBundlePost')->name('balance.buy.bundle.post');
            Route::get('buy-bundle-list', 'BalanceController@buyBundleList')->name('balance.buy.bundle.list');

            Route::get('users-wallet-list', 'BalanceController@userWallet')->name('balance.wallet.user.list');
            Route::get('reseller-wallet-list', 'BalanceController@resellerWallet')->name('balance.wallet.reseller.list');

            // Rate Route
            Route::get('sms-rate-list', 'RateController@index')->name('rate.list');
            Route::get('email-rate-list', 'RateController@emailRate')->name('rate.list.email');
            Route::get('rate-add', 'RateController@create')->name('rate.add');
            Route::post('rate-add', 'RateController@store')->name('rate.add.post');
            Route::get('rate-edit/{id}', 'RateController@edit')->name('rate.edit');
            Route::post('rate-edit/{id}', 'RateController@update')->name('rate.edit.post');

            // Country Route
            Route::get('country-list', 'CountryController@index')->name('country.list');
            Route::get('country-add', 'CountryController@create')->name('country.add');
            Route::post('country-add', 'CountryController@store')->name('country.add.post');
            Route::get('country-edit/{id}', 'CountryController@edit')->name('country.edit');
            Route::post('country-edit/{id}', 'CountryController@update')->name('country.edit.post');

            // SMS Operator Route
            Route::get('sms-operator-list', 'SmsOperatorController@index')->name('smsconfig.operator.list');
            Route::get('sms-operator-add', 'SmsOperatorController@create')->name('smsconfig.operator.add');
            Route::post('sms-operator-add', 'SmsOperatorController@store')->name('smsconfig.operator.add.post');
            Route::get('sms-operator-edit/{id}', 'SmsOperatorController@edit')->name('smsconfig.operator.edit');
            Route::post('sms-operator-edit/{id}', 'SmsOperatorController@update')->name('smsconfig.operator.edit.post');
            Route::post('sms-operator-change-status/{id}', 'SmsOperatorController@changeStatus')->name('smsconfig.operator.status');

            // SMS Service Provider Route
            Route::get('sms-service-provider-list', 'SmsServiceProviderController@index')->name('smsconfig.serviceprovider.list');
            Route::get('sms-service-provider-view/{id}', 'SmsServiceProviderController@show')->name('smsconfig.serviceprovider.view');
            Route::get('sms-service-provider-add', 'SmsServiceProviderController@create')->name('smsconfig.serviceprovider.add');
            Route::post('sms-service-provider-add', 'SmsServiceProviderController@store')->name('smsconfig.serviceprovider.add.post');
            Route::get('sms-service-provider-edit/{id}', 'SmsServiceProviderController@edit')->name('smsconfig.serviceprovider.edit');
            Route::post('sms-service-provider-edit/{id}', 'SmsServiceProviderController@update')->name('smsconfig.serviceprovider.edit.post');
            Route::post('sms-service-provider-change-status/{id}', 'SmsServiceProviderController@changeStatus')->name('smsconfig.serviceprovider.status');

            // SMS Routing Route
            Route::get('sms-route-list', 'SmsRouteController@index')->name('smsconfig.route.list');
            Route::get('sms-route-add', 'SmsRouteController@create')->name('smsconfig.route.add');
            Route::post('sms-route-add', 'SmsRouteController@store')->name('smsconfig.route.add.post');
            Route::get('sms-route-edit/{id}', 'SmsRouteController@edit')->name('smsconfig.route.edit');
            Route::post('sms-route-edit/{id}', 'SmsRouteController@update')->name('smsconfig.route.edit.post');
            Route::post('sms-route-change-status/{id}', 'SmsRouteController@changeStatus')->name('smsconfig.route.status');

            // SMS Service Provider Route
            Route::get('email-service-provider-list', 'EmailServiceProviderController@index')->name('emailconfig.serviceprovider.list');
            Route::get('email-service-provider-view/{id}', 'EmailServiceProviderController@show')->name('emailconfig.serviceprovider.view');
            Route::get('email-service-provider-add', 'EmailServiceProviderController@create')->name('emailconfig.serviceprovider.add');
            Route::post('email-service-provider-add', 'EmailServiceProviderController@store')->name('emailconfig.serviceprovider.add.post');
            Route::get('email-service-provider-edit/{id}', 'EmailServiceProviderController@edit')->name('emailconfig.serviceprovider.edit');
            Route::post('email-service-provider-edit/{id}', 'EmailServiceProviderController@update')->name('emailconfig.serviceprovider.edit.post');
            Route::post('email-service-provider-change-status/{id}', 'EmailServiceProviderController@changeStatus')->name('emailconfig.serviceprovider.status');

            // SMS Routing Route
            Route::get('email-route-list', 'EmailRouteController@index')->name('emailconfig.route.list');
            Route::get('email-route-add', 'EmailRouteController@create')->name('emailconfig.route.add');
            Route::post('email-route-add', 'EmailRouteController@store')->name('emailconfig.route.add.post');
            Route::get('email-route-edit/{id}', 'EmailRouteController@edit')->name('emailconfig.route.edit');
            Route::post('email-route-edit/{id}', 'EmailRouteController@update')->name('emailconfig.route.edit.post');
            Route::post('email-route-change-status/{id}', 'EmailRouteController@changeStatus')->name('emailconfig.route.status');

            // PhoneBook Group Route
            Route::get('group-list', 'PhoneBookController@groups')->name('phonebook.group.list');
            Route::get('group-add', 'PhoneBookController@groupCreate')->name('phonebook.group.add');
            Route::post('group-add', 'PhoneBookController@groupStore')->name('phonebook.group.add.post');
            Route::get('group-edit/{id}', 'PhoneBookController@groupEdit')->name('phonebook.group.edit');
            Route::post('group-edit/{id}', 'PhoneBookController@groupUpdate')->name('phonebook.group.edit.post');
            Route::get('group-view/{id}', 'PhoneBookController@groupView')->name('phonebook.group.view');
            Route::post('group-change-status/{id}', 'PhoneBookController@changeGroupStatus')->name('phonebook.group.status');

            // PhoneBook Contact Route
            Route::get('contacts', 'PhoneBookController@contacts')->name('phonebook.contact.list');
//            Route::post('contacts', 'PhoneBookController@contacts')->name('phonebook.contact.list.post');
            Route::get('contact-add', 'PhoneBookController@contactAdd')->name('phonebook.contact.add');
            Route::post('contact-add', 'PhoneBookController@contactPost')->name('phonebook.contact.add.post');
            Route::post('group-view/{id}', 'PhoneBookController@contactStore')->name('phonebook.contact.add.store');
            Route::get('contact-edit/{id}', 'PhoneBookController@contactEdit')->name('phonebook.contact.edit');
            Route::post('contact-edit/{id}', 'PhoneBookController@contactUpdate')->name('phonebook.contact.edit.post');
            Route::post('contact-change-status/{id}', 'PhoneBookController@changeContactStatus')->name('phonebook.contact.status');
            Route::post('contact-delete', 'PhoneBookController@contactDelete')->name('phonebook.contact.delete');


            // Mask Route
            Route::get('senderid-list', 'SenderIdController@index')->name('config.senderid.list');
            Route::get('senderid-add', 'SenderIdController@create')->name('config.senderid.add');
            Route::post('senderid-add', 'SenderIdController@store')->name('config.senderid.add.post');
            Route::get('senderid-edit/{id}', 'SenderIdController@edit')->name('config.senderid.edit');
            Route::post('senderid-edit/{id}', 'SenderIdController@update')->name('config.senderid.edit.post');
            Route::post('senderid-change-status/{id}', 'SenderIdController@changeStatus')->name('config.senderid.status');

            // Mask Assign Route
            Route::get('senderid-assign-user', 'SenderIdController@assignUser')->name('config.assign.senderid.user');
            Route::post('senderid-assign-user', 'SenderIdController@assignUserStore')->name('config.assign.senderid.user.post');

            // Domain Route
            Route::get('domain-list', 'DomainController@index')->name('config.domain.list');
            Route::get('domain-add', 'DomainController@create')->name('config.domain.add');
            Route::post('domain-add', 'DomainController@store')->name('config.domain.add.post');
            Route::get('domain-edit/{id}', 'DomainController@edit')->name('config.domain.edit');
            Route::post('domain-edit/{id}', 'DomainController@update')->name('config.domain.edit.post');
            Route::post('domain-change-status/{id}', 'DomainController@changeStatus')->name('config.domain.status');

            // Domain Assign Route
            Route::get('domain-assign-user', 'DomainController@assignUser')->name('config.assign.domain.user');
            Route::post('domain-assign-user', 'DomainController@assignUserStore')->name('config.assign.domain.user.post');

            // Template Route
            Route::get('template-list', 'TemplateController@index')->name('template.list');
            Route::get('template-add', 'TemplateController@create')->name('template.add');
            Route::post('template-add', 'TemplateController@store')->name('template.add.post');
            Route::get('template-edit/{id}', 'TemplateController@edit')->name('template.edit');
            Route::post('template-edit/{id}', 'TemplateController@update')->name('template.edit.post');
            Route::post('template-change-status/{id}', 'TemplateController@changeStatus')->name('template.status');

            // Message Route
            Route::get('message-list', 'MessageController@index')->name('message.list');
            Route::get('message-send', 'MessageController@sendNonMaskingSms')->name('message.add');
            Route::get('message-send-masking', 'MessageController@sendMaskingSms')->name('message.add.masking');
            Route::post('message-send', 'MessageController@store')->name('message.add.post');
            Route::get('message-send-dynamic', 'MessageController@sendDynamicSms')->name('message.add.dynamic');
            Route::post('message-send-dynamic', 'MessageController@storeDynamic')->name('message.add.dynamic.post');

            // Email Route
            Route::get('email-list', 'EmailController@index')->name('email.list');
            Route::get('email-send', 'EmailController@create')->name('email.add');
            Route::post('email-send', 'EmailController@store')->name('email.add.post');
            Route::get('email-edit/{id}', 'EmailController@edit')->name('email.edit');
            Route::post('email-edit/{id}', 'EmailController@update')->name('email.edit.post');
            Route::post('email-delete/{id}', 'EmailController@delete')->name('email.delete.post');

            // Campaign Route
            Route::post('campaign-store', 'MessageController@campaignStore')->name('campaign.store');
            Route::post('ckeditor', 'MessageController@uploadImage')->name('upload.image');

            //Create API
            Route::get('create-api', 'ConfigController@createApi')->name('config.create.api');
            Route::post('create-api', 'ConfigController@storeApi')->name('config.create.api.post');

            // Report Route
            Route::get('sms-report-list', 'ReportController@index')->name('report.list');
            Route::get('sms-report-failed-list', 'ReportController@reportFailed')->name('report.failed.list');
            Route::get('sms-report-count', 'ReportController@reportCount')->name('report.sms.count');
            Route::get('email-report-list', 'ReportController@emailLogs')->name('report.email.list');

            // Invoce Route
            Route::get('invoice-list', 'InvoiceController@index')->name('invoice.list');
            Route::get('invoice-create', 'InvoiceController@create')->name('invoice.create');
            Route::post('invoice-create', 'InvoiceController@create');
            Route::post('invoice-create-store', 'InvoiceController@store')->name('invoice.create.post');
            Route::get('invoice-view/{id}', 'InvoiceController@view')->name('invoice.view');

            //Ajax Route
            Route::group(['middleware' => ['only.ajax']], function () {
                Route::get('get-channel-data', 'AjaxController@channelData')->name('get.smsconfig.serviceprovider.data');
                Route::get('get-user-data-by-mask', 'AjaxController@userDataByMask')->name('get.mask.user.data');
                Route::get('get-user-data-by-domain', 'AjaxController@userDataByDomain')->name('get.domain.user.data');
                Route::get('get-users', 'AjaxController@selectUsers')->name('select.user');
                Route::get('get-invoice-users', 'AjaxController@selectInvoiceUsers')->name('select.invoice.user');
                Route::get('get-mask', 'AjaxController@selectMasks')->name('select.mask');
                Route::get('get-domain', 'AjaxController@selectDomains')->name('select.domain');
                Route::get('get-user-mask-domain', 'AjaxController@getUserMaskDomain')->name('get.user.mask.domain');
                Route::get('get-resellers', 'AjaxController@selectResellers')->name('select.reseller');
                Route::get('get-district', 'AjaxController@selectDistrict')->name('get.district');
                Route::get('get-upazilla', 'AjaxController@selectUpazilla')->name('get.upazilla');
                Route::get('get-remaining-balance', 'AjaxController@getRemainingBalance')->name('get.remaining.balance');
                Route::get('get-route-name', 'AjaxController@getRouteName')->name('get.route.name');
                Route::get('get-from-email', 'AjaxController@getFromEmail')->name('get.from.email');
                Route::get('get-contact-group', 'AjaxController@getContactGroup')->name('get.contact.group');
            });
        });
    });
});
