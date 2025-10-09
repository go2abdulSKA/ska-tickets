# User Profile Photo Upload Troubleshooting

## 🔍 Issue: Photo uploads but doesn't save to database

I've added extensive logging to help debug. Follow these steps:

## ✅ Step 1: Check User Model

**File:** `app/Models/User.php`

Make sure `profile_photo_path` is in the `$fillable` array:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role_id',
    'phone',
    'profile_photo_path',  // ← Must be here!
    'is_active',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'created_by',
    'updated_by',
];
```

## ✅ Step 2: Check Storage Configuration

```bash
# Ensure storage link exists
php artisan storage:link

# Check permissions
chmod -R 755 storage/app/public

# Create profile-photos directory if missing
mkdir -p storage/app/public/profile-photos
chmod 755 storage/app/public/profile-photos
```

## ✅ Step 3: Check Database Column

Run this in Tinker or check your migration:

```bash
php artisan tinker
```

```php
// Check if column exists
Schema::hasColumn('users', 'profile_photo_path');
// Should return: true
```

If false, you need to add the migration:

```php
// Create migration
php artisan make:migration add_profile_photo_path_to_users_table

// In migration file:
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'profile_photo_path')) {
            $table->string('profile_photo_path', 2048)->nullable()->after('email');
        }
    });
}

// Run it
php artisan migrate
```

## ✅ Step 4: Test Upload and Check Logs

1. **Upload a photo**
2. **Click Save**
3. **Check Laravel logs:**

```bash
tail -f storage/logs/laravel.log
```

You should see:

```
Processing profile photo upload
Profile photo saved successfully: profile-photos/xxxxxx.jpg
User created/updated with photo
```

## ✅ Step 5: Verify in Database

```bash
php artisan tinker
```

```php
// Check last created user
$user = User::latest()->first();
dd($user->profile_photo_path);
// Should show: "profile-photos/xxxxxx.jpg"
```

## ✅ Step 6: Check File Actually Saved

```bash
# List files in profile-photos directory
ls -la storage/app/public/profile-photos/

# Should see your uploaded file
# Example: 1234567890_photo.jpg
```

## 🐛 Common Issues & Solutions

### **Issue 1: Column not in fillable**

```
Error: Add [profile_photo_path] to fillable property
```

**Solution:**

```php
// In User.php
protected $fillable = [
    // ... other fields
    'profile_photo_path', // Add this
];
```

### **Issue 2: Storage link missing**

```
Error: File not found or image doesn't display
```

**Solution:**

```bash
php artisan storage:link
```

### **Issue 3: Permission denied**

```
Error: Unable to create file
```

**Solution:**

```bash
chmod -R 755 storage/app/public
chown -R www-data:www-data storage/app/public  # Linux
```

### **Issue 4: Livewire temporary upload fails**

```
Error: Class 'Livewire\TemporaryUploadedFile' not found
```

**Solution:**

```bash
# Clear Livewire temp files
php artisan livewire:delete-uploads

# Ensure Livewire is configured
php artisan livewire:publish --config
```

### **Issue 5: File too large**

```
Error: Maximum upload size exceeded
```

**Solution:**

Update `php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
```

Or `.env`:

```env
UPLOAD_MAX_FILESIZE=10M
POST_MAX_SIZE=10M
```

## 🧪 Test Script

Create a test route to verify everything:

```php
// routes/web.php
Route::get('/test-photo-upload', function() {
    $user = User::latest()->first();

    return [
        'user_id' => $user->id,
        'name' => $user->name,
        'profile_photo_path' => $user->profile_photo_path,
        'profile_photo_url' => $user->profile_photo_url,
        'file_exists' => $user->profile_photo_path
            ? Storage::disk('public')->exists($user->profile_photo_path)
            : false,
        'full_path' => $user->profile_photo_path
            ? storage_path('app/public/' . $user->profile_photo_path)
            : null,
    ];
});
```

Visit: `http://your-domain.com/test-photo-upload`

Expected output:

```json
{
    "user_id": 1,
    "name": "John Doe",
    "profile_photo_path": "profile-photos/1234567890_photo.jpg",
    "profile_photo_url": "http://your-domain.com/storage/profile-photos/1234567890_photo.jpg",
    "file_exists": true,
    "full_path": "/path/to/storage/app/public/profile-photos/1234567890_photo.jpg"
}
```

## 📝 Debugging Checklist

-   [ ] `profile_photo_path` in `$fillable` array
-   [ ] Storage link exists (`php artisan storage:link`)
-   [ ] Directory permissions correct (755)
-   [ ] Database column exists
-   [ ] Livewire config published
-   [ ] PHP upload limits sufficient
-   [ ] Check Laravel logs during upload
-   [ ] Verify file saved in `storage/app/public/profile-photos/`
-   [ ] Check database has path saved
-   [ ] Test with different image (JPG, PNG)

## 🚀 Expected Flow

1. **User uploads photo** → Livewire stores to temp
2. **User clicks save** → Component validates
3. **Photo stored** → `storage/app/public/profile-photos/xxx.jpg`
4. **Database updated** → `profile_photo_path` = "profile-photos/xxx.jpg"
5. **Page refreshes** → Photo displays from `/storage/profile-photos/xxx.jpg`

## 💡 Quick Fix Command

Run all these at once:

```bash
php artisan storage:link && \
chmod -R 755 storage/app/public && \
mkdir -p storage/app/public/profile-photos && \
php artisan config:clear && \
php artisan cache:clear && \
php artisan view:clear
```

Then try uploading again!

## 📞 Still Not Working?

Check the Laravel log output after clicking Save. Share the log lines that start with:

-   "Processing profile photo upload"
-   "Profile photo saved successfully" or any error messages
-   "User created" or "User updated"

This will help identify exactly where it's failing!
