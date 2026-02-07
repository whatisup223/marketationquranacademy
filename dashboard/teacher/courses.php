<?php
require '../includes/header.php';
require '../db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Fetch courses taught by this teacher
// Assuming teacher_id exists in courses table. If not, we'll just show all for now as demo.
$stmt = $pdo->prepare("SELECT * FROM courses"); // In production: WHERE teacher_id = ?
$stmt->execute();
$teacher_courses = $stmt->fetchAll();
?>

<div class="mb-12">
    <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-2">
        <?php echo t('دوراتي التعليمية', 'My Teaching Courses'); ?>
    </h2>
    <p class="text-slate-500 font-medium">
        <?php echo t('إدارة المناهج ومتابعة طلابك في كل دورة', 'Manage curriculum and track your students in each course'); ?>
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-right">
    <?php foreach ($teacher_courses as $course): ?>
        <div
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden group">
            <div class="h-48 bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                <img src="<?php echo htmlspecialchars($course['image'] ?? 'https://images.unsplash.com/photo-1590073844006-33379778ae09?q=80&w=600&auto=format&fit=crop'); ?>"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-x-6 bottom-6 flex justify-between items-center">
                    <span
                        class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-[10px] font-black text-white uppercase tracking-widest border border-white/20">
                        <?php echo t('نشط', 'Active'); ?>
                    </span>
                </div>
            </div>
            <div class="p-8">
                <h4 class="text-xl font-black text-slate-800 dark:text-white mb-3">
                    <?php echo htmlspecialchars($course['title']); ?>
                </h4>
                <div class="flex items-center gap-4 mb-6 text-slate-500 font-bold text-xs">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        12
                        <?php echo t('طالب', 'Students'); ?>
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        24
                        <?php echo t('ساعة', 'Hours'); ?>
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button
                        class="py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 transition-all">
                        <?php echo t('إدارة الطلاب', 'Students'); ?>
                    </button>
                    <button
                        class="py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-gray-300 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                        <?php echo t('المحتوى', 'Content'); ?>
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require '../includes/footer.php'; ?>