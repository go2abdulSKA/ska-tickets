# Predefined Logos Setup Guide

## 📁 Directory Structure

The predefined logos are stored in:

```
public/storage/logos/predefined/
```

## 🚀 Setup Instructions

### 1. Create the Directory

Run these commands in your terminal:

```bash
# Create the predefined logos directory
mkdir -p public/storage/logos/predefined

# Set proper permissions
chmod -R 755 public/storage/logos
```

### 2. Add Initial Logos

Place your predefined logo files in the `public/storage/logos/predefined/` directory.

**Recommended naming convention:**

-   `ska-only.png`
-   `ska-group.png`
-   `ska-energy.png`
-   `ska-somalia.png`
-   `srm-logo.png`
-   `ska-toyota.png`
-   `dfac-logo.png`
-   `vees-lounge.png`
-   etc.

**Supported formats:**

-   JPG/JPEG
-   PNG
-   GIF
-   SVG

**File size:**

-   Maximum: 2MB per file
-   Recommended: Under 500KB for faster loading

### 3. File Naming Best Practices

The system automatically converts filenames to display names:

-   `ska-only.png` → "Ska Only"
-   `ska-group.png` → "Ska Group"
-   `risk_management.png` → "Risk Management"

**Tips:**

-   Use hyphens (-) or underscores (\_) to separate words
-   Use lowercase for consistency
-   Avoid spaces in filenames

## 🎯 How It Works

### For Users:

1. **Selecting Existing Logo:**

    - Open Add/Edit Department modal
    - Choose from the "Predefined Logos" dropdown
    - Preview appears automatically
    - Save department

2. **Adding New Logo:**
    - Select "➕ Add New Logo to Library" from dropdown
    - Upload section appears
    - Choose your logo file
    - Preview shows before saving
    - Click Save - logo is now available for all future departments!

### For Developers:

The `getPredefinedLogos()` method in `DepartmentList.php`:

```php
public function getPredefinedLogos()
{
    $predefinedPath = public_path('storage/logos/predefined');

    // Auto-creates directory if missing
    if (!file_exists($predefinedPath)) {
        mkdir($predefinedPath, 0755, true);
    }

    // Scans for image files
    $logos = [];
    $files = glob($predefinedPath . '/*.{jpg,jpeg,png,gif,svg}', GLOB_BRACE);

    foreach ($files as $file) {
        $filename = basename($file);
        $name = ucwords(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME)));

        $logos[] = [
            'path' => 'logos/predefined/' . $filename,
            'name' => $name,
            'filename' => $filename,
        ];
    }

    return $logos;
}
```

## 💾 Storage Logic

### Predefined Logo Selected:

-   Logo path stored directly: `logos/predefined/ska-only.png`
-   No file copying - uses existing file
-   Multiple departments can share the same logo

### New Logo Uploaded (Add to Library):

-   Saved to: `public/storage/logos/predefined/`
-   Filename: `timestamp_originalname.png`
-   Immediately available in dropdown for all users
-   Never deleted (permanent library)

### Regular Upload (Not using predefined):

-   Saved to: `public/storage/departments/logos/`
-   Unique per department
-   Deleted when department logo is changed/deleted

## 🔧 Maintenance

### To Remove a Predefined Logo:

1. Navigate to `public/storage/logos/predefined/`
2. Delete the unwanted logo file
3. The logo will no longer appear in the dropdown

### To Add Multiple Logos at Once:

```bash
# Copy multiple logos
cp /path/to/logos/*.png public/storage/logos/predefined/

# Set permissions
chmod 644 public/storage/logos/predefined/*.png
```

## ✅ Verification

Check if setup is correct:

```bash
# List all predefined logos
ls -lh public/storage/logos/predefined/

# Check permissions
ls -ld public/storage/logos/predefined/
# Should show: drwxr-xr-x
```

## 🎨 Example Logos to Add

Create or download these logos for SKA departments:

-   SKA Only Logo
-   SKA Group Logo
-   SKA Energy Logo
-   SKA Somalia Logo
-   SRM (Risk Management) Logo
-   SKA Toyota Logo
-   DFAC Logo# Predefined Logos Setup Guide

## 📁 Directory Structure

The predefined logos are stored in:

```
public/storage/logos/predefined/
```

## 🚀 Setup Instructions

### 1. Create the Directory

Run these commands in your terminal:

```bash
# Create the predefined logos directory
mkdir -p public/storage/logos/predefined

# Set proper permissions
chmod -R 755 public/storage/logos
```

### 2. Add Initial Logos

Place your predefined logo files in the `public/storage/logos/predefined/` directory.

**Recommended naming convention:**

-   `ska-only.png`
-   `ska-group.png`
-   `ska-energy.png`
-   `ska-somalia.png`
-   `srm-logo.png`
-   `ska-toyota.png`
-   `dfac-logo.png`
-   `vees-lounge.png`
-   etc.

**Supported formats:**

-   JPG/JPEG
-   PNG
-   GIF
-   SVG

**File size:**

-   Maximum: 2MB per file
-   Recommended: Under 500KB for faster loading

### 3. File Naming Best Practices

The system automatically converts filenames to display names:

-   `ska-only.png` → "Ska Only"
-   `ska-group.png` → "Ska Group"
-   `risk_management.png` → "Risk Management"

**Tips:**

-   Use hyphens (-) or underscores (\_) to separate words
-   Use lowercase for consistency
-   Avoid spaces in filenames

## 🎯 How It Works

### For Users:

1. **Selecting Existing Logo:**

    - Open Add/Edit Department modal
    - Choose from the "Predefined Logos" dropdown
    - Preview appears automatically
    - Save department

2. **Adding New Logo:**
    - Select "➕ Add New Logo to Library" from dropdown
    - Upload section appears
    - Choose your logo file
    - Preview shows before saving
    - Click Save - logo is now available for all future departments!

### For Developers:

The `getPredefinedLogos()` method in `DepartmentList.php`:

```php
public function getPredefinedLogos()
{
    $predefinedPath = public_path('storage/logos/predefined');

    // Auto-creates directory if missing
    if (!file_exists($predefinedPath)) {
        mkdir($predefinedPath, 0755, true);
    }

    // Scans for image files
    $logos = [];
    $files = glob($predefinedPath . '/*.{jpg,jpeg,png,gif,svg}', GLOB_BRACE);

    foreach ($files as $file) {
        $filename = basename($file);
        $name = ucwords(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME)));

        $logos[] = [
            'path' => 'logos/predefined/' . $filename,
            'name' => $name,
            'filename' => $filename,
        ];
    }

    return $logos;
}
```

## 💾 Storage Logic

### Predefined Logo Selected:

-   Logo path stored directly: `logos/predefined/ska-only.png`
-   No file copying - uses existing file
-   Multiple departments can share the same logo

### New Logo Uploaded (Add to Library):

-   Saved to: `public/storage/logos/predefined/`
-   Filename: `timestamp_originalname.png`
-   Immediately available in dropdown for all users
-   Never deleted (permanent library)

### Regular Upload (Not using predefined):

-   Saved to: `public/storage/departments/logos/`
-   Unique per department
-   Deleted when department logo is changed/deleted

## 🔧 Maintenance

### To Remove a Predefined Logo:

1. Navigate to `public/storage/logos/predefined/`
2. Delete the unwanted logo file
3. The logo will no longer appear in the dropdown

### To Add Multiple Logos at Once:

```bash
# Copy multiple logos
cp /path/to/logos/*.png public/storage/logos/predefined/

# Set permissions
chmod 644 public/storage/logos/predefined/*.png
```

## ✅ Verification

Check if setup is correct:

```bash
# List all predefined logos
ls -lh public/storage/logos/predefined/

# Check permissions
ls -ld public/storage/logos/predefined/
# Should show: drwxr-xr-x
```

## 🎨 Example Logos to Add

Create or download these logos for SKA departments:

-   SKA Only Logo
-   SKA Group Logo
-   SKA Energy Logo
-   SKA Somalia Logo
-   SRM (Risk Management) Logo
-   SKA Toyota Logo
-   DFAC Logo
-   Vees Lounge Logo
-   PX Logo
-   Construction Logo
-   Logistics Logo
-   Life Support Logo
-   WFP Logo

## 📝 Notes

-   The system automatically creates the directory on first load if missing
-   Logos are shared across all departments
-   Adding a new logo to the library makes it instantly available
-   No database storage for predefined logos - file-based only
-   Supports unlimited predefined logos

## 🚨 Troubleshooting

**Issue:** Logos not showing in dropdown

-   Check directory exists: `public/storage/logos/predefined/`
-   Check file permissions: `chmod 755` on directory, `644` on files
-   Check storage link: `php artisan storage:link`

**Issue:** Upload fails

-   Check `php.ini` settings: `upload_max_filesize` and `post_max_size`
-   Check disk space
-   Check file permissions on `storage/` directory

**Issue:** Image not displaying

-   Verify storage link: `php artisan storage:link`
-   Check image path in database
-   Check browser console for 404 errors
-   Vees Lounge Logo
-   PX Logo
-   Construction Logo
-   Logistics Logo
-   Life Support Logo
-   WFP Logo

## 📝 Notes

-   The system automatically creates the directory on first load if missing
-   Logos are shared across all departments
-   Adding a new logo to the library makes it instantly available
-   No database storage for predefined logos - file-based only
-   Supports unlimited predefined logos

## 🚨 Troubleshooting

**Issue:** Logos not showing in dropdown

-   Check directory exists: `public/storage/logos/predefined/`
-   Check file permissions: `chmod 755` on directory, `644` on files
-   Check storage link: `php artisan storage:link`

**Issue:** Upload fails

-   Check `php.ini` settings: `upload_max_filesize` and `post_max_size`
-   Check disk space
-   Check file permissions on `storage/` directory

**Issue:** Image not displaying

-   Verify storage link: `php artisan storage:link`
-   Check image path in database
-   Check browser console for 404 errors
