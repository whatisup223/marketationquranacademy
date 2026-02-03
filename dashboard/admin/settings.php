<?php
require '../includes/header.php';
require '../db.php';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key !== 'submit') {
            $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (setting_key, setting_value, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP)");
            $stmt->execute([$key, $value]);
        }
    }
    $success = t('تم حفظ الإعدادات بنجاح', 'Settings saved successfully');
}

// Fetch current settings
$settings = [];
$result = $pdo->query("SELECT setting_key, setting_value FROM settings");
while ($row = $result->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Default values
$defaults = [
    'site_name' => 'أهل السنة',
    'site_email' => 'info@elsona.com',
    'site_phone' => '+966 XX XXX XXXX',
    'timezone' => 'Asia/Riyadh',
    'currency' => 'SAR',
    'students_per_class' => '10',
    'class_duration' => '60',
    'enable_registration' => '1',
    'enable_notifications' => '1',
];

foreach ($defaults as $key => $value) {
    if (!isset($settings[$key])) {
        $settings[$key] = $value;
    }
}
?>

<div class="mb-8">
    <h2 class="text-3xl font-black text-slate-800 dark:text-white">
        <?php echo t('الإعدادات', 'Settings'); ?>
    </h2>
    <p class="text-slate-500 text-sm mt-1">
        <?php echo t('إدارة إعدادات النظام العامة', 'Manage general system settings'); ?>
    </p>
</div>

<?php if (isset($success)): ?>
    <div
        class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 rounded-xl text-emerald-700 dark:text-emerald-400 font-bold flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <?php echo $success; ?>
    </div>
<?php endif; ?>

<form method="POST" class="space-y-6">
    <!-- General Settings -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                    </path>
                </svg>
            </div>
            <?php echo t('الإعدادات العامة', 'General Settings'); ?>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('اسم الموقع', 'Site Name'); ?>
                </label>
                <input type="text" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>"
                    class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('البريد الإلكتروني', 'Email'); ?>
                </label>
                <input type="email" name="site_email" value="<?php echo htmlspecialchars($settings['site_email']); ?>"
                    class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('رقم الهاتف', 'Phone Number'); ?>
                </label>
                <input type="text" name="site_phone" value="<?php echo htmlspecialchars($settings['site_phone']); ?>"
                    class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('المنطقة الزمنية', 'Timezone'); ?>
                </label>
                <select name="timezone"
                    class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="Asia/Riyadh" <?php echo $settings['timezone'] === 'Asia/Riyadh' ? 'selected' : ''; ?>
                        >Asia/Riyadh</option>
                    <option value="Asia/Dubai" <?php echo $settings['timezone'] === 'Asia/Dubai' ? 'selected' : ''; ?>
                        >Asia/Dubai</option>
                    <option value="Europe/London" <?php echo $settings['timezone'] === 'Europe/London' ? 'selected' : ''; ?>>Europe/London</option>
                    <option value="America/New_York" <?php echo $settings['timezone'] === 'America/New_York' ? 'selected' : ''; ?>>America/New_York</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('العملة', 'Currency'); ?>
                </label>
                <select name="currency"
                    class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="SAR" <?php echo $settings['currency'] === 'SAR' ? 'selected' : ''; ?>>SAR - ريال سعودي
                    </option>
                    <option value="USD" <?php echo $settings['currency'] === 'USD' ? 'selected' : ''; ?>>USD - دولار
                        أمريكي</option>
                    <option value="EUR" <?php echo $settings['currency'] === 'EUR' ? 'selected' : ''; ?>>EUR - يورو
                    </option>
                </select>
            </div>
        </div>
    </div>

    <!-- Class Settings -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <?php echo t('إعدادات الحصص', 'Class Settings'); ?>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('عدد الطلاب لكل حصة', 'Students Per Class'); ?>
                </label>
                <input type="number" name="students_per_class"
                    value="<?php echo htmlspecialchars($settings['students_per_class']); ?>"
                    class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('مدة الحصة (دقيقة)', 'Class Duration (minutes)'); ?>
                </label>
                <input type="number" name="class_duration"
                    value="<?php echo htmlspecialchars($settings['class_duration']); ?>"
                    class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <!-- System Settings -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-6">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <?php echo t('إعدادات النظام', 'System Settings'); ?>
        </h3>

        <div class="space-y-4">
            <label
                class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <span class="font-bold text-sm text-slate-700 dark:text-slate-300">
                    <?php echo t('السماح بالتسجيل الجديد', 'Enable Registration'); ?>
                </span>
                <input type="checkbox" name="enable_registration" value="1" <?php echo $settings['enable_registration'] ? 'checked' : ''; ?>
                class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
            </label>

            <label
                class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <span class="font-bold text-sm text-slate-700 dark:text-slate-300">
                    <?php echo t('تفعيل الإشعارات', 'Enable Notifications'); ?>
                </span>
                <input type="checkbox" name="enable_notifications" value="1" <?php echo $settings['enable_notifications'] ? 'checked' : ''; ?>
                class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500">
            </label>
        </div>
    </div>

    <!-- Save Button -->
    <div class="flex justify-end">
        <button type="submit" name="submit"
            class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <?php echo t('حفظ التغييرات', 'Save Changes'); ?>
        </button>
    </div>
</form>

<?php require '../includes/footer.php'; ?>