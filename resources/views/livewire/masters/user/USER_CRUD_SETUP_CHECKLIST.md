# User CRUD - Setup Checklist

## 📋 Step-by-Step Implementation

### ✅ **Step 1: Create Component**

```bash
# Component already created
app/Livewire/Masters/User/UserList.php
```

### ✅ **Step 2: Create Blade Views**

```bash
# All views already created
resources/views/livewire/masters/user/
├── index.blade.php       ✅
├── add-user.blade.php    ✅
├── view-user.blade.php   ✅
└── delete-user.blade.php ✅
```

### ☐ **Step 3: Add Route**

**File:** `routes/web.php`

```php
use App\Livewire\Masters\User\UserList;

Route::middleware(['auth'])->group(function () {
    Route::prefix('masters')->name('masters.')->group(function () {
        Route::get('/user', UserList::class)->name('user');
    });
});
```

### ☐ **Step 4: Add Sidebar Menu**

**File:** `resources/views/layouts/partials/sidebar.blade.php`

Find your Masters section and add:

```blade
{{-- Users --}}
<li class="side-nav-item">
    <a href="{{ route('masters.user') }}"
       class="side-nav-link {{ request()->routeIs('masters.user') ? 'active' : '' }}">
        <i class="ti ti-users"></i>
        <span> Users </span>
    </a>
</li>
```

### ☐ **Step 5: Add Profile Dropdown in Header**

**File:** `resources/views/layouts/partials/header.blade.php` (or your header file)

Add the profile dropdown code (see Profile Dropdown artifact)

### ☐ **Step 6: Verify User Model**

**File:** `app/Models/User.php`

Ensure these relationships exist:

```php
public function role()
{
    return $this->belongsTo(Role::class);
}

public function departments()
{
    return $this->belongsToMany(Department::class, 'user_departments');
}

public function tickets()
{
    return $this->hasMany(TicketMaster::class, 'created_by');
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function updater()
{
    return $this->belongsTo(User::class, 'updated_by');
}
```

### ☐ **Step 7: Test User CRUD**

**Admin Panel Tests:**

-   [ ] Navigate to Masters → Users
-   [ ] See users list with profile photos
-   [ ] Search by name/email
-   [ ] Filter by role/department/status
-   [ ] Click "Add User" - modal opens
-   [ ] Upload profile photo - preview shows
-   [ ] Fill form and save - user created
-   [ ] Click view icon - profile card shows
-   [ ] Click edit icon - modal opens with data
-   [ ] Update user - changes saved
-   [ ] Try to delete user with tickets - prevented
-   [ ] Delete user without tickets - success

**Jetstream Profile Tests:**

-   [ ] Click profile dropdown → "My Account"
-   [ ] Goes to `/user/profile`
-   [ ] Update profile info works
-   [ ] Upload new photo works
-   [ ] Change password works
-   [ ] Enable 2FA works
-   [ ] View active sessions works
-   [ ] Logout other sessions works

## 🎯 Access URLs

| Feature             | URL             | Description                           |
| ------------------- | --------------- | ------------------------------------- |
| **User Management** | `/masters/user` | Admin panel for managing all users    |
| **My Profile**      | `/user/profile` | Jetstream profile page (self-service) |
| **Logout**          | `/logout`       | POST request to logout                |

## 🔐 Security Checklist

-   [ ] Users cannot delete themselves
-   [ ] Users cannot deactivate themselves
-   [ ] Cannot delete users with tickets
-   [ ] Password hashed on create/update
-   [ ] Profile photos stored securely
-   [ ] Email validation working
-   [ ] Role assignment restricted to admins
-   [ ] Department assignment working

## 🎨 Design Checklist

-   [ ] Profile photos circular (40px in list, 120px in view)
-   [ ] First letter placeholders for users without photos
-   [ ] Colored backgrounds for placeholders
-   [ ] Upload preview working in modal
-   [ ] Profile card style in view offcanvas
-   [ ] Icons loading properly (Lucide + Tabler)
-   [ ] Responsive layout working
-   [ ] Loading states on buttons

## 📊 Features Checklist

### **List View:**

-   [ ] Profile photo column
-   [ ] Name (clickable to view)
-   [ ] Email
-   [ ] Role badge
-   [ ] Department badges (multiple)
-   [ ] Phone number
-   [ ] Ticket count
-   [ ] Created date
-   [ ] Status badge
-   [ ] Action buttons (view, edit, delete)

### **Add/Edit Modal:**

-   [ ] Profile photo upload (top)
-   [ ] Upload preview
-   [ ] Name field
-   [ ] Email field
-   [ ] Password fields (create only / optional on edit)
-   [ ] Role dropdown
-   [ ] Department multi-select
-   [ ] Phone field
-   [ ] Active checkbox
-   [ ] Loading state on save

### **View Offcanvas:**

-   [ ] Profile photo (large, centered)
-   [ ] Name (large)
-   [ ] Email (with icon)
-   [ ] Status badge
-   [ ] Role badge
-   [ ] Department badges
-   [ ] Phone (with icon)
-   [ ] Ticket count
-   [ ] 2FA status (if enabled)
-   [ ] Created by & date
-   [ ] Updated by & date
-   [ ] Edit button
-   [ ] Close button

### **Delete Modal:**

-   [ ] Profile photo & name
-   [ ] User details display
-   [ ] Usage validation (tickets)
-   [ ] Self-delete prevention
-   [ ] Clear warning message
-   [ ] Confirm/cancel buttons

## 🚀 Optional Enhancements

### **1. Welcome Email**

Send email when user is created:

```php
use App\Notifications\WelcomeUser;

$user->notify(new WelcomeUser($temporaryPassword));
```

### **2. Password Reset Link**

Generate reset link instead of temporary password:

```php
use Illuminate\Support\Facades\Password;

$token = Password::createToken($user);
$resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
```

### **3. Role-Based Access Control**

Restrict user management to admins:

```php
public function mount()
{
    abort_unless(auth()->user()->isAdmin(), 403, 'Unauthorized');
}
```

### **4. Activity Log**

Track user actions:

```php
activity()
    ->performedOn($user)
    ->causedBy(auth()->user())
    ->log('User profile updated');
```

### **5. Bulk Operations**

Add bulk delete/activate/deactivate:

```php
public function bulkActivate()
{
    User::whereIn('id', $this->selectedItems)
        ->update(['is_active' => true]);
}
```

## 📝 Common Issues & Solutions

### **Issue: Profile photo not showing**

```bash
# Ensure storage link exists
php artisan storage:link

# Check permissions
chmod -R 755 storage/app/public
```

### **Issue: Lucide icons disappearing**

-   ✅ Already fixed with multiple Livewire hooks in index.blade.php

### **Issue: Department multi-select not working**

-   Hold Ctrl (Windows) or Cmd (Mac) to select multiple
-   Or implement a better multi-select library like Select2

### **Issue: Cannot delete user**

-   Check if user has tickets
-   Check if trying to delete self
-   Check validation logic in component

### **Issue: Password not updating**

-   Ensure password is hashed: `Hash::make($password)`
-   Check validation rules for password confirmation

## ✨ You're Ready!

Once you complete this checklist, your User CRUD will be fully functional with:

-   ✅ Beautiful profile-focused design
-   ✅ Full CRUD operations
-   ✅ Jetstream integration
-   ✅ Profile photo management
-   ✅ Role & department assignments
-   ✅ Complete security validation

**Start URL:** `http://your-domain.com/masters/user`

🎉 **Happy user managing!**
