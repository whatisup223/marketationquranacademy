<?php
require '../includes/header.php';
require '../db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Fetch sessions for this teacher
$stmt = $pdo->prepare("
    SELECT s.*, c.title as course_title
    FROM sessions s
    JOIN courses c ON s.course_id = c.id
    WHERE s.teacher_id = ?
    ORDER BY s.start_time DESC
");
$stmt->execute([$user_id]);
$sessions = $stmt->fetchAll();
?>

<div class="flex items-center justify-between mb-12">
    <div>
        <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-2">
            <?php echo t('حصصي المباشرة', 'My Live Sessions'); ?>
        </h2>
        <p class="text-slate-500 font-medium">
            <?php echo t('إدارة مواعيد البث المباشر والتفاعل مع الطلاب', 'Manage live stream schedules and interact with students'); ?>
        </p>
    </div>
    <a href="index.php?lang=<?php echo $lang; ?>"
        class="hidden md:flex px-6 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 transition-all gap-2 items-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <?php echo t('جدولة حصة', 'Schedule Session'); ?>
    </a>
</div>

<div class="space-y-6">
    <?php foreach ($sessions as $session): ?>
        <div
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm flex flex-col md:flex-row items-center gap-8 group">
            <div
                class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 rounded-3xl flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                    </path>
                </svg>
            </div>

            <div class="flex-1 text-center md:text-right">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">
                        <?php echo htmlspecialchars($session['course_title']); ?>
                    </span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <?php echo date('Y-m-d H:i', strtotime($session['start_time'])); ?>
                    </span>
                </div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-2">
                    <?php echo htmlspecialchars($session['title']); ?>
                </h3>
                <div class="text-sm font-bold text-slate-400">
                    <?php echo t('عدد الحضور المتوقع: 15 طالب', 'Expected attendance: 15 students'); ?>
                </div>
            </div>

            <div class="flex gap-4 w-full md:w-auto">
                <a href="<?php echo htmlspecialchars($session['meeting_link']); ?>" target="_blank"
                    class="flex-1 md:flex-none px-10 py-5 bg-indigo-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all text-center">
                    <?php echo t('بدء البث', 'Start Session'); ?>
                </a>
                <button
                    class="px-6 py-5 bg-slate-50 dark:bg-slate-800 text-slate-400 rounded-[2rem] hover:text-rose-500 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($sessions)): ?>
        <div
            class="py-24 text-center bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800">
            <h4 class="text-xl font-black text-slate-800 dark:text-gray-200 mb-2">
                <?php echo t('لا توجد حصص مجدولة', 'No scheduled sessions'); ?>
            </h4>
            <p class="text-slate-500 font-medium">
                <?php echo t('لم تبدأ بجدولة حصصك بعد. ابدأ الآن بالتواصل مع طلابك.', 'You haven\'t started scheduling your sessions yet. Start connecting with your students now.'); ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<?php require '../includes/footer.php'; ?>