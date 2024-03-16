<?php

use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoarderController;
use App\Http\Controllers\LeaseAgreementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UnitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard')
    ->breadcrumbs(fn (Trail $trail) =>
        $trail->push('Dashboard', route('dashboard'))
    );

Route::middleware('auth')->group(function () {

    Route::get('/boarders', [BoarderController::class, 'index'])
        ->name('boarders.index')
        ->breadcrumbs(fn (Trail $trail) => 
            $trail
                ->parent('dashboard')
                ->push(__('Boarders'), route('boarders.index'))
        );

    Route::get('/boarders/create', [BoarderController::class, 'create'])
        ->name('boarders.create')
        ->breadcrumbs(fn (Trail $trail) => 
            $trail
                ->parent('dashboard')
                ->push(__('Boarders'), route('boarders.index'))
                ->push(__('New'), route('boarders.create'))
        );

    Route::get('/boarders/{boarder}/edit', [BoarderController::class, 'edit'])
        ->name('boarders.edit')
        ->breadcrumbs(fn (Trail $trail, $boarder) => 
            $trail
                ->parent('dashboard')
                ->push(__('Boarders'), route('boarders.index'))
                ->push(__('Edit'), route('boarders.edit', $boarder))
        );

    Route::get('/boarders/{boarder}', [BoarderController::class, 'show'])
        ->name('boarders.show')
        ->breadcrumbs(fn (Trail $trail, $boarder) => 
            $trail
                ->parent('dashboard')
                ->push(__('Boarders'), route('boarders.index'))
                ->push($boarder->fullName)
        );

    Route::resource('boarders', BoarderController::class)
        ->except(['index', 'create', 'edit', 'show']);

    Route::get('/units', [UnitController::class, 'index'])
        ->name('units.index')
        ->breadcrumbs(fn (Trail $trail) => 
            $trail
                ->parent('dashboard')
                ->push(__('Units'), route('units.index'))
        );

    Route::get('/units/create', [UnitController::class, 'create'])
        ->name('units.create')
        ->breadcrumbs(fn (Trail $trail) => 
            $trail
                ->parent('dashboard')
                ->push(__('Units'), route('units.index'))
                ->push(__('New'), route('units.create'))
        );

    Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])
        ->name('units.edit')
        ->breadcrumbs(fn (Trail $trail, $unit) => 
            $trail
                ->parent('dashboard')
                ->push(__('Units'), route('units.index'))
                ->push(__('Edit'), route('units.edit', $unit))
        );

    Route::resource('units', UnitController::class)
        ->except(['index', 'create', 'edit', 'show']);

    Route::post('/units/{unit}/rooms', [RoomController::class, 'store'])
        ->name('units.rooms.store');

    Route::get('/boarders/{boarder}/contracts/create', [LeaseAgreementController::class, 'create'])
        ->name('boarders.contracts.create')
        ->breadcrumbs(fn (Trail $trail, $boarder) => 
            $trail
                ->parent('dashboard')
                ->push(__('Boarders'), route('boarders.index'))
                ->push(__($boarder->fullName), route('boarders.show', $boarder))
                ->push('New Contract')
        );

    Route::get('/boarders/{boarder}/contracts/{contract}/edit', [LeaseAgreementController::class, 'edit'])
        ->name('boarders.contracts.edit')
        ->breadcrumbs(fn (Trail $trail, $boarder) => 
            $trail
                ->parent('dashboard')
                ->push(__('Boarders'), route('boarders.index'))
                ->push(__($boarder->fullName), route('boarders.show', $boarder))
                ->push('Edit Contract')
        );

    Route::resource('boarders.contracts', LeaseAgreementController::class)
        ->scoped()->except(['index', 'edit']);

    Route::patch('/boarders/{boarder}/contracts/{contract}/terminate', [LeaseAgreementController::class, 'terminate'])
        ->name('boarders.contracts.end');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__.'/auth.php';
