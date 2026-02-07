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

// Function to handle image upload
function handleUpload($file)
{
    if (!empty($file['name'])) {
        $target_dir = "../../assets/images/uploads/";
        if (!file_exists($target_dir))
            mkdir($target_dir, 0777, true);

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return "assets/images/uploads/" . $filename;
        }
    }
    return null;
}

// One-time update for existing courses with missing images
$pdo->exec("UPDATE courses SET image = 'assets/images/quran_course_img_1770121397437.png' WHERE image IS NULL OR image = '' OR image NOT LIKE 'assets/images/%'");

// Handle course creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_course'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $teacher_id = $_POST['teacher_id'] ?: null;
    $price = $_POST['price'];

    // Handle Upload
    $uploaded_image = handleUpload($_FILES['image_file']);

    // Default images rotation
    $default_images = [
        'assets/images/quran_course_img_1770121397437.png',
        'assets/images/noor_bayan_course_img_1770121413393.png',
        'assets/images/tajweed_course_img_1770121430041.png',
        'assets/images/islamic_studies_course_img_1770121444719.png',
        'assets/images/arabic_lang_course_img_1770121459816.png',
        'assets/images/tafsir_course_img_v2_1770121485567.png'
    ];
    $random_img = $default_images[array_rand($default_images)];

    $image = $uploaded_image ?: $random_img;
    $category = $_POST['category'] ?: '';
    $badge = $_POST['badge'] ?: '';
    $duration = $_POST['duration'] ?: '';
    $features = $_POST['features'] ? json_encode(array_filter(array_map('trim', explode("\n", $_POST['features'])))) : '[]';

    $stmt = $pdo->prepare("INSERT INTO courses (title, description, teacher_id, price, image, category, badge, duration, features) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $teacher_id, $price, $image, $category, $badge, $duration, $features]);
    header("Location: courses.php?lang=" . $lang);
    exit;
}

// Handle course update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_course'])) {
    $id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $teacher_id = $_POST['teacher_id'] ?: null;
    $price = $_POST['price'];

    // Handle Upload
    $uploaded_image = handleUpload($_FILES['image_file']);
    $image = $uploaded_image ?: $_POST['image'];

    $category = $_POST['category'];
    $badge = $_POST['badge'];
    $duration = $_POST['duration'];
    $features = $_POST['features'] ? json_encode(array_filter(array_map('trim', explode("\n", $_POST['features'])))) : '[]';

    $stmt = $pdo->prepare("UPDATE courses SET title=?, description=?, teacher_id=?, price=?, image=?, category=?, badge=?, duration=?, features=? WHERE id=?");
    $stmt->execute([$title, $description, $teacher_id, $price, $image, $category, $badge, $duration, $features, $id]);
    header("Location: courses.php?lang=" . $lang);
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

// Prepare courses data for JS
$courses_json = json_encode(array_map(function ($c) {
    $c['features_text'] = implode("\n", json_decode($c['features'] ?? '[]', true) ?: []);
    return $c;
}, $courses));
?>

<div x-data="{ 
    modalOpen: false, 
    isEdit: false, 
    courseData: { id: '', title: '', description: '', teacher_id: '', price: '$49', image: '', category: '', badge: '', duration: '', features_text: '' },
    allCourses: <?php echo htmlspecialchars($courses_json, ENT_QUOTES, 'UTF-8'); ?>,
    openCreate() {
        this.isEdit = false;
        this.courseData = { id: '', title: '', description: '', teacher_id: '', price: '$49', image: '', category: '', badge: '', duration: '', features_text: '' };
        this.modalOpen = true;
    },
    openEdit(id) {
        this.isEdit = true;
        const course = this.allCourses.find(c => c.id == id);
        if (course) {
            this.courseData = { ...course };
            this.modalOpen = true;
        }
    }
}">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white">
                <?php echo t('إدارة الدورات', 'Courses Management'); ?>
            </h2>
            <p class="text-slate-500 text-sm mt-1">
                <?php echo t('إدارة جميع الدورات التعليمية', 'Manage all educational courses'); ?>
            </p>
        </div>
        <button @click="openCreate()"
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
                <div class="h-40 relative overflow-hidden">
                    <img src="<?php echo $course['image'] ?: 'assets/images/quran_course_img_1770121397437.png'; ?>"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <?php if (!empty($course['badge'])): ?>
                        <div
                            class="absolute top-4 left-4 bg-indigo-600 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-lg border border-white/20">
                            <?php echo $course['badge']; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-6">
                    <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">
                        <?php echo $course['category'] ?: t('عام', 'General'); ?>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 line-clamp-1">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </h3>
                    <p class="text-sm text-slate-500 mb-4 line-clamp-2 min-h-[40px]">
                        <?php echo htmlspecialchars($course['description']); ?>
                    </p>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-bold">
                                <?php echo $course['teacher_name'] ?: t('لا يوجد معلم', 'No teacher'); ?>
                            </span>
                        </div>
                        <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">
                            <?php echo $course['price']; ?>
                        </span>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button @click="openEdit(<?php echo $course['id']; ?>)"
                            class="flex-1 py-2.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            <?php echo t('تعديل', 'Edit'); ?>
                        </button>
                        <a href="?delete=<?php echo $course['id']; ?>&lang=<?php echo $lang; ?>"
                            onclick="return confirm('<?php echo t('هل أنت متأكد من حذف هذه الدورة؟', 'Are you sure you want to delete this course?'); ?>')"
                            class="flex-1 py-2.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl text-xs font-bold hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors text-center flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            <?php echo t('حذف', 'Delete'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Add New Card -->
        <button @click="openCreate()"
            class="bg-slate-50 dark:bg-slate-800/50 rounded-[2.5rem] border-4 border-dashed border-slate-200 dark:border-slate-800 hover:border-indigo-400 dark:hover:border-indigo-500 transition-all h-full min-h-[350px] flex flex-col items-center justify-center gap-4 group">
            <div
                class="w-20 h-20 rounded-[2rem] bg-white dark:bg-slate-700 shadow-xl flex items-center justify-center text-slate-400 group-hover:text-indigo-500 group-hover:scale-110 transition-all duration-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <span
                class="font-black text-slate-500 group-hover:text-indigo-600 transition-colors uppercase tracking-widest text-sm">
                <?php echo t('إضافة دورة جديدة', 'Add New Course'); ?>
            </span>
        </button>
    </div>

    <!-- Course Modal (Create/Edit) -->
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center backdrop-blur-md p-4" x-cloak>

        <div @click.away="modalOpen = false" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 w-full max-w-lg shadow-2xl border border-slate-100 dark:border-slate-800 max-h-[90vh] overflow-y-auto custom-scrollbar">

            <div class="flex items-center justify-between mb-8">
                <h3 class="text-3xl font-black text-slate-800 dark:text-white"
                    x-text="isEdit ? '<?php echo t('تعديل الدورة', 'Edit Course'); ?>' : '<?php echo t('إضافة دورة جديدة', 'Add New Course'); ?>'">
                </h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="course_id" x-model="courseData.id">
                <input type="hidden" :name="isEdit ? 'update_course' : 'create_course'" value="1">
                <input type="hidden" name="image" x-model="courseData.image">

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('عنوان الدورة', 'Course Title'); ?>
                    </label>
                    <input type="text" name="title" x-model="courseData.title" required
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('الوصف المختصر', 'Short Description'); ?>
                    </label>
                    <textarea name="description" rows="3" x-model="courseData.description"
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                            <?php echo t('المعلم (اختياري)', 'Teacher (Optional)'); ?>
                        </label>
                        <select name="teacher_id" x-model="courseData.teacher_id"
                            class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="">
                                <?php echo t('بدون معلم حالياً', 'No teacher for now'); ?>
                            </option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?php echo $teacher['id']; ?>">
                                    <?php echo htmlspecialchars($teacher['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                            <?php echo t('الحالة (مثل: جديد)', 'Badge (e.g. New)'); ?>
                        </label>
                        <input type="text" name="badge" x-model="courseData.badge" placeholder="مثلاً: الأكثر طلباً"
                            class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                            <?php echo t('التصنيف', 'Category'); ?>
                        </label>
                        <input type="text" name="category" x-model="courseData.category"
                            placeholder="مثلاً: القرآن الكريم"
                            class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                            <?php echo t('مدة الدورة', 'Duration'); ?>
                        </label>
                        <input type="text" name="duration" x-model="courseData.duration" placeholder="مثلاً: 12 أسبوع"
                            class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('صورة الدورة (رفع محلي)', 'Course Image (Upload)'); ?>
                    </label>
                    <div class="flex items-center gap-4">
                        <div
                            class="relative group w-24 h-24 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0">
                            <template x-if="courseData.image">
                                <img :src="'../../' + courseData.image" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!courseData.image">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </template>
                        </div>
                        <input type="file" name="image_file" accept="image/*"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('مميزات المسار (ميزة في كل سطر)', 'Course Features (one per line)'); ?>
                    </label>
                    <textarea name="features" rows="5" x-model="courseData.features_text"
                        placeholder="مثلاً:&#10;حفظ بإتقان&#10;متابعة يومية"
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('السعر', 'Price'); ?>
                    </label>
                    <input type="text" name="price" x-model="courseData.price" required
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="flex gap-4 mt-10">
                    <button type="button" @click="modalOpen = false"
                        class="flex-1 py-5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-750 transition-all">
                        <?php echo t('إلغاء', 'Cancel'); ?>
                    </button>
                    <button type="submit"
                        class="flex-1 py-5 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20"
                        x-text="isEdit ? '<?php echo t('تحديث البيانات', 'Update Course'); ?>' : '<?php echo t('إضافة المسار', 'Add Course'); ?>'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><!-- End x-data -->

<?php require '../includes/footer.php'; ?>