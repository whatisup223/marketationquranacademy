<?php
require '../includes/header.php';
require '../db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$user_role_label = t('معلم', 'Teacher');

// PHP logic to fetch teacher specific stats
$course_count = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn(); // Simplified for demo
$session_count = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE teacher_id = ? AND start_time > datetime('now')");
$session_count->execute([$user_id]);
$upcoming_sessions = $session_count->fetchColumn();
?>

<div class="max-w-6xl mx-auto py-8 px-6">
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">
                <?php echo t('لوحة تحكم المعلم', 'Teacher Dashboard'); ?> 👋
            </h1>
            <p class="text-slate-500 font-medium">
                <?php echo t('مرحباً بك يا فضيلة الشيخ، لديك حصص مجدولة اليوم.', 'Welcome Sheikh, you have sessions scheduled for today.'); ?>
            </p>
        </div>
        <div
            class="flex items-center gap-3 bg-white dark:bg-slate-900 p-2 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black">
                <?php echo strtoupper(substr($user_name, 0, 1)); ?>
            </div>
            <div class="pr-4 pl-8">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <?php echo t('الحساب', 'Account'); ?>
                </div>
                <div class="text-sm font-bold text-slate-800 dark:text-gray-200">
                    <?php echo $user_role_label; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div
            class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:-translate-y-1 transition-all">
            <div
                class="w-14 h-14 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                <?php echo t('دوراتي', 'My Courses'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">
                <?php echo $course_count; ?>
            </div>
        </div>

        <div
            class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:-translate-y-1 transition-all">
            <div
                class="w-14 h-14 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                <?php echo t('حصصي المجدولة', 'Sessions'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">
                <?php echo $upcoming_sessions; ?>
            </div>
        </div>

        <div
            class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:-translate-y-1 transition-all">
            <div
                class="w-14 h-14 bg-amber-50 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                <?php echo t('طلابي النشطين', 'Active Students'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">0</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div
            class="bg-indigo-600 p-10 rounded-[3rem] text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden">
            <h3 class="text-2xl font-black mb-6 relative z-10">
                <?php echo t('إدارة البث المباشر', 'Live Stream Management'); ?>
            </h3>
            <p class="text-indigo-100 mb-8 font-medium leading-relaxed relative z-10">
                <?php echo t('ابدأ حصصك المباشرة وتفاعل مع طلابك الآن من خلال منصة البث المدمجة.', 'Start your live sessions and interact with your students now through the integrated streaming platform.'); ?>
            </p>
            <a href="sessions.php?lang=<?php echo $lang; ?>"
                class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-50 transition-all relative z-10">
                <?php echo t('الذهاب للحصص', 'Go to Sessions'); ?>
            </a>
            <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div
            class="bg-white dark:bg-slate-900 p-10 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-6">
                <?php echo t('آخر التنبيهات', 'Recent Alerts'); ?>
            </h3>
            <div class="space-y-4">
                <div
                    class="p-4 bg-slate-50 dark:bg-slate-850 rounded-2xl border border-slate-100 dark:border-slate-800 text-sm font-bold text-slate-500">
                    <?php echo t('لا توجد تنبيهات جديدة حالياً.', 'No new alerts at the moment.'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>