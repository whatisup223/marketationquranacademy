<?php
// dashboard/admin/index.php
require '../includes/header.php';
require '../db.php';

// Fetch real stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$active_sessions = $pdo->query("SELECT COUNT(*) FROM sessions WHERE status = 'scheduled'")->fetchColumn();
$new_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student' AND created_at > date('now', '-7 days')")->fetchColumn();

// Fetch recent users
$recent_users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card -->
    <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:-translate-y-1 transition-all">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 dark:bg-indigo-500/10 rounded-full group-hover:scale-110 transition-transform">
        </div>
        <div class="relative z-10">
            <div class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">
                <?php echo t('إجمالي المستخدمين', 'Total Users'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">
                <?php echo number_format($total_users); ?></div>
            <div class="text-[10px] font-bold text-indigo-500 flex items-center gap-1">
                <span><?php echo t('نمو مستمر', 'Steady Growth'); ?></span>
            </div>
        </div>
    </div>

    <!-- Stat Card -->
    <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:-translate-y-1 transition-all">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-full group-hover:scale-110 transition-transform">
        </div>
        <div class="relative z-10">
            <div class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">
                <?php echo t('الدورات المتاحة', 'Available Courses'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?php echo $total_courses; ?></div>
            <div class="text-[10px] font-bold text-emerald-500">
                <span><?php echo t('مسارات تعليمية كاملة', 'Full Programs'); ?></span>
            </div>
        </div>
    </div>

    <!-- Stat Card -->
    <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:-translate-y-1 transition-all">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 dark:bg-amber-500/10 rounded-full group-hover:scale-110 transition-transform">
        </div>
        <div class="relative z-10">
            <div class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">
                <?php echo t('الحصص المجدولة', 'Scheduled Sessions'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?php echo $active_sessions; ?></div>
            <div class="text-[10px] font-bold text-amber-500">
                <span><?php echo t('بث مباشر قادم', 'Live Soon'); ?></span>
            </div>
        </div>
    </div>

    <!-- Stat Card -->
    <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:-translate-y-1 transition-all">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 dark:bg-rose-500/10 rounded-full group-hover:scale-110 transition-transform">
        </div>
        <div class="relative z-10">
            <div class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">
                <?php echo t('طلاب جدد', 'New Students'); ?>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1"><?php echo $new_students; ?></div>
            <div class="text-[10px] font-bold text-rose-500">
                <span><?php echo t('آخر 7 أيام', 'Last 7 Days'); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div
        class="lg:col-span-2 bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-black text-xl text-slate-800 dark:text-white">
                <?php echo t('أحدث المسجلين', 'Recent Registrations'); ?>
            </h3>
            <a href="users.php?lang=<?php echo $lang; ?>"
                class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:underline">
                <?php echo t('عرض الكل', 'View All'); ?>
            </a>
        </div>

        <div class="space-y-4">
            <?php foreach ($recent_users as $user): ?>
                <div
                    class="flex items-center gap-4 p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 rounded-3xl transition-all group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-black text-slate-600 dark:text-slate-300 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-black text-sm text-slate-800 dark:text-white">
                            <?php echo htmlspecialchars($user['name']); ?></h4>
                        <p class="text-xs text-slate-500 font-bold"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <span
                        class="text-[10px] font-black px-3 py-1 rounded-lg border border-slate-100 dark:border-slate-700 uppercase tracking-widest text-slate-400">
                        <?php echo t($user['role'], ucfirst($user['role'])); ?>
                    </span>
                </div>
            <?php endforeach; ?>

            <?php if (empty($recent_users)): ?>
                <div class="text-center py-12 text-slate-400 font-bold">
                    <?php echo t('لا يوجد مسجلين جدد حالياً', 'No recent registrations'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="space-y-6">
        <div
            class="bg-indigo-600 p-8 rounded-[2.5rem] text-white relative overflow-hidden shadow-xl shadow-indigo-600/20">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-black text-xl mb-4 leading-tight">
                    <?php echo t('تحكم كامل في الأكاديمية', 'Full Control of Academy'); ?> 🎯
                </h3>
                <p class="text-sm text-indigo-100 mb-8 font-medium opacity-80 leading-relaxed">
                    <?php echo t('يمكنك إدارة الدورات، الطلاب، والمعلمين وجدولة الحصص المباشرة من لوحة واحدة.', 'Manage courses, students, teachers and schedule live sessions from one place.'); ?>
                </p>
                <a href="courses.php?lang=<?php echo $lang; ?>"
                    class="block w-full py-4 bg-white text-indigo-600 rounded-2xl font-black text-xs text-center uppercase tracking-widest hover:bg-indigo-50 transition-all shadow-lg hover:shadow-indigo-500/10">
                    <?php echo t('إدارة الدورات', 'Manage Courses'); ?>
                </a>
            </div>
            <!-- Decorative circles -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-indigo-900/40 rounded-full blur-3xl"></div>
        </div>

        <div
            class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50">
            <h4 class="font-black text-slate-800 dark:text-white text-sm mb-6 uppercase tracking-widest">
                <?php echo t('نصيحة إدارية', 'Management Tip'); ?> 💡
            </h4>
            <div
                class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-500/20 rounded-2xl">
                <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed font-bold">
                    <?php echo t('تأكد من مراجعة الحصص المجدولة يومياً وتحديث حالة المعلمين لضمان جودة التعليم.', 'Review scheduled sessions daily and update teacher statuses to ensure quality.'); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>