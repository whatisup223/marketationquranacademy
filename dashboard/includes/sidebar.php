<?php
// dashboard/includes/sidebar.php

// Ensure we don't have redeclaration errors if included multiple times
if (!isset($menu_items)) {
    // Current Page Logic for Active Menu
    $current_page = basename($_SERVER['PHP_SELF']);

    // Define menus based on role
    // Role-based directory context
    $role_dir = '';
    if ($user_role === 'admin')
        $role_dir = 'admin/';
    elseif ($user_role === 'teacher')
        $role_dir = 'teacher/';
    else
        $role_dir = 'student/';

    // Define menus based on role
    $menu_items = [
        [
            'label' => t('الرئيسية', 'Dashboard'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
            'link' => 'index.php'
        ]
    ];

    if ($user_role === 'admin') {
        $menu_items[] = [
            'label' => t('المستخدمين', 'Users'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
            'link' => 'users.php'
        ];
        $menu_items[] = [
            'label' => t('الدورات', 'Courses'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
            'link' => 'courses.php'
        ];
        $menu_items[] = [
            'label' => t('الحصص المباشرة', 'Live Sessions'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>',
            'link' => 'sessions.php'
        ];
        $menu_items[] = [
            'label' => t('الإدارة المالية', 'Payments'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            'link' => 'payments.php'
        ];
        $menu_items[] = [
            'label' => t('الإعدادات', 'Settings'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
            'link' => 'settings.php'
        ];
    } elseif ($user_role === 'teacher') {
        $menu_items[] = [
            'label' => t('دوراتي', 'My Courses'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
            'link' => 'courses.php'
        ];
        $menu_items[] = [
            'label' => t('حصصي المباشرة', 'My Sessions'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>',
            'link' => 'sessions.php'
        ];
    } else { // Student / User
        $menu_items[] = [
            'label' => t('دوراتي الملتحق بها', 'My Learning'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
            'link' => 'learning.php'
        ];
        $menu_items[] = [
            'label' => t('جدول حصصي', 'My Schedule'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
            'link' => 'schedule.php'
        ];
        $menu_items[] = [
            'label' => t('سجل المدفوعات', 'Billing'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            'link' => 'payments.php'
        ];
    }

    $menu_items[] = [
        'label' => t('تسجيل الخروج', 'Logout'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>',
        'link' => '../../logout.php',
        'danger' => true
    ];
}
?>

<!-- Sidebar Backdrop (Mobile) -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
    class="fixed inset-0 bg-black/50 z-30 lg:hidden backdrop-blur-sm"></div>

<!-- Sidebar -->
<aside :class="[
            sidebarOpen ? 'translate-x-0' : (dir === 'rtl' ? 'translate-x-full' : '-translate-x-full'),
            sidebarCompact ? 'lg:w-20' : 'lg:w-72'
       class=" fixed top-0 bottom-0 z-40 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-all
    duration-300 lg:translate-x-0 lg:static lg:block shadow-2xl lg:shadow-none <?php echo ($dir == 'rtl') ? 'right-0 border-l' : 'left-0 border-r'; ?>">

    <!-- Logo -->
    <div class="h-20 flex items-center justify-center border-b border-gray-100 dark:border-slate-800 px-4 overflow-hidden relative"
        :class="sidebarCompact ? 'px-2' : 'px-6'">
        <a href="../../index.php" class="flex items-center gap-3 w-full justify-center transition-all duration-300">
            <div class="bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/30 transition-all duration-300 shrink-0"
                :class="sidebarCompact ? 'w-10 h-10' : 'w-10 h-10'">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>

            <span
                class="text-xl font-black tracking-wider uppercase text-slate-800 dark:text-white transition-all duration-300 whitespace-nowrap"
                :class="sidebarCompact ? 'w-0 opacity-0 overflow-hidden' : 'w-auto opacity-100'">
                <?php echo t('أهل السنة', 'ELSONA'); ?>
            </span>
        </a>
    </div>

    <!-- Menu -->
    <nav class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-5rem)]">
        <?php foreach ($menu_items as $item): ?>
            <?php
            $link = (isset($item['danger']) && $item['danger']) ? $item['link'] : '../' . $role_dir . $item['link'];
            // If we are already in that directory, we need to adjust relative path
            // But wait, the current setup is: dashboard/admin/index.php
            // Header is in dashboard/includes/header.php
            // sidebar is in dashboard/includes/sidebar.php
            // So '../' . $role_dir . item['link'] from context of admin/index.php 
            // would be '../admin/index.php' - Correct.
            ?>
            <a href="<?php echo $link; ?>?lang=<?php echo $lang; ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group font-bold text-sm
                      <?php
                      if (isset($item['danger']) && $item['danger']) {
                          echo 'text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 mt-8';
                      } elseif ($item['link'] == $current_page) {
                          echo 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30';
                      } else {
                          echo 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-white';
                      }
                      ?>" :class="sidebarCompact ? 'justify-center px-0' : ''"
                :title="sidebarCompact ? '<?php echo htmlspecialchars($item['label']); ?>' : ''">

                <span
                    class="<?php echo ($item['link'] == $current_page) ? 'text-white' : (isset($item['danger']) ? 'text-red-500' : 'text-slate-400 group-hover:text-indigo-500'); ?>">
                    <?php echo $item['icon']; ?>
                </span>

                <span :class="sidebarCompact ? 'hidden' : 'block'">
                    <?php echo $item['label']; ?>
                </span>

                <?php if ($item['link'] == $current_page): ?>
                    <span class="mr-auto w-1.5 h-1.5 rounded-full bg-white opacity-50"
                        :class="sidebarCompact ? 'hidden' : 'block'"></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>