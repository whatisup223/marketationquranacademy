<?php
require '../includes/header.php';
require '../db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Fetch upcoming sessions for enrolled courses
$stmt = $pdo->prepare("
    SELECT s.*, c.title as course_title, u.name as teacher_name
    FROM sessions s
    JOIN enrollments e ON s.course_id = e.course_id
    JOIN courses c ON s.course_id = c.id
    LEFT JOIN users u ON s.teacher_id = u.id
    WHERE e.user_id = ? AND s.start_time > datetime('now', '-1 hour')
    ORDER BY s.start_time ASC
");
$stmt->execute([$user_id]);
$sessions = $stmt->fetchAll();
?>

<div class="mb-12">
    <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-2">
        <?php echo t('جدول الحصص', 'My Schedule'); ?>
    </h2>
    <p class="text-slate-500 font-medium">
        <?php echo t('تابع مواعيد حصصك المباشرة القادمة', 'Track your upcoming live session schedules'); ?>
    </p>
</div>

<div class="grid grid-cols-1 gap-6">
    <?php foreach ($sessions as $session): ?>
        <div
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 flex flex-col md:flex-row items-center gap-8 shadow-sm group hover:border-indigo-100 dark:hover:border-indigo-500/30 transition-all">
            <!-- DateTime Box -->
            <div
                class="w-full md:w-32 h-32 bg-indigo-50 dark:bg-indigo-500/10 rounded-3xl flex flex-col items-center justify-center text-indigo-600 shrink-0">
                <span class="text-xs font-black uppercase tracking-widest text-indigo-400 mb-1">
                    <?php echo date('M', strtotime($session['start_time'])); ?>
                </span>
                <span class="text-4xl font-black">
                    <?php echo date('d', strtotime($session['start_time'])); ?>
                </span>
                <span class="text-[10px] font-bold">
                    <?php echo date('H:i', strtotime($session['start_time'])); ?>
                </span>
            </div>

            <div class="flex-1 text-center md:text-right">
                <div class="text-[10px] font-black text-indigo-500 mb-1 uppercase tracking-widest">
                    <?php echo htmlspecialchars($session['course_title']); ?>
                </div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-2">
                    <?php echo htmlspecialchars($session['title']); ?>
                </h3>
                <div class="flex items-center justify-center md:justify-start gap-3 text-slate-500 font-bold mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-sm">
                        <?php echo htmlspecialchars($session['teacher_name']); ?>
                    </span>
                </div>

                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                    <span
                        class="px-3 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-500/20">
                        <?php echo t('قريباً', 'Upcoming'); ?>
                    </span>
                    <span class="text-xs text-slate-400 font-bold">
                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <?php echo date('l', strtotime($session['start_time'])); ?>
                    </span>
                </div>
            </div>

            <div class="w-full md:w-auto">
                <a href="<?php echo htmlspecialchars($session['meeting_link']); ?>" target="_blank"
                    class="block w-full md:w-auto px-10 py-5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-[2rem] font-black text-xs uppercase tracking-widest hover:bg-indigo-600 dark:hover:bg-indigo-600 hover:text-white transition-all text-center shadow-xl shadow-slate-900/10">
                    <?php echo t('انضمام الآن', 'Join Now'); ?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($sessions)): ?>
        <div
            class="py-24 text-center bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800">
            <div
                class="w-20 h-20 bg-slate-50 dark:bg-slate-850 rounded-3xl flex items-center justify-center mx-auto mb-8 border border-slate-100 dark:border-slate-800">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h4 class="text-xl font-black text-slate-800 dark:text-gray-200 mb-2">
                <?php echo t('لا توجد حصص قريبة', 'No upcoming sessions'); ?>
            </h4>
            <p class="text-slate-500 font-medium">
                <?php echo t('يبدو أنه لا توجد حصص مجدولة حالياً لدوراتك.', 'Looks like there are no sessions scheduled for your courses at the moment.'); ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<?php require '../includes/footer.php'; ?>