<?php
require 'dashboard/db.php';

session_start();

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'ar';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';

function t($ar, $en)
{
    global $lang;
    return ($lang == 'ar') ? $ar : $en;
}

$message = '';
$message_type = ''; // 'success' or 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!empty($email)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // In a real app, you'd send an email with a token.
            // For this demo/academy, we will simulate the success message.
            $message = t('تم إرسال تعليمات استعادة كلمة المرور إلى بريدك الإلكتروني', 'Password reset instructions have been sent to your email');
            $message_type = 'success';
        } else {
            $message = t('هذا البريد الإلكتروني غير مسجل لدينا', 'This email address is not registered');
            $message_type = 'error';
        }
    } else {
        $message = t('يرجى إدخال البريد الإلكتروني', 'Please enter your email');
        $message_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo t('استعادة كلمة المرور - أكاديمية أهل السنة', 'Reset Password - Ahl El-Sona Academy'); ?>
    </title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
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
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    },
                    fontFamily: {
                        sans: [<?php echo ($lang == 'ar') ? "'IBM Plex Sans Arabic'" : "'Outfit'"; ?>, 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;600;700&family=Outfit:wght@300;400;600;900&display=swap');

        * {
            font-family:
                <?php echo ($lang == 'ar') ? "'IBM Plex Sans Arabic', sans-serif" : "'Outfit', sans-serif"; ?>
                !important;
        }

        body {
            font-family:
                <?php echo ($lang == 'ar') ? "'IBM Plex Sans Arabic', sans-serif" : "'Outfit', sans-serif"; ?>
                !important;
            scroll-behavior: smooth;
            transition: background-color 0.5s ease, color 0.5s ease;
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden min-h-screen flex flex-col relative"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
    :class="darkMode ? 'dark bg-slate-950 text-slate-200' : 'bg-slate-50 text-slate-900'"
    x-init="if (!localStorage.getItem('theme')) localStorage.setItem('theme', 'dark'); $watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">

    <!-- Background Elements -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-[120px] -mr-40 -mt-40 animate-pulse-slow">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-500/20 rounded-full blur-[120px] -ml-40 -mb-40 animate-pulse-slow">
        </div>
    </div>

    <!-- Header -->
    <header class="absolute top-0 w-full z-50 p-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-4 group">
                <div
                    class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl transition-transform group-hover:scale-110">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <span class="text-2xl font-black tracking-widest uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <?php echo t('أهل السنة', 'ELSONA'); ?>
                </span>
            </a>

            <div class="flex items-center gap-4">
                <button @click="darkMode = !darkMode"
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-all bg-indigo-600/10 text-indigo-500 hover:bg-indigo-600 hover:text-white border border-indigo-600/20">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </button>
                <a href="?lang=<?php echo ($lang == 'ar' ? 'en' : 'ar'); ?>"
                    class="text-[10px] font-black bg-white/5 px-4 py-2 rounded-xl border border-white/10 uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-600 border-black/5 bg-black/5'">
                    <?php echo ($lang == 'ar' ? 'EN' : 'AR'); ?>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center relative z-10 px-6 py-24">
        <div class="w-full max-w-lg">
            <div class="p-10 rounded-[3rem] border backdrop-blur-2xl transition-all duration-500 relative shadow-2xl"
                :class="darkMode ? 'bg-slate-900/60 border-white/10' : 'bg-white/80 border-black/5'">

                <div class="text-center mb-10">
                    <h1 class="text-3xl font-black uppercase tracking-tighter mb-2"
                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                        <?php echo t('استعادة الحساب', 'Reset Access'); ?>
                    </h1>
                    <p class="text-sm font-medium" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                        <?php echo t('أدخل بريدك الإلكتروني وسنرسل لك رابط الاستعادة', 'Enter your email to receive a reset link'); ?>
                    </p>
                </div>

                <?php if ($message): ?>
                    <div
                        class="mb-6 p-4 rounded-2xl text-sm font-bold text-center <?php echo $message_type === 'success' ? 'bg-green-500/10 border border-green-500/20 text-green-500' : 'bg-red-500/10 border border-red-500/20 text-red-500'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest px-2"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('البريد الإلكتروني', 'Email Address'); ?>
                        </label>
                        <input type="email" name="email" required
                            class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold"
                            :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'"
                            placeholder="example@mail.com">
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl hover:bg-indigo-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                        <span><?php echo t('إرسال رابط الاستعادة', 'Send Reset Link'); ?></span>
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <div class="mt-8 text-center text-sm font-bold" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    <a href="login.php"
                        class="text-indigo-500 hover:text-indigo-400 underline decoration-2 underline-offset-4 uppercase tracking-widest text-[10px] font-black">
                        <?php echo t('العودة لتسجيل الدخول', 'Back to Login'); ?>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="p-6 text-center text-[10px] font-black uppercase tracking-widest opacity-40">
        &copy; <?php echo date('Y'); ?>
        <?php echo t('أكاديمية أهل السنة - جميع الحقوق محفوظة', 'Ahl El-Sona Academy - All Rights Reserved'); ?>
    </footer>

</body>

</html>