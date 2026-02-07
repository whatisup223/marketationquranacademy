<?php
require '../includes/header.php';
require '../db.php';

// Handle session creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_session'])) {
    $course_id = $_POST['course_id'];
    $teacher_id = $_POST['teacher_id'];
    $title = $_POST['title'];
    $meeting_link = $_POST['meeting_link'];
    $start_time = $_POST['start_time'];

    $stmt = $pdo->prepare("INSERT INTO sessions (course_id, teacher_id, title, meeting_link, start_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$course_id, $teacher_id, $title, $meeting_link, $start_time]);
    header("Location: sessions.php?lang=$lang");
    exit;
}

// Handle session deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM sessions WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: sessions.php?lang=$lang");
    exit;
}

// Fetch all sessions with course and teacher info
$sessions = $pdo->query("
    SELECT s.*, c.title as course_title, u.name as teacher_name 
    FROM sessions s 
    LEFT JOIN courses c ON s.course_id = c.id 
    LEFT JOIN users u ON s.teacher_id = u.id 
    ORDER BY s.start_time ASC
")->fetchAll();

$courses = $pdo->query("SELECT id, title FROM courses")->fetchAll();
$teachers = $pdo->query("SELECT id, name FROM users WHERE role = 'teacher' OR role = 'admin'")->fetchAll();
?>

<div x-data="{ modalOpen: false }">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white">
                <?php echo t('إدارة الحصص المباشرة', 'Live Sessions Management'); ?>
            </h2>
            <p class="text-slate-500 text-sm mt-1">
                <?php echo t('جدولة وإدارة حلقات تحفيظ القرآن المباشرة', 'Schedule and manage live Quran memorization sessions'); ?>
            </p>
        </div>
        <button @click="modalOpen = true"
            class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <?php echo t('جدولة حصة جديدة', 'Schedule New Session'); ?>
        </button>
    </div>

    <!-- Sessions Table -->
    <div
        class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('الحصة / الدورة', 'Session / Course'); ?>
                        </th>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('المعلم', 'Teacher'); ?>
                        </th>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('التوقيت', 'Time'); ?>
                        </th>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('الحالة', 'Status'); ?>
                        </th>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('الإجراءات', 'Actions'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    <?php foreach ($sessions as $session): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-800 dark:text-white">
                                    <?php echo htmlspecialchars($session['title']); ?>
                                </div>
                                <div class="text-[10px] font-black text-indigo-500 uppercase">
                                    <?php echo htmlspecialchars($session['course_title']); ?>
                                </div>
                            </td>
                            <td class="px-8 py-5 italic text-slate-600 dark:text-slate-400 text-sm">
                                <?php echo htmlspecialchars($session['teacher_name']); ?>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    <?php echo date('Y-m-d H:i', strtotime($session['start_time'])); ?>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                <?php echo $session['status'] === 'scheduled' ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600'; ?>">
                                    <?php echo t($session['status'], ucfirst($session['status'])); ?>
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo $session['meeting_link']; ?>" target="_blank"
                                        class="p-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="?delete=<?php echo $session['id']; ?>&lang=<?php echo $lang; ?>"
                                        onclick="return confirm('Confirm delete?')"
                                        class="p-2 bg-rose-50 dark:bg-rose-900/30 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($sessions)): ?>
                        <tr>
                            <td colspan="5" class="py-20 text-center text-slate-400 font-bold">
                                <?php echo t('لا توجد حصص مجدولة حالياً', 'No scheduled sessions'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Session Modal -->
    <div x-show="modalOpen" x-cloak
        class="fixed inset-0 bg-slate-900/60 z-[60] flex items-center justify-center backdrop-blur-md p-4">
        <div @click.away="modalOpen = false"
            class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 w-full max-w-md shadow-2xl border border-slate-100 dark:border-slate-800">
            <h3 class="text-3xl font-black mb-8 text-slate-800 dark:text-white">
                <?php echo t('جدولة حصة جديدة', 'Schedule Session'); ?>
            </h3>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="create_session" value="1">

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('عنوان الحصة', 'Session Title'); ?>
                    </label>
                    <input type="text" name="title" required placeholder="مثال: مراجعة سورة البقرة"
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                            <?php echo t('الدورة', 'Course'); ?>
                        </label>
                        <select name="course_id"
                            class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                            <?php echo t('المعلم', 'Teacher'); ?>
                        </label>
                        <select name="teacher_id"
                            class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?php echo $teacher['id']; ?>">
                                    <?php echo htmlspecialchars($teacher['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('رابط الاجتماع (Zoom/Meet)', 'Meeting Link'); ?>
                    </label>
                    <input type="url" name="meeting_link" required placeholder="https://zoom.us/j/..."
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('وقت البدء', 'Start Time'); ?>
                    </label>
                    <input type="datetime-local" name="start_time" required
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex gap-4 mt-10">
                    <button type="button" @click="modalOpen = false"
                        class="flex-1 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest">
                        <?php echo t('إلغاء', 'Cancel'); ?>
                    </button>
                    <button type="submit"
                        class="flex-1 py-5 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-600/20">
                        <?php echo t('جدولة الآن', 'Schedule Now'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require '../includes/footer.php'; ?>