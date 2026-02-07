<?php
require '../includes/header.php';
require '../db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$user_role_label = t('طالب', 'Student');

// Fetch student specific stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE user_id = ?");
$stmt->execute([$user_id]);
$my_courses = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM sessions s
    JOIN enrollments e ON s.course_id = e.course_id
    WHERE e.user_id = ? AND s.start_time > datetime('now')
");
$stmt->execute([$user_id]);
$upcoming_sessions = $stmt->fetchColumn();
?>

<div class="max-w-6xl mx-auto py-8 px-6">
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">
                <?php echo t('لوحة تحكم الطالب', 'Student Dashboard'); ?> 👋
            </h1>
            <p class="text-slate-500 font-medium">
                <?php echo t('استكمل رحلتك في حفظ القرآن الكريم.', 'Continue your journey in Quran memorization.'); ?>
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
                <?php echo $my_courses; ?>
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
                <?php echo t('حصصي القادمة', 'Sessions'); ?>
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
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                <?php echo t('الإنجاز', 'Achievement'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">0%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div
                class="bg-indigo-600 p-10 rounded-[3rem] text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden">
                <h3 class="text-2xl font-black mb-6 relative z-10">
                    <?php echo t('ابدأ التعلم الآن', 'Start Learning Now'); ?>
                </h3>
                <p class="text-indigo-100 mb-8 font-medium leading-relaxed relative z-10">
                    <?php echo t('تصفح المقررات الدراسية المتاحة وابدأ رحلتك في تعلم كتاب الله الكريم.', 'Browse available courses and start your journey in learning the Holy Quran.'); ?>
                </p>
                <a href="learning.php?lang=<?php echo $lang; ?>"
                    class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-50 transition-all relative z-10">
                    <?php echo t('تصفح الدورات', 'Browse Courses'); ?>
                </a>
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
            </div>
        </div>
        <div
            class="bg-white dark:bg-slate-900 p-10 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 leading-tight">
                <?php echo t('الحصة التالية', 'Next Session'); ?>
            </h4>
            <div class="text-center py-6">
                <div class="text-slate-400 font-bold">
                    <?php echo t('لا توجد حصص قريبة', 'No sessions soon'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>