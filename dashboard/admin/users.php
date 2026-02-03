<?php
require '../includes/header.php';
require '../db.php';

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
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
?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-3xl font-black text-slate-800 dark:text-white">
            <?php echo t('إدارة المستخدمين', 'Users Management'); ?>
        </h2>
        <p class="text-slate-500 text-sm mt-1">
            <?php echo t('إدارة جميع مستخدمي النظام', 'Manage all system users'); ?>
        </p>
    </div>
    <button onclick="document.getElementById('createUserModal').classList.remove('hidden')"
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
        <div class="text-sm font-bold opacity-90 mb-2">
            <?php echo t('إجمالي المستخدمين', 'Total Users'); ?>
        </div>
        <div class="text-3xl font-black">
            <?php echo count($users); ?>
        </div>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-2xl text-white shadow-lg">
        <div class="text-sm font-bold opacity-90 mb-2">
            <?php echo t('المعلمين', 'Teachers'); ?>
        </div>
        <div class="text-3xl font-black">
            <?php echo count(array_filter($users, fn($u) => $u['role'] === 'teacher')); ?>
        </div>
    </div>
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-6 rounded-2xl text-white shadow-lg">
        <div class="text-sm font-bold opacity-90 mb-2">
            <?php echo t('الطلاب', 'Students'); ?>
        </div>
        <div class="text-3xl font-black">
            <?php echo count(array_filter($users, fn($u) => $u['role'] === 'student')); ?>
        </div>
    </div>
    <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-6 rounded-2xl text-white shadow-lg">
        <div class="text-sm font-bold opacity-90 mb-2">
            <?php echo t('المدراء', 'Admins'); ?>
        </div>
        <div class="text-3xl font-black">
            <?php echo count(array_filter($users, fn($u) => $u['role'] === 'admin')); ?>
        </div>
    </div>
</div>

<!-- Users Table -->
<div
    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <?php echo t('المستخدم', 'User'); ?>
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <?php echo t('البريد الإلكتروني', 'Email'); ?>
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <?php echo t('الدور', 'Role'); ?>
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <?php echo t('تاريخ الإنضمام', 'Joined'); ?>
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <?php echo t('الإجراءات', 'Actions'); ?>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                                    <?php echo strtoupper(substr($user['name'], 0, 2)); ?>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white">
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase
                            <?php
                            echo $user['role'] === 'admin' ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400' :
                                ($user['role'] === 'teacher' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' :
                                    'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300');
                            ?>">
                                <?php echo t($user['role'] === 'admin' ? 'مدير' : ($user['role'] === 'teacher' ? 'معلم' : 'مستخدم'), ucfirst($user['role'])); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-sm">
                            <?php echo date('Y-m-d', strtotime($user['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button
                                    class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-slate-400 hover:text-indigo-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </button>
                                <a href="?delete=<?php echo $user['id']; ?>&lang=<?php echo $lang; ?>"
                                    onclick="return confirm('<?php echo t('هل أنت متأكد من حذف هذا المستخدم؟', 'Are you sure you want to delete this user?'); ?>')"
                                    class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-slate-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div
        class="bg-white dark:bg-slate-900 rounded-3xl p-8 w-full max-w-md shadow-2xl border border-slate-100 dark:border-slate-700">
        <h3 class="text-2xl font-bold mb-6 text-slate-800 dark:text-white">
            <?php echo t('إضافة مستخدم جديد', 'Add New User'); ?>
        </h3>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="create_user" value="1">

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('الاسم', 'Name'); ?>
                </label>
                <input type="text" name="name" required
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('البريد الإلكتروني', 'Email'); ?>
                </label>
                <input type="email" name="email" required
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('كلمة المرور', 'Password'); ?>
                </label>
                <input type="password" name="password" required
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">
                    <?php echo t('الدور', 'Role'); ?>
                </label>
                <select name="role"
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="user">
                        <?php echo t('مستخدم', 'User'); ?>
                    </option>
                    <option value="teacher">
                        <?php echo t('معلم', 'Teacher'); ?>
                    </option>
                    <option value="student">
                        <?php echo t('طالب', 'Student'); ?>
                    </option>
                    <option value="admin">
                        <?php echo t('مدير', 'Admin'); ?>
                    </option>
                </select>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="button" onclick="document.getElementById('createUserModal').classList.add('hidden')"
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