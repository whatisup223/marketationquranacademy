<?php
// dashboard/admin/index.php
// A placeholder main file to test the layout
require '../includes/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card -->
    <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:-translate-y-1 transition-transform">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 dark:bg-indigo-500/10 rounded-full group-hover:scale-110 transition-transform">
        </div>
        <div class="relative z-10">
            <div class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">
                <?php echo t('إجمالي المستخدمين', 'Total Users'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">2,543</div>
            <div class="text-xs font-bold text-green-500 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span>+12.5%</span>
            </div>
        </div>
    </div>
    <!-- Stat Card -->
    <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:-translate-y-1 transition-transform">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 dark:bg-amber-500/10 rounded-full group-hover:scale-110 transition-transform">
        </div>
        <div class="relative z-10">
            <div class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">
                <?php echo t('الإيرادات الشهرية', 'Monthly Revenue'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">$45.2k</div>
            <div class="text-xs font-bold text-green-500 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span>+8.2%</span>
            </div>
        </div>
    </div>
    <!-- Stat Card -->
    <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:-translate-y-1 transition-transform">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 dark:bg-rose-500/10 rounded-full group-hover:scale-110 transition-transform">
        </div>
        <div class="relative z-10">
            <div class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">
                <?php echo t('الحصص النشطة', 'Active Classes'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">128</div>
            <div class="text-xs font-bold text-slate-400">
                <span>
                    <?php echo t('الآن', 'Right Now'); ?>
                </span>
            </div>
        </div>
    </div>
    <!-- Stat Card -->
    <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:-translate-y-1 transition-transform">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-full group-hover:scale-110 transition-transform">
        </div>
        <div class="relative z-10">
            <div class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">
                <?php echo t('المعلمين الجدد', 'New Teachers'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">12</div>
            <div class="text-xs font-bold text-slate-400">
                <span>
                    <?php echo t('هذا الأسبوع', 'This Week'); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div
        class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50">
        <h3 class="font-bold text-lg mb-6 text-slate-800 dark:text-white">
            <?php echo t('أحدث التسجيلات', 'Recent Registrations'); ?>
        </h3>
        <div class="space-y-4">
            <!-- Row -->
            <div
                class="flex items-center gap-4 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 rounded-2xl transition-colors">
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                    AH</div>
                <div class="flex-1">
                    <h4 class="font-bold text-sm text-slate-800 dark:text-white">Ahmed Hassan</h4>
                    <p class="text-xs text-slate-500">Parent Account</p>
                </div>
                <span class="text-xs font-bold bg-green-100 text-green-700 px-2 py-1 rounded-lg">Verified</span>
            </div>
            <!-- Row -->
            <div
                class="flex items-center gap-4 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 rounded-2xl transition-colors">
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                    FA</div>
                <div class="flex-1">
                    <h4 class="font-bold text-sm text-slate-800 dark:text-white">Fatima Ali</h4>
                    <p class="text-xs text-slate-500">Student Account</p>
                </div>
                <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-1 rounded-lg">Pending</span>
            </div>
            <!-- Row -->
            <div
                class="flex items-center gap-4 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 rounded-2xl transition-colors">
                <div
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                    OM</div>
                <div class="flex-1">
                    <h4 class="font-bold text-sm text-slate-800 dark:text-white">Omar Mahmoud</h4>
                    <p class="text-xs text-slate-500">Teacher Account</p>
                </div>
                <span class="text-xs font-bold bg-green-100 text-green-700 px-2 py-1 rounded-lg">Verified</span>
            </div>
        </div>
    </div>

    <div class="bg-indigo-600 p-6 rounded-3xl text-white relative overflow-hidden shadow-xl shadow-indigo-600/20">
        <div class="relative z-10">
            <h3 class="font-bold text-lg mb-2">
                <?php echo t('برو ماكس', 'Pro Max Plan'); ?> 🚀
            </h3>
            <p class="text-sm text-indigo-100 mb-6 opacity-90">
                <?php echo t('قم بترقية خطتك للحصول على ميزات إضافية وتقارير متقدمة.', 'Upgrade your plan to get more features and advanced reports.'); ?>
            </p>
            <button
                class="w-full py-3 bg-white text-indigo-600 rounded-xl font-black text-sm hover:bg-indigo-50 transition-colors">
                <?php echo t('ترقية الآن', 'Upgrade Now'); ?>
            </button>
        </div>
        <!-- Decorative -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-indigo-900/40 rounded-full blur-2xl -ml-10 -mb-10"></div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>