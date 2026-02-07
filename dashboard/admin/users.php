<?php
require '../includes/header.php';
require '../db.php';

// Handle user actions
if (isset($_GET['action']) && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->execute([$id]);
    } elseif ($action === 'perm_ban') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'permanently_banned' WHERE id = ? AND role != 'admin'");
        $stmt->execute([$id]);
    } elseif ($action === 'temp_ban') {
        // Simple temp ban sets status, could be enhanced with an expiry date
        $stmt = $pdo->prepare("UPDATE users SET status = 'temporarily_banned' WHERE id = ? AND role != 'admin'");
        $stmt->execute([$id]);
    } elseif ($action === 'activate') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->execute([$id]);
    }

    header("Location: users.php?lang=$lang");
    exit;
}

// Handle user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $role]);
    header("Location: users.php?lang=$lang");
    exit;
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
$users_json = json_encode($users);
?>

<div x-data="{ 
    modalOpen: false,
    detailModalOpen: false,
    selectedUser: {},
    allUsers: <?php echo htmlspecialchars($users_json, ENT_QUOTES, 'UTF-8'); ?>,
    showDetails(id) {
        this.selectedUser = this.allUsers.find(u => u.id == id);
        this.detailModalOpen = true;
    }
}">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white">
                <?php echo t('إدارة المستخدمين', 'Users Management'); ?>
            </h2>
            <p class="text-slate-500 text-sm mt-1">
                <?php echo t('إدارة صلاحيات وحالات مستخدمي النظام', 'Manage permissions and statuses of system users'); ?>
            </p>
        </div>
        <button @click="modalOpen = true"
            class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <?php echo t('مستخدم جديد', 'New User'); ?>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 rounded-2xl text-white shadow-lg">
            <div class="text-xs font-black opacity-80 uppercase mb-2 tracking-widest">
                <?php echo t('إجمالي المستخدمين', 'Total Users'); ?>
            </div>
            <div class="text-3xl font-black">
                <?php echo count($users); ?>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-2xl text-white shadow-lg text-center">
            <div class="text-xs font-black opacity-80 uppercase mb-2 tracking-widest">
                <?php echo t('النشطين', 'Active'); ?>
            </div>
            <div class="text-3xl font-black">
                <?php echo count(array_filter($users, fn($u) => $u['status'] === 'active')); ?>
            </div>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-6 rounded-2xl text-white shadow-lg text-center">
            <div class="text-xs font-black opacity-80 uppercase mb-2 tracking-widest">
                <?php echo t('حظر مؤقت', 'Temp Ban'); ?>
            </div>
            <div class="text-3xl font-black">
                <?php echo count(array_filter($users, fn($u) => $u['status'] === 'temporarily_banned')); ?>
            </div>
        </div>
        <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-6 rounded-2xl text-white shadow-lg text-center">
            <div class="text-xs font-black opacity-80 uppercase mb-2 tracking-widest">
                <?php echo t('حظر دائم', 'Perm Ban'); ?>
            </div>
            <div class="text-3xl font-black">
                <?php echo count(array_filter($users, fn($u) => $u['status'] === 'permanently_banned')); ?>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div
        class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('المستخدم', 'User'); ?>
                        </th>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('الحالة', 'Status'); ?>
                        </th>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('الدور', 'Role'); ?>
                        </th>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <?php echo t('الإجراءات', 'Actions'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-lg border border-indigo-100/50 dark:border-indigo-500/20 shadow-sm">
                                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div
                                            class="font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 transition-colors">
                                            <?php echo htmlspecialchars($user['name']); ?>
                                        </div>
                                        <div class="text-xs text-slate-400 font-medium">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <?php if ($user['status'] === 'active'): ?>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <?php echo t('نشط', 'Active'); ?>
                                    </span>
                                <?php elseif ($user['status'] === 'temporarily_banned'): ?>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider">
                                        <?php echo t('حظر مؤقت', 'Temp Ban'); ?>
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-[10px] font-black uppercase tracking-wider">
                                        <?php echo t('حظر دائم', 'Perm Ban'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-[10px] font-black px-3 py-1.5 rounded-lg border
                            <?php
                            echo $user['role'] === 'admin' ? 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-500/30 dark:bg-rose-900/20 dark:text-rose-400' :
                                ($user['role'] === 'teacher' ? 'border-indigo-200 bg-indigo-50 text-indigo-600 dark:border-indigo-500/30 dark:bg-indigo-900/20 dark:text-indigo-400' :
                                    'border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-400');
                            ?> uppercase tracking-widest">
                                    <?php echo t($user['role'] === 'admin' ? 'مدير' : ($user['role'] === 'teacher' ? 'معلم' : 'مستخدم'), ucfirst($user['role'])); ?>
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <button @click="showDetails(<?php echo $user['id']; ?>)"
                                        class="p-2.5 bg-slate-50 dark:bg-slate-700/50 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-all border border-transparent hover:border-indigo-100 dark:hover:border-indigo-500/30 shadow-sm"
                                        title="<?php echo t('التفاصيل', 'Details'); ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>

                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="p-2.5 bg-slate-50 dark:bg-slate-700/50 text-slate-400 hover:text-amber-600 rounded-xl transition-all border border-transparent hover:border-amber-100 shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                class="absolute left-0 mt-2 w-48 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 z-50 p-2 overflow-hidden">
                                                <a href="?action=activate&id=<?php echo $user['id']; ?>&lang=<?php echo $lang; ?>"
                                                    class="block px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:text-emerald-600 rounded-xl transition-colors">
                                                    ✅ <?php echo t('تفعيل الحساب', 'Activate Account'); ?>
                                                </a>
                                                <a href="?action=temp_ban&id=<?php echo $user['id']; ?>&lang=<?php echo $lang; ?>"
                                                    class="block px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 rounded-xl transition-colors">
                                                    ⏳ <?php echo t('حظر مؤقت', 'Temporary Ban'); ?>
                                                </a>
                                                <a href="?action=perm_ban&id=<?php echo $user['id']; ?>&lang=<?php echo $lang; ?>"
                                                    class="block px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 hover:text-rose-600 rounded-xl transition-colors">
                                                    🚫 <?php echo t('حظر دائم', 'Permanent Ban'); ?>
                                                </a>
                                                <div class="h-px bg-slate-100 dark:bg-slate-800 my-1"></div>
                                                <a href="?action=delete&id=<?php echo $user['id']; ?>&lang=<?php echo $lang; ?>"
                                                    onclick="return confirm('<?php echo t('هل أنت متأكد من الحذف النهائي؟', 'Confirm final deletion?'); ?>')"
                                                    class="block px-4 py-2.5 text-xs font-black text-rose-600 hover:bg-rose-100 dark:hover:bg-rose-900/50 rounded-xl transition-colors">
                                                    🗑️ <?php echo t('حذف نهائي', 'Delete User'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed View Modal -->
    <div x-show="detailModalOpen" x-cloak
        class="fixed inset-0 bg-slate-900/60 z-[60] flex items-center justify-center backdrop-blur-md p-4">
        <div @click.away="detailModalOpen = false"
            class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 w-full max-w-lg shadow-2xl border border-slate-100 dark:border-slate-800 max-h-[90vh] overflow-y-auto custom-scrollbar relative">

            <button @click="detailModalOpen = false"
                class="absolute top-8 left-8 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <div class="text-center mb-8">
                <div class="w-24 h-24 rounded-[2rem] bg-indigo-600 text-white flex items-center justify-center text-4xl font-black mx-auto mb-4 shadow-xl shadow-indigo-600/20"
                    x-text="selectedUser.name ? selectedUser.name.substring(0,1).toUpperCase() : ''"></div>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white" x-text="selectedUser.name"></h3>
                <p class="text-slate-500 font-bold" x-text="selectedUser.email"></p>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                            <?php echo t('الدور', 'Role'); ?></div>
                        <div class="font-black text-slate-800 dark:text-white uppercase" x-text="selectedUser.role">
                        </div>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                            <?php echo t('الحالة', 'Status'); ?></div>
                        <div class="font-black"
                            :class="selectedUser.status === 'active' ? 'text-emerald-500' : 'text-rose-500'"
                            x-text="selectedUser.status"></div>
                    </div>
                </div>

                <div
                    class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                        <?php echo t('رقم الهاتف', 'Phone Number'); ?></div>
                    <div class="font-bold text-slate-800 dark:text-white"
                        x-text="selectedUser.phone || '<?php echo t('غير متوفر', 'Not set'); ?>'"></div>
                </div>

                <div
                    class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                        <?php echo t('تاريخ الإنضمام', 'Joined At'); ?></div>
                    <div class="font-bold text-slate-800 dark:text-white" x-text="selectedUser.created_at"></div>
                </div>

                <div
                    class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                        <?php echo t('السيرة الذاتية', 'Bio'); ?></div>
                    <div class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed"
                        x-text="selectedUser.bio || '<?php echo t('لا توجد سيرة ذاتية مكتوبة', 'No bio provided'); ?>'">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div x-show="modalOpen" x-cloak
        class="fixed inset-0 bg-slate-900/60 z-[60] flex items-center justify-center backdrop-blur-md p-4">
        <div @click.away="modalOpen = false"
            class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 w-full max-w-md shadow-2xl border border-slate-100 dark:border-slate-800">
            <h3 class="text-3xl font-black mb-8 text-slate-800 dark:text-white">
                <?php echo t('إضافة مستخدم جديد', 'Add New User'); ?>
            </h3>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="create_user" value="1">

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('الاسم الكامل', 'Full Name'); ?>
                    </label>
                    <input type="text" name="name" required
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('البريد الإلكتروني', 'Email'); ?>
                    </label>
                    <input type="email" name="email" required
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('كلمة المرور', 'Password'); ?>
                    </label>
                    <input type="password" name="password" required
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        <?php echo t('الدور الوظيفي', 'Role'); ?>
                    </label>
                    <select name="role"
                        class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="user"><?php echo t('مستخدم', 'User'); ?></option>
                        <option value="teacher"><?php echo t('معلم', 'Teacher'); ?></option>
                        <option value="student"><?php echo t('طالب', 'Student'); ?></option>
                        <option value="admin"><?php echo t('مدير', 'Admin'); ?></option>
                    </select>
                </div>

                <div class="flex gap-4 mt-10">
                    <button type="button" @click="modalOpen = false"
                        class="flex-1 py-5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-750 transition-all">
                        <?php echo t('إلغاء', 'Cancel'); ?>
                    </button>
                    <button type="submit"
                        class="flex-1 py-5 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                        <?php echo t('إضافة الآن', 'Add Now'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div><!-- End x-data -->

<?php require '../includes/footer.php'; ?>