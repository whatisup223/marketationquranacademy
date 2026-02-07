<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in (optional, can be commented out for dev)
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    // header("Location: ../../login.php");
    // exit;
}

$user_name = $_SESSION['user_name'] ?? 'Admin User';
$user_role = $_SESSION['user_role'] ?? 'admin';

// Language & Translation Helper
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'ar';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';

if (!function_exists('t')) {
    function t($ar, $en)
    {
        global $lang;
        return ($lang == 'ar') ? $ar : $en;
    }
}

// Page Title
$page_title = isset($page_title) ? $page_title : t('لوحة التحكم', 'Dashboard');
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo t('أهل السنة', 'ELSONA'); ?></title>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: {
                            850: '#151e2e',
                            950: '#020617',
                        },
                        indigo: {
                            450: '#6366f1'
                        }
                    },
                    fontFamily: {
                        sans: [<?php echo ($lang == 'ar') ? "'IBM Plex Sans Arabic'" : "'Outfit'"; ?>, 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>

    <!-- Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;900&display=swap');

        * {
            font-family:
                <?php echo ($lang == 'ar') ? "'IBM Plex Sans Arabic', sans-serif" : "'Outfit', sans-serif"; ?>
                !important;
        }

        body {
            font-family:
                <?php echo ($lang == 'ar') ? "'IBM Plex Sans Arabic', sans-serif" : "'Outfit', sans-serif"; ?>
                !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300" x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark',
          sidebarOpen: false,
          sidebarCompact: localStorage.getItem('sidebarCompact') === 'true'
      }" :class="{ 'dark': darkMode }"
    x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light')); $watch('sidebarCompact', val => localStorage.setItem('sidebarCompact', val))">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Include -->
        <?php include dirname(__FILE__) . '/sidebar.php'; ?>

        <!-- Main Content Wrapper -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <!-- Top Header -->
            <header
                class="sticky top-0 z-30 bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-800">
                <div class="px-6 py-4 flex items-center justify-between">

                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Button -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Desktop Sidebar Toggle -->
                        <button @click="sidebarCompact = !sidebarCompact"
                            class="hidden lg:block text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                        </button>

                        <!-- Left Side (Search or Breadcrumbs) -->
                        <div class="hidden sm:flex items-center gap-4">
                            <h2
                                class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300">
                                <?php echo t('أهلاً بك،', 'Welcome back,'); ?>
                                <?php echo htmlspecialchars($user_name); ?>
                                👋
                            </h2>
                        </div>
                    </div>

                    <!-- Right Side (Actions) -->
                    <div class="flex items-center gap-4">


                        <!-- Language Toggle -->
                        <a href="?lang=<?php echo ($lang == 'ar' ? 'en' : 'ar'); ?>"
                            class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-indigo-100 hover:text-indigo-600 transition-colors font-bold text-xs shadow-sm">
                            <?php echo ($lang == 'ar' ? 'EN' : 'AR'); ?>
                        </a>

                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode"
                            class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-indigo-100 hover:text-indigo-600 transition-colors shadow-sm focus:outline-none">
                            <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                </path>
                            </svg>
                            <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </button>

                        <!-- Profile Dropdown (Simplified) -->
                        <div
                            class="flex items-center gap-3 pl-3 border-l border-slate-200 dark:border-slate-700 <?php echo ($dir == 'rtl' ? 'border-r pr-3 border-l-0 pl-0' : ''); ?>">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user_name); ?>&background=6366f1&color=fff"
                                alt="User"
                                class="w-8 h-8 rounded-full shadow-md ring-2 ring-white dark:ring-slate-800 cursor-pointer hover:ring-indigo-500 transition-all">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="w-full flex-grow p-6">
                <!-- Content injected here -->