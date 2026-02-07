<?php
require '../includes/header.php';
require '../db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Fetch enrolled courses
$stmt = $pdo->prepare("
    SELECT c.* 
    FROM courses c
    JOIN enrollments e ON c.id = e.course_id
    WHERE e.user_id = ?
");
$stmt->execute([$user_id]);
$enrolled_courses = $stmt->fetchAll();

// Fetch available courses (not enrolled)
$stmt = $pdo->prepare("
    SELECT * FROM courses 
    WHERE id NOT IN (SELECT course_id FROM enrollments WHERE user_id = ?)
");
$stmt->execute([$user_id]);
$available_courses = $stmt->fetchAll();
?>

<div class="mb-12">
    <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-2">
        <?php echo t('مساري التعليمي', 'My Learning Path'); ?>
    </h2>
    <p class="text-slate-500 font-medium">
        <?php echo t('إدارة دوراتك ومتابعة تقدمك الدراسي', 'Manage your courses and track your academic progress'); ?>
    </p>
</div>

<!-- Enrolled Courses -->
<section class="mb-16">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
        <h3 class="text-xl font-black text-slate-800 dark:text-gray-200 uppercase tracking-tight">
            <?php echo t('الدورات الحالية', 'Current Courses'); ?>
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($enrolled_courses as $course): ?>
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden group">
                <div class="h-48 bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                    <img src="<?php echo htmlspecialchars($course['image'] ?? 'https://images.unsplash.com/photo-1585036156171-3839efc2296c?q=80&w=600&auto=format&fit=crop'); ?>"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <span
                            class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-[10px] font-black text-white uppercase tracking-widest border border-white/20">
                            <?php echo t('ملتحق', 'Enrolled'); ?>
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <h4 class="text-xl font-black text-slate-800 dark:text-white mb-3">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </h4>
                    <p class="text-slate-500 text-sm line-clamp-2 mb-6 font-medium">
                        <?php echo htmlspecialchars($course['description']); ?>
                    </p>
                    <a href="course-view.php?id=<?php echo $course['id']; ?>&lang=<?php echo $lang; ?>"
                        class="block w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs text-center uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/10">
                        <?php echo t('دخول الدورة', 'Enter Course'); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($enrolled_courses)): ?>
            <div
                class="col-span-full py-16 bg-slate-50 dark:bg-slate-850 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800 text-center">
                <div
                    class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <p class="text-slate-400 font-black uppercase tracking-widest text-xs italic">
                    <?php echo t('أنت غير ملتحق بأي دورة حالياً', 'You are not enrolled in any courses yet'); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Available Courses -->
<section>
    <div class="flex items-center gap-3 mb-8">
        <div class="w-2 h-8 bg-emerald-500 rounded-full"></div>
        <h3 class="text-xl font-black text-slate-800 dark:text-gray-200 uppercase tracking-tight">
            <?php echo t('دورات مقترحة لك', 'Recommended Courses'); ?>
        </h3>
    </div>

    <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-right <?php echo ($lang == 'ar') ? 'rtl' : 'ltr'; ?>">
        <?php foreach ($available_courses as $course): ?>
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden group">
                <div class="h-48 bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                    <img src="<?php echo htmlspecialchars($course['image'] ?? 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?q=80&w=600&auto=format&fit=crop'); ?>"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-6 <?php echo ($lang == 'ar' ? 'left-6' : 'right-6'); ?>">
                        <span
                            class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/30">
                            $
                            <?php
                            // Clean price from any non-numeric characters (like '$') before formatting
                            $clean_price = preg_replace('/[^0-9.]/', '', $course['price']);
                            echo number_format((float) $clean_price, 2);
                            ?>
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <h4 class="text-xl font-black text-slate-800 dark:text-white mb-3">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </h4>
                    <p class="text-slate-500 text-sm line-clamp-2 mb-6 font-medium">
                        <?php echo htmlspecialchars($course['description']); ?>
                    </p>
                    <button
                        class="w-full py-4 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all">
                        <?php echo t('اشترك الآن', 'Enroll Now'); ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require '../includes/footer.php'; ?>