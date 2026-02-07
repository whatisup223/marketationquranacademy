<?php
// dashboard/index.php (ROUTER)
require 'includes/header.php';

// Redirect logic based on role
if ($user_role === 'admin') {
    header("Location: admin/index.php?lang=$lang");
    exit;
} elseif ($user_role === 'teacher') {
    header("Location: teacher/index.php?lang=$lang");
    exit;
} else {
    header("Location: student/index.php?lang=$lang");
    exit;
}
?>

<?php
// PHP logic to fetch stats
require 'db.php';
$user_id = $_SESSION['user_id'] ?? 0;

if ($user_role === 'teacher') {
    $course_count = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE teacher_id = ?"); // Assuming teacher_id column in courses
    // For now, let's just show general counts if column doesn't exist yet
    $course_count = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    $session_count = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE teacher_id = ? AND start_time > datetime('now')");
    $session_count->execute([$user_id]);
    $upcoming_sessions = $session_count->fetchColumn();
} else {
    $course_count = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE user_id = ?");
    $course_count->execute([$user_id]);
    $my_courses = $course_count->fetchColumn();

    $session_count = $pdo->prepare("
        SELECT COUNT(*) FROM sessions s
        JOIN enrollments e ON s.course_id = e.course_id
        WHERE e.user_id = ? AND s.start_time > datetime('now')
    ");
    $session_count->execute([$user_id]);
    $upcoming_sessions = $session_count->fetchColumn();
}
?>

<div class="max-w-6xl mx-auto py-8 px-6">
    <!-- Welcome Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">
                <?php echo t('أهلاً بك مجدداً،', 'Welcome back,'); ?> <?php echo htmlspecialchars($user_name); ?> 👋
            </h1>
            <p class="text-slate-500 font-medium">
                <?php echo $user_role === 'teacher'
                    ? t('لديك حصص مجدولة اليوم، نتمنى لك وقتاً ممتعاً مع طلابك.', 'You have scheduled classes today, enjoy your time with students.')
                    : t('استكمل رحلتك في حفظ القرآن الكريم وعلوم اللغة العربية.', 'Continue your journey in Quran memorization and Arabic language.'); ?>
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
                <div class="text-sm font-bold text-slate-800 dark:text-gray-200"><?php echo $user_role_label; ?></div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
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
                <?php echo $user_role === 'teacher' ? t('دوراتي', 'My Courses') : t('الدورات الملتحق بها', 'My Courses'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white">
                <?php echo $user_role === 'teacher' ? $course_count : $my_courses; ?>
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
                <?php echo t('حصص قادمة', 'Upcoming Sessions'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white"><?php echo $upcoming_sessions; ?></div>
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

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div
                class="bg-white dark:bg-slate-900 p-8 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-6">
                        <?php echo t('بداية سريعة', 'Quick Start'); ?>
                    </h3>
                    <p class="text-slate-500 leading-relaxed mb-8 font-medium">
                        <?php echo t('نحن نعمل على تجهيز جدولك المخصص وموادك التعليمية. قريباً ستظهر هنا أحدث المواد الدراسية وآخر الحصص المسجلة.', 'We are working on setting up your custom schedule and learning materials. Soon, the latest study materials and recorded sessions will appear here.'); ?>
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="<?php echo $user_role === 'teacher' ? 'my-courses.php' : 'my-learning.php'; ?>?lang=<?php echo $lang; ?>"
                            class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                            <?php echo t('تصفح المقررات', 'Browse Courses'); ?>
                        </a>
                        <a href="<?php echo $user_role === 'teacher' ? 'my-sessions.php' : 'my-schedule.php'; ?>?lang=<?php echo $lang; ?>"
                            class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                            <?php echo t('عرض الجدول', 'View Schedule'); ?>
                        </a>
                    </div>
                </div>
                <!-- Decorative element -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 blur-3xl -mr-20 -mt-20"></div>
            </div>
        </div>

        <div class="space-y-6">
            <div
                class="bg-slate-900 p-8 rounded-[3rem] text-white shadow-xl shadow-slate-900/20 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-black mb-4"><?php echo t('مركز المساعدة', 'Help Center'); ?></h4>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6 font-medium">
                        <?php echo t('هل تواجه مشكلة تقنية أو لديك استفسار عن المناهج؟ فريقنا متاح دائماً للمساعدة.', 'Facing a technical issue or have a query about the curriculum? Our team is always here to help.'); ?>
                    </p>
                    <button
                        class="w-full py-4 bg-white text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-50 transition-all">
                        <?php echo t('تحدث معنا', 'Contact Support'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>