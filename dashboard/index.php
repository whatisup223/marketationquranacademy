<?php
// dashboard/index.php
require 'includes/header.php';

$user_role_label = t($_SESSION['user_role'] ?? 'طالب', $_SESSION['user_role'] ?? 'Student');
?>

<div class="max-w-4xl mx-auto py-12 px-6">
    <div
        class="bg-white dark:bg-slate-900 rounded-[3rem] p-12 border border-slate-100 dark:border-slate-800 shadow-2xl relative overflow-hidden text-center">
        <!-- Decorative Background -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/10 blur-[100px] rounded-full"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-500/10 blur-[100px] rounded-full"></div>

        <div class="relative z-10">
            <div
                class="w-24 h-24 bg-indigo-600 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-xl shadow-indigo-600/20">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>

            <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-4">
                <?php echo t('مرحباً بك في لوحة تحكمك', 'Welcome to Your Dashboard'); ?>
            </h1>

            <p class="text-lg text-slate-500 dark:text-slate-400 mb-10 max-w-xl mx-auto">
                <?php echo t('جاري العمل على تجهيز مسارك التعليمي المخصص. ستتمكن قريباً من متابعة حصصك، واجباتك، وتفاعلك مع المعلمين.', 'We are preparing your personalized learning path. Soon you will be able to track your classes, assignments, and interaction with teachers.'); ?>
            </p>

            <div
                class="inline-flex items-center gap-3 bg-indigo-50 dark:bg-indigo-500/10 px-6 py-3 rounded-2xl border border-indigo-100 dark:border-indigo-500/20 mb-10">
                <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">
                    <?php echo t('الحالة: قيد التجهيز', 'Status: Setting up your account'); ?>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="p-6 rounded-3xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">
                        <?php echo t('الرتبة', 'Role'); ?>
                    </div>
                    <div class="text-lg font-bold text-slate-800 dark:text-white">
                        <?php echo $user_role_label; ?>
                    </div>
                </div>
                <div
                    class="p-6 rounded-3xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">
                        <?php echo t('الدورات', 'Courses'); ?>
                    </div>
                    <div class="text-lg font-bold text-slate-800 dark:text-white">0</div>
                </div>
                <div
                    class="p-6 rounded-3xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">
                        <?php echo t('التقدم', 'Progress'); ?>
                    </div>
                    <div class="text-lg font-bold text-slate-800 dark:text-white">0%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>