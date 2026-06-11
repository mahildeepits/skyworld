<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\Member\PinController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Member\AuthController;
use App\Http\Controllers\Admin\CoursesController;
use App\Http\Controllers\Admin\PancardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RewardsController;
use App\Http\Controllers\Member\PayoutController;
use App\Http\Controllers\Admin\AutopoolController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Member\AccountController;
use App\Http\Controllers\Admin\MemberViewController;
use App\Http\Controllers\Frontend\WebsiteController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\SaleEntryController;
use App\Http\Controllers\Admin\JoiningKitsController;
use App\Http\Controllers\Member\BinaryTreeController;
use App\Http\Controllers\Admin\WelcomeLetterController;
use App\Http\Controllers\Admin\WebsiteSettingsController;
use App\Http\Controllers\Member\StudentRegistrationController;
use App\Http\Controllers\Admin\PinController as AdminPinController;
use App\Http\Controllers\Admin\PayoutController as PayoutControllerAlias;
use App\Http\Controllers\Admin\Auth\AuthController as AuthControllerAlias;
use App\Http\Controllers\Member\RewardsController as RewardsControllerAlias;
use App\Http\Controllers\Admin\DashboardController as DashboardControllerAlias;
use App\Http\Controllers\Admin\SaleEntryController as AdminSaleEntryController;
use App\Http\Controllers\Admin\UserPayoutController;
use App\Http\Controllers\Member\WalletTransactionController;
use App\Http\Controllers\Admin\AgentCategoriesController;
use App\Http\Controllers\Admin\TasksController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\Member\FeaturesController;
use App\Http\Controllers\Admin\MemberFeaturesController;
use App\Http\Controllers\DailyTradeController;
use App\Http\Controllers\Admin\RegistrationRequestController;

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

Route::get('test/trade', function () {
    $amount = 100;
    for ($i = 1; $i <= 35; $i++) {
        $roi = round($amount * 0.02, 2);
        $amount = round($amount + $roi, 2);
    }
    dd($amount);
});
Route::get('generate-payout', [AuthController::class, 'generatePayoutIds']);
Route::get('fix/userenc', function () {
    $enc_password = encrypt('123456');
    $hash_password = \Hash::make('123456');
    $users = User::all();
    $errors = [];
    foreach ($users as $user) {
        try {
            $user->update(['password' => $hash_password, 'enc_password' => $enc_password]);
        } catch (\Throwable $th) {
            $errors[$user->id] = $th->getMessage();
        }
    }
    dd($errors);
});

//Admin Route
Route::get('/', function () {
    return view('frontendsite');
});
Route::group(['prefix' => 'admin'], function () {
    Route::get('login', [AuthControllerAlias::class, 'loginForm'])->name('admin.login');
    Route::post('login', [AuthControllerAlias::class, 'login'])->name('admin.login');
    Route::group(['middleware' => 'admin_auth'], function () {
        Route::get('/', [DashboardControllerAlias::class, 'index'])->name('admin.dashboard');
        Route::get('/withdrawals/requests', [DashboardControllerAlias::class, 'withdrawalRequests'])->name('admin.withdrawal.requests');
        Route::get('/company/revenue/report', [DashboardControllerAlias::class, 'companyRevenueReport'])->name('admin.company.revenue.report');
        Route::get('/setpaid/{id}/user', [DashboardControllerAlias::class, 'setPaidForm'])->name('admin.setpaid.form');
        Route::post('/setpaid/{id}/user', [DashboardControllerAlias::class, 'setPaidFunction'])->name('admin.setpaid.function');

        Route::get('joining/kits', [JoiningKitsController::class, 'joiningKits'])->name('joining.kits');
        Route::post('joining/kits', [JoiningKitsController::class, 'saveJoiningKits'])->name('joining.kits');
        Route::get('joining/kit/delete/{id}', [JoiningKitsController::class, 'deleteJoiningKit'])->name('delete.joining-kit');
        Route::get('pin/history', [JoiningKitsController::class, 'pinHistory'])->name('admin.pin.history');

        //Generate Pin
        Route::get('joining/pins', [JoiningKitsController::class, 'joiningPins'])->name('admin.joining.pins');
        Route::post('joining/pins/generate', [JoiningKitsController::class, 'generatePins'])->name('generate.pins');
        Route::match(['get', 'post'], 'pins/status', [JoiningKitsController::class, 'pinStatus'])->name('joining.pin.status');

        //Payout Report
        Route::get('payout/report', [PayoutControllerAlias::class, 'index'])->name('admin.payout.report');
        Route::post('generate/payout', ['as' => 'admin.generate.payout', 'uses' => 'PayoutController@generatePayout']);

        Route::match(['get', 'post'], 'payout/details', [PayoutControllerAlias::class, 'payoutDetails'])->name('admin.payout.details');
        Route::get('payout/details/set/paid/{id}', [PayoutControllerAlias::class, 'setPayoutAsPaid'])->name('admin.payout.setpaid');
        Route::get('payout/details/set/unpaid/{id}', [PayoutControllerAlias::class, 'setPayoutAsUnPaid'])->name('admin.payout.setunpaid');
        //Catagory
        Route::get('category', [CategoryController::class, 'index'])->name('admin.category');
        Route::post('category', [CategoryController::class, 'store'])->name('admin.store.category');
        Route::get('category/{id}', [CategoryController::class, 'destroy'])->name('admin.delete.category');

        //Product
        Route::get('product', [ProductController::class, 'index'])->name('admin.product');
        Route::post('product', [ProductController::class, 'store'])->name('admin.store.product');
        Route::get('product-list', [ProductController::class, 'list'])->name('admin.list.product');
        Route::get('subcategory/{id}', [ProductController::class, 'get_subcategory'])->name('admin.get.subcategory');
        Route::get('product/delete/{id}', [ProductController::class, 'destroy'])->name('admin.product.delete');
        Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::post('productupdate/update', [ProductController::class, 'update'])->name('admin.product.update');
        Route::get('product_image/delete/{id}', [ProductController::class, 'destroyimage'])->name('admin.delete_product_image');
        Route::get('deletedproduct', [ProductController::class, 'Deleted_product_list'])->name('admin.deleted.product');

        //Orders
        Route::get('order', [OrderController::class, 'index'])->name('admin.orders');

        Route::match(['get', 'post'], 'pancard/report', [PancardController::class, 'index'])->name('pancard.report');
        Route::match(['get', 'post'], 'transfer/pins', [AdminPinController::class, 'transferPin'])->name('admin.transfer.pins');

        Route::match(['get', 'post'], 'pending/pins', [AdminPinController::class, 'pendingPins'])->name('admin.pending.pins');

        Route::match(['get', 'post'], 'user/profiles', [UsersController::class, 'editUser'])->name('admin.edit.user');
        Route::get('user/franchise/{user_id}/{type}', [UsersController::class, 'makeFranchiseUser'])->name('admin.franchise.user');

        Route::post('profile/update', [UsersController::class, 'profileUpdate'])->name('admin.profile.update');
        Route::match(['get', 'post'], 'welcome/letters', [WelcomeLetterController::class, 'index'])->name('admin.welcome.letter');

        //Member View
        Route::get('member/view/all', [MemberViewController::class, 'index'])->name('admin.member-view-all');

        //Users
        Route::get('users/{q?}', [UsersController::class, 'index'])->name('admin.users');

        Route::get('kyc-details', [UsersController::class, 'editKyc'])->name('admin.edit.kyc');
        Route::post('kyc-details/update', [UsersController::class, 'updateKycDetails'])->name('admin.update.kyc');
        Route::get('kyc-details/approve/{id}', [UsersController::class, 'approveKyc'])->name('admin.approve.kyc');
        Route::get('kyc-details/reject/{id}', [UsersController::class, 'rejectKyc'])->name('admin.reject.kyc');

        Route::match(['get', 'post'], 'settings', [UsersController::class, 'adminCharges'])->name('admin.charges');

        Route::get('logout', [AuthControllerAlias::class, 'logout'])->name('admin.logout');

        Route::match(['get', 'post'], 'website/settings', [SettingsController::class, 'index'])->name('website.settings');

        Route::match(['get', 'post'], 'change/password', [AuthControllerAlias::class, 'changePassword'])->name('change.password');

        Route::get('user/paid/{user_id}', [UsersController::class, 'setUserToPaid'])->name('set-user-to-paid');

        Route::get('delete/{some_code}', [SettingsController::class, 'deleteRecords'])->name('delete-records');

        /**categories controller */
        // Route::get('category/list',[CategoryController::class,'listCategory'])->name('category_admin');

        Route::get('rewards', [RewardsController::class, 'index'])->name('rewards.index');
        Route::post('rewards/save', [RewardsController::class, 'save'])->name('rewards.save');
        Route::get('rewards/delete/{id}', [RewardsController::class, 'delete'])->name('rewards.delete');

        Route::match(['get', 'post'], 'sale-entries', [AdminSaleEntryController::class, 'index'])->name('admin.sale.entries');
        Route::resource('roles', RolesController::class);

        Route::get('courses', [CoursesController::class, 'index'])->name('admin.courses');
        Route::post('courses/save', [CoursesController::class, 'save'])->name('admin.courses.save');
        Route::delete('courses/{id}', [CoursesController::class, 'delete'])->name('admin.courses.delete');
        Route::get('roles/{id}/delete', [RolesController::class, 'deleteRole'])->name('roles.delete');
        // Autopool
        Route::resource('auto-pool', AutopoolController::class);
        // userPayouts
        Route::get('all/payouts', [UserPayoutController::class, 'allPayouts'])->name('admin.all.payouts');
        Route::get('pay/{id}/view', [UserPayoutController::class, 'payPayoutsView'])->name('admin.pay.view');
        Route::post('pay/{id}/payouts', [UserPayoutController::class, 'payPayouts'])->name('admin.pay.payouts');
        Route::get('reject/{id}/payouts', [UserPayoutController::class, 'rejectPayouts'])->name('admin.reject.payouts');
        // agent categroy
        Route::resource('agentcategories', AgentCategoriesController::class);
        
        // Registration Requests
        Route::get('registration/requests', [RegistrationRequestController::class, 'index'])->name('admin.registration.requests');
        Route::get('registration/requests/{id}/approve', [RegistrationRequestController::class, 'approve'])->name('admin.registration.requests.approve');
        Route::get('registration/requests/{id}/reject', [RegistrationRequestController::class, 'reject'])->name('admin.registration.requests.reject');
        // Tasks Management
        Route::resource('tasks', TasksController::class);
        Route::get('/settle/bonuses', function () {
            Artisan::call('settle:bonuses');
            return redirect()->back()->with('success', 'Success|Bonuses Settled Successfully');
        })->name('settle.bonuses');
        // features controll
        Route::get('/contacts/list', [MemberFeaturesController::class, 'contactsIndex'])->name('contacts.list');
        Route::get('/news/events', [MemberFeaturesController::class, 'newAndEventsIndex'])->name('news.events');
        Route::get('/news/events/create', [MemberFeaturesController::class, 'newAndEventsCreate'])->name('news.events.create');
        Route::post('/news/events/store', [MemberFeaturesController::class, 'newAndEventsStore'])->name('news.events.store');
        Route::get('/announcements', [MemberFeaturesController::class, 'announcements'])->name('announcements.index');
        Route::match(['get', 'post'], '/announcements/create', [MemberFeaturesController::class, 'announcementCreate'])->name('announcements.create');
        Route::get('/announcements/delete/{id}', [MemberFeaturesController::class, 'announcementDelete'])->name('announcements.delete');
    });
    Route::get('memberids', [UsersController::class, 'searchMembers'])->name('search-members');
});

Route::group(['prefix' => 'member'], function () {

    Route::middleware('guest:member')->group(function () {
        Route::get('login', [AuthController::class, 'loginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::match(['get', 'post'], 'forget/password', [AuthController::class, 'forgetPassword'])->name('forget.password');
    });

    Route::get('register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('register/send-otp', [AuthController::class, 'sendRegistrationOtp'])->name('register.send_otp');
    Route::post('register/verify-otp', [AuthController::class, 'verifyRegistrationOtp'])->name('register.verify_otp');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('register/details', [AuthController::class, 'registerDetails'])->name('register.details');

    Route::group(['middleware' => 'member_auth'], function () {
        //Dashboard
        Route::get('check/deposits', function () {
            Artisan::call('track:deposits');
            return redirect()->route('member.dashboard')->with('success', 'Success|Deposits Checked Successfully');
        })->name('track.deposit.cron');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('member.dashboard');
        Route::post('announcement/dismiss', [DashboardController::class, 'dismissAnnouncement'])->name('announcement.dismiss');
        Route::get('levels', [DashboardController::class, 'myLevels'])->name('member.levels');
        Route::get('explore/{id}/category', [DashboardController::class, 'exploreCategory'])->name('explore.category');
        Route::get('upgrade/{id}/package', [DashboardController::class, 'upgradePackage'])->name('upgrade.package');
        Route::get('attend/{id}/task', [DashboardController::class, 'attendTask'])->name('attend.task');
        Route::post('complete/task', [DashboardController::class, 'completeTask'])->name('complete.task');
        Route::get('my-receipt', [RecordController::class, 'myReceipt'])->name('receipt');
        Route::get('my-invoice', [RecordController::class, 'myInvoice'])->name('invoice');
        Route::get('my-card', [RecordController::class, 'idCard'])->name('id-card');
        Route::match(['get', 'post'], 'lock/stake', [DashboardController::class, 'lockWalletAmount'])->name('member.locking');
        Route::post('/unlock/{id}/stake', [DashboardController::class, 'unlockStake'])->name('unlock.stake');

        //Account Management
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::get('profile/edit', [AccountController::class, 'editProfile'])->name('account.profile-edit');
        Route::post('profile/save', [AccountController::class, 'saveProfile'])->name('account.save-profile');

        Route::post('update/profile/image', [AccountController::class, 'updateProfileImage'])->name('member.profile.image.update');

        Route::get('kyc', [AccountController::class, 'kycDetails'])->name('account.kyc-details');
        Route::post('kyc/update', [AccountController::class, 'updateKycDocuments'])->name('update.kyc-documents');
        Route::get('bank-details', [AccountController::class, 'editBankDetails'])->name('edit-bank-details');
        Route::post('bank-details/save', [AccountController::class, 'saveBankDetails'])->name('account.save-bank-details');
        Route::get('change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
        Route::post('password/update', [AccountController::class, 'updatePassword'])->name('account.update-password');

        Route::get('directs', [BinaryTreeController::class, 'myDirects'])->name('my-directs');
        Route::get('downline', [BinaryTreeController::class, 'downline'])->name('my-downline');

        //Pin Management
        Route::get('joining-pins', [PinController::class, 'joiningPins'])->name('joining-pins');
        Route::get('pins/transfer', [PinController::class, 'transferPins'])->name('transfer-pins');
        Route::post('pins/transfer', [PinController::class, 'transferPinsNow'])->name('transfer-pins-now');
        Route::get('pins/history', [PinController::class, 'pinsHistory'])->name('member.pins.history');
        Route::get('self/upgrade', [PinController::class, 'selfUpgradeView'])->name('member.self-upgrade.view');
        Route::post('self/upgrade', [PinController::class, 'selfUpgrade'])->name('member.self-upgrade');

        Route::get('tree/{number}', [BinaryTreeController::class, 'index'])->name('member.tree');
        //AjaxRoute
        Route::get('binary/tree/{number}', [BinaryTreeController::class, 'tree'])->name('member.tree.view');

        Route::get('payout-details', [PayoutController::class, 'index'])->name('member.payout.details')->middleware('topup_validate');

        Route::match(['get', 'post'], 'sale-entries', [SaleEntryController::class, 'index'])->name('member.sale.entries');

        Route::get('purchase', [SaleEntryController::class, 'myPurchases'])->name('member.my-purchases');

        //Rewards
        Route::get('my-rewards', [RewardsControllerAlias::class, 'index'])->name('member.rewards');

        Route::get('top-up', [DashboardController::class, 'topupPage'])->name('topup');
        Route::match(['get', 'post'], 'user/topup', [DashboardController::class, 'topupNow'])->name('member.topup.now');

        Route::match(['get', 'post'], 'joining/packages', [PinController::class, 'joiningPackages'])->name('member.joining-packages');
        Route::get('role/users', [BinaryTreeController::class, 'usersByRole'])->name('role.users');
        Route::get('add/member', [BinaryTreeController::class, 'addMember'])->name('add.member');
        Route::post('store/member', [BinaryTreeController::class, 'storeMember'])->name('store.member');

        Route::match(['get', 'post'], 'replace/tree', [BinaryTreeController::class, 'replaceTree'])->name('member.tree.replace');
        // userPayouts
        Route::get('user/payouts', [UserPayoutController::class, 'index'])->name('member.user.payouts');
        Route::get('request/{id}/payouts', [UserPayoutController::class, 'payoutRequest'])->name('member.request.payout');

        Route::get('wallet', [WalletTransactionController::class, 'walletIndex'])->name('member.wallet');
        // User Transaction
        Route::get('transfer/to/wallet', [WalletTransactionController::class, 'transferToWallet'])->name('income.transfer.to.wallet');
        Route::post('wallet/transaction', [WalletTransactionController::class, 'walletTransaction'])->name('member.wallet.transaction');
        // User Transaction
        Route::match(['get', 'post'], 'wallet/withdrawl', [TransactionsController::class, 'withdrawlCreate'])->name('wallet.withdrawl');
        Route::match(['get', 'post'], 'wallet/transfer', [WalletTransactionController::class, 'walletTransfer'])->name('wallet.transfer');
        Route::match(['get', 'post'], 'edit/wallet/address', [TransactionsController::class, 'editWalletAddress'])->name('edit.wallet.address');
        Route::match(['get', 'post'], 'wallet/deposit', [TransactionsController::class, 'walletDeposit'])->name('wallet.deposit')->middleware('wallet_connected');
        Route::match(['get', 'post'], 'wallet/pin', [TransactionsController::class, 'setTransactionPin'])->name('wallet.pin');

        // feature
        Route::get('contact/us', [FeaturesController::class, 'contactPage'])->name('contact.us');
        Route::post('contact/submit', [FeaturesController::class, 'contactSubmit'])->name('contact.submit');
        Route::get('newsevents/list', [FeaturesController::class, 'newsEventsPage'])->name('newsevents.list');
        Route::get('announcements/list', [FeaturesController::class, 'announcementsPage'])->name('announcements.list');
        Route::get('/about-us', [FeaturesController::class, 'aboutUsPage'])->name('about-us');
        Route::post('weekly/checkin', [FeaturesController::class, 'weeklyCheckin'])->name('weekly.checkin');
        Route::get('/redeem/{id}/reward', [FeaturesController::class, 'redeemRewards'])->name('redeem.rewards');

        // Daily Trade
        Route::get('daily-trade', [DailyTradeController::class, 'index'])->name('member.trade');
        Route::post('daily-trade/start', [DailyTradeController::class, 'store'])->name('member.trade.start');
        Route::post('daily-trade/complete', [DailyTradeController::class, 'completeTrade'])->name('member.trade.complete');
    });


    //Ajax Routes
    Route::get('sponsor/validate', [AjaxController::class, 'validateSponsor'])->name('sponsor.details');
    Route::get('pins/available', [AjaxController::class, 'availablePins'])->name('available.pins');
    Route::get('joining-pins/available', [AjaxController::class, 'joiningPins'])->name('available.joining-pins');
    Route::get('send/otp', [AjaxController::class, 'sendOtp'])->name('send.otp');
});

Route::group(['prefix' => 'student'], function () {
    Route::group(['middleware' => 'member_auth'], function () {
        // Student Routes
        Route::get('courses', [CoursesController::class, 'viewCourses'])->name('student.courses');
        Route::get('my-courses', [CoursesController::class, 'myCourses'])->name('student.my-courses');
    });

    Route::get('student/registration', [StudentRegistrationController::class, 'index'])->name('student.registration');
    Route::post('student/register', [StudentRegistrationController::class, 'register'])->name('register.student');
});


//Route::get('/',[WebsiteController::class,'index'])->name('web.home');
//Route::get('/about-us',[WebsiteController::class,'aboutus'])->name('web.aboutus');
//Route::get('/business',[WebsiteController::class,'business'])->name('web.business');
//Route::get('/products',[WebsiteController::class,'products'])->name('web.products');
//Route::get('/contact-us',[WebsiteController::class,'contactUs'])->name('web.contact');
Route::get('/clear', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return "cleared";
});
Route::get('/storage/link', function () {
    Artisan::call('storage:link');
    return "storage linked";
});

// Route::get('mysite', function () {
//     Artisan::call('view:clear');
//     return view('frontendsite');
// });

Route::get('/migrate', function() {
    Artisan::call('migrate');
    return "migrated";
});
