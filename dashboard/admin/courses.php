<?php
require '../includes/header.php';
require '../db.php';

// Handle course deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: courses.php?lang=$lang");
    exit;
}

// Handle course creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_course'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $teacher_id = $_POST['teacher_id'] ?: null;
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO courses (title, description, teacher_id, price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $teacher_id, $price]);
    header("Location: courses.php?lang=$lang");
    exit;
}

// Fetch all courses with teacher info
$courses = $pdo->query("
    SELECT c.*, u.name as teacher_name 
    FROM courses c 
    LEFT JOIN users u ON c.teacher_id = u.id 
    ORDER BY c.created_at DESC
")->fetchAll();

// Fetch teachers for dropdown
$teachers = $pdo->query("SELECT id, name FROM users WHERE role = 'teacher'")->fetchAll();
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-3xl font-black text-slate-800 dark:text-white">
            <?php echo t('إدارة الدورات', 'Courses Management'); ?>
        </h2>
        <p class="text-slate-500 text-sm mt-1">
            <?php echo t('إدارة جميع الدورات التعليمية', 'Manage all educational courses'); ?>
        </p>
    </div>
    <button onclick="document.getElementById('createCourseModal').classList.remove('hidden')"
        class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <?php echo t('دورة جديدة', 'New Course'); ?>
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 rounded-2xl text-white shadow-lg">
        <div class="text-sm font-bold opacity-90 mb-2">
            <?php echo t('إجمالي الدورات', 'Total Courses'); ?>
        </div>
        <div class="text-3xl font-black">
            <?php echo count($courses); ?>
        </div>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-2xl text-white shadow-lg">
        <div class="text-sm font-bold opacity-90 mb-2">
            <?php echo t('الدورات النشطة', 'Active Courses'); ?>
        </div>
        <div class="text-3xl font-black">
            <?php echo count(array_filter($courses, fn($c) => $c['status'] === 'active')); ?>
        </div>
    </div>
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-6 rounded-2xl text-white shadow-lg">
        <div class="text-sm font-bold opacity-90 mb-2">
            <?php echo t('إجمالي الإيرادات', 'Total Revenue'); ?>
        </div>
        <div class="text-3xl font-black">$
            <?php echo number_format(array_sum(array_column($courses, 'price')), 2); ?>
        </div>
    </div>
</div>

<!-- Courses Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($courses as $course): ?>
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden hover:shadow-lg transition-all hover:-translate-y-1 group">
            <div class="h-40 bg-gradient-to-br from-indigo-500 to-purple-600 relative overflow-hidden">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
            </div>

            <div class="p-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 line-clamp-2">
                    <?php echo htmlspecialchars($course['title']); ?>
                </h3>
                <p class="text-sm text-slate-500 mb-4 line-clamp-2">
                    <?php echo htmlspecialchars($course['description']); ?>
                </p>

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>
                            <?php echo $course['teacher_name'] ?: t('لا يوجد معلم', 'No teacher'); ?>
                        </span>
                    </div>
                    <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">$
                        <?php echo number_format($course['price'], 2); ?>
                    </span>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button
                        class="flex-1 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                        <?php echo t('تعديل', 'Edit'); ?>
                    </button>
                    <a href="?delete=<?php echo $course['id']; ?>&lang=<?php echo $lang; ?>"
                        onclick="return confirm('<?php echo t('هل أنت متأكد من حذف هذه الدورة؟', 'Are you sure you want to delete this course?'); ?>')"
                        class="flex-1 py-2 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-xs font-bold hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors text-center">
                        <?php echo t('حذف', 'Delete'); ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Add New Card -->
    <button onclick="document.getElementById('createCourseModal').classList.remove('hidden')"
        class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-indigo-400 dark:hover:border-indigo-500 transition-all h-full min-h-[300px] flex flex-col items-center justify-center gap-4 group">
        <div
            class="w-16 h-16 rounded-full bg-white dark:bg-slate-700 shadow-sm flex items-center justify-center text-slate-400 group-hover:text-indigo-500 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
        <span class="font-bold text-slate-500 group-hover:text-indigo-600 transition-colors">
            <?php echo t('إضافة دورة جديدة', 'Add New Course'); ?>
        </span>
    </button>
</div>

<!-- Create Course Modal -->
<div id="createCourseModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div
        class="bg-white dark:bg-slate-900 rounded-3xl p-8 w-full max-w-md shadow-2xl border border-slate-100 dark:border-slate-700">
        <h3 class="text-2xl font-bold mb-6 text-slate-800 dark:text-white">
            <?php echo t('إضافة دورة جديدة', 'Add New Course'); ?>
        </h3>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="create_course" value="1">

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('عنوان الدورة', 'Course Title'); ?>
                </label>
                <input type="text" name="title" required
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('الوصف', 'Description'); ?>
                </label>
                <textarea name="description" rows="3"
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('المعلم', 'Teacher'); ?>
                </label>
                <select name="teacher_id"
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">
                        <?php echo t('اختر معلم', 'Select Teacher'); ?>
                    </option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?php echo $teacher['id']; ?>">
                            <?php echo htmlspecialchars($teacher['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('السعر', 'Price'); ?>
                </label>
                <input type="number" name="price" step="0.01" value="0" required
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="flex gap-3 mt-8">
                <button type="button" onclick="document.getElementById('createCourseModal').classList.add('hidden')"
                    class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-xl font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <?php echo t('إلغاء', 'Cancel'); ?>
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-colors shadow-lg">
                    <?php echo t('إضافة', 'Add'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require '../includes/footer.php'; ?>