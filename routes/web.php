<?php

use App\Livewire\Masters\UOM\UOMList;
use Illuminate\Support\Facades\Route;
use App\Livewire\Masters\CostCenters\CostCenterList;
use App\Livewire\Masters\ServiceTypes\ServiceTypeList;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

// });

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Finance Tickets Routes
    Route::prefix('tickets/finance')->name('tickets.finance.')->group(function () {
        Route::get('/', function () {
            return view('admin.coming-soon', ['title' => 'Finance Tickets']);
        })->name('index');
        Route::get('/create', function () {
            return view('admin.coming-soon', ['title' => 'Create Finance Ticket']);
        })->name('create');
    });

    // Delivery Notes Routes
    Route::prefix('tickets/delivery')->name('tickets.delivery.')->group(function () {
        Route::get('/', function () {
            return view('admin.coming-soon', ['title' => 'Delivery Notes']);
        })->name('index');
        Route::get('/create', function () {
            return view('admin.coming-soon', ['title' => 'Create Delivery Note']);
        })->name('create');
    });

    // Fuel Sales Routes
    Route::prefix('fuel-sales')->name('fuel-sales.')->group(function () {
        Route::get('/', function () {
            return view('admin.coming-soon', ['title' => 'Fuel Sales']);
        })->name('index');
        Route::get('/create', function () {
            return view('admin.coming-soon', ['title' => 'Create Fuel Sale']);
        })->name('create');
    });

    // Masters Routes
    Route::prefix('masters')->name('masters.')->group(function () {
        // Departments
        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', function () {
                return view('admin.coming-soon', ['title' => 'Departments']);
            })->name('index');
        });

        // Users
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', function () {
                return view('admin.coming-soon', ['title' => 'Users']);
            })->name('index');
        });

        // Clients
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', function () {
                return view('admin.coming-soon', ['title' => 'Clients']);
            })->name('index');
        });

        // Cost Centers

        Route::get('/cost-center', CostCenterList::class)->name('cost-centers');
        // Route::get('/masters/cost-center', CostCenterList::class)->name('masters.cost-center');

        // Route::prefix('cost-centers')->name('cost-centers.')->group(function () {
        //     Route::get('/', function () {
        //         return view('admin.coming-soon', ['title' => 'Cost Centers']);
        //     })->name('index');
        // });

        // Service Types

        Route::get('service-type', ServiceTypeList::class)->name('service-type');

        // Route::prefix('service-types')->name('service-types.')->group(function () {
        //     Route::get('/', function () {
        //         return view('admin.coming-soon', ['title' => 'Service Types']);
        //     })->name('index');
        // });

        // UOM
        // Route::prefix('uom')->name('uom.')->group(function () {
        //     Route::get('/', function () {
        //         return view('masters.uom', ['title' => 'Units of Measurement']);
        //     })->name('index');
        // });

        Route::get('/uom', UOMList::class)->name('uom-list');
    });

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/finance', function () {
            return view('admin.coming-soon', ['title' => 'Finance Tickets Report']);
        })->name('finance');

        Route::get('/delivery', function () {
            return view('admin.coming-soon', ['title' => 'Delivery Notes Report']);
        })->name('delivery');

        Route::get('/fuel', function () {
            return view('admin.coming-soon', ['title' => 'Fuel Sales Report']);
        })->name('fuel');

        Route::get('/department', function () {
            return view('admin.coming-soon', ['title' => 'Department-wise Report']);
        })->name('department');

        Route::get('/summary', function () {
            return view('admin.coming-soon', ['title' => 'Summary Report']);
        })->name('summary');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/roles', function () {
            return view('admin.coming-soon', ['title' => 'Roles & Permissions']);
        })->name('roles');

        Route::get('/general', function () {
            return view('admin.coming-soon', ['title' => 'General Settings']);
        })->name('general');

        Route::get('/backup', function () {
            return view('admin.coming-soon', ['title' => 'Backup & Restore']);
        })->name('backup');

        Route::get('/activity-log', function () {
            return view('admin.coming-soon', ['title' => 'Activity Logs']);
        })->name('activity-log');
    });

});
