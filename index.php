<?php
/**
 * Ahl El-Sona Academy - Ultimate Hybrid Masterpiece (Fixed Dark/Light)
 * Features: Restored Premium Dark Mode, Elegant Light Mode, Dynamic Typography
 */

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'ar';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';

require 'dashboard/db.php';

function t($ar, $en)
{
    global $lang;
    return ($lang == 'ar') ? $ar : $en;
}

// Fetch Courses from DB
try {
    // Force seed if not 6 courses
    $count = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    if ($count != 6) {
        $pdo->exec("DELETE FROM courses");
        $default_courses = [
            ['title' => 'تحفيظ القرآن الكريم', 'description' => 'دورة شاملة لحفظ كتاب الله بالتلقي والمشافهة مع خطة فردية لكل طالب.', 'image' => 'assets/images/quran_course_img_1770121397437.png', 'category' => 'القرآن الكريم', 'badge' => 'الأكثر طلباً', 'duration' => 'مستمر', 'price' => '$49', 'features' => json_encode(['حفظ بالتلقي والمشافهة', 'تصحيح التلاوة بدقة', 'خطة حفظ فردية مستدامة', 'متابعة يومية مع الشيخ'])],
            ['title' => 'دورة نور البيان للأطفال', 'description' => 'تأسيس قوي للأطفال في قراءة اللغة العربية والقرآن الكريم بأساليب ممتعة.', 'image' => 'assets/images/noor_bayan_course_img_1770121413393.png', 'category' => 'اللغة العربية', 'badge' => 'للمبتدئين', 'duration' => '8 أسابيع', 'price' => '$29', 'features' => json_encode(['إتقان القراءة بالحركات', 'تمكين مخارج الحروف', 'تأسيس قوي في الكتابة', 'أساليب مشوقة للأطفال'])],
            ['title' => 'إتقان التجويد العملي', 'description' => 'شرح متون التجويد مع تطبيق عملي مكثف لتحسين الصوت والأداء.', 'image' => 'assets/images/tajweed_course_img_1770121430041.png', 'category' => 'التجويد', 'badge' => 'احترافي', 'duration' => '12 أسبوع', 'price' => '$59', 'features' => json_encode(['شرح نظري وتطبيق عملي', 'دراسة متون التجويد', 'تحسين الصوت والأداء', 'اختبارات دورية مكثفة'])],
            ['title' => 'أساسيات الدراسات الإسلامية', 'description' => 'تعلم العقيدة والفقه والأخلاق بأسلوب عصري يربط العلم بالعمل.', 'image' => 'assets/images/islamic_studies_course_img_1770121444719.png', 'category' => 'شرعي', 'badge' => 'شامل', 'duration' => '10 أسابيع', 'price' => '$39', 'features' => json_encode(['أساسيات العقيدة الصحيحة', 'فقه العبادات والمعاملات', 'شرح الأحاديث النبوية', 'تزكية النفس والأخلاق'])],
            ['title' => 'تفسير آيات الأحكام', 'description' => 'فهم مقاصد الآيات واستنباط الأحكام الشرعية بأسلوب ميسر.', 'image' => 'assets/images/tafsir_course_img_v2_1770121485567.png', 'category' => 'تفسير', 'badge' => 'مميز', 'duration' => '14 أسبوع', 'price' => '$69', 'features' => json_encode(['فهم مقاصد الآيات', 'استنباط الأحكام الشرعية', 'ربط التفسير بالواقع', 'تحليل لغوي وبياني'])],
            ['title' => 'اللغة العربية لغير الناطقين', 'description' => 'تعلم النحو والصرف وتطوير مهارات التحدث بطلاقة.', 'image' => 'assets/images/arabic_lang_course_img_1770121459816.png', 'category' => 'لغة', 'badge' => 'مكثف', 'duration' => '24 أسبوع', 'price' => '$129', 'features' => json_encode(['النحو والصرف بتبسيط', 'تنمية مهارات التحدث', 'فهم الأدب والبلاغة', 'إعداد لاختبارات الكفاءة'])]
        ];
        $is = $pdo->prepare("INSERT INTO courses (title, description, image, category, badge, duration, price, features) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($default_courses as $c)
            $is->execute(array_values($c));
    }

    $stmt = $pdo->query("SELECT * FROM courses WHERE status = 'active' ORDER BY created_at DESC");
    while ($row = $stmt->fetch()) {
        $row['features'] = json_decode($row['features'] ?? '[]', true) ?: [];
        $db_courses[] = $row;
    }
} catch (Exception $e) {
}

// Fallback to static data if DB is empty
$display_courses = !empty($db_courses) ? $db_courses : [
    [
        'title' => t('تحفيظ القرآن الكريم', 'Memorizing Quran'),
        'category' => t('القرآن', 'Quran'),
        'duration' => t('مستمر', 'Ongoing'),
        'price' => '$49',
        'image' => 'assets/images/quran_course_img_1770121397437.png',
        'badge' => t('الأكثر طلباً', 'Trending'),
        'features' => [
            t('حفظ بالتلقي والمشافهة', 'Traditional Memorization'),
            t('تصحيح التلاوة بدقة', 'Precise Recitation Correction'),
            t('خطة حفظ فردية مستدامة', 'Personalized Long-term Plan'),
            t('متابعة يومية مع الشيخ', 'Daily Teacher Follow-up')
        ]
    ],
    [
        'title' => t('دورة نور البيان', 'Noor Al-Bayan'),
        'category' => t('اللغة العربية', 'Arabic'),
        'duration' => t('8 أسابيع', '8 Weeks'),
        'price' => '$29',
        'image' => 'assets/images/noor_bayan_course_img_1770121413393.png',
        'badge' => t('للأطفال', 'Beginner'),
        'features' => [
            t('إتقان القراءة بالحركات', 'Mastering Voweled Reading'),
            t('تمكين مخارج الحروف', 'Correcting Articulation Points'),
            t('تأسيس قوي في الكتابة', 'Strong Foundation in Writing'),
            t('أساليب مشوقة للأطفال', 'Engaging Methods for Kids')
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>" x-data="{ 
        darkMode: localStorage.getItem('theme') !== 'light',
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        }
    }" :class="{ 'dark': darkMode, 'light': !darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo t('أكاديمية أهل السنة - منصة التعلم الفاخرة', 'Ahl El-Sona Academy - Luxury Learning'); ?>
    </title>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate_950: '#020617',
                        indigo_500: '#6366f1',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-20px)' } }
                    },
                    fontFamily: {
                        sans: [<?php echo ($lang == 'ar') ? "'IBM Plex Sans Arabic'" : "'Outfit'"; ?>, 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light');
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
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

        /* Essential Global Themes */
        .dark body {
            background-color: #020617;
            color: #cbd5e1;
        }

        .light body {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .gradient-text {
            background: linear-gradient(135deg, #818cf8, #f472b6, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        .dark ::-webkit-scrollbar-track {
            background: #020617;
        }

        .light ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 10px;
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden">

    <!-- Header -->
    <header class="fixed top-0 z-50 w-full px-6 py-4 glass transition-all duration-500"
        :class="darkMode ? 'bg-slate-950/80 backdrop-blur-xl border-b border-white/5' : 'bg-white/80 backdrop-blur-xl border-b border-black/5'">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl">
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
            </div>

            <nav
                class="hidden lg:flex items-center gap-10 text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">
                <a href="#why-us" class="hover:text-indigo-500 transition-colors">
                    <?php echo t('لماذا نحن', 'Why Us'); ?>
                </a>
                <a href="#about-us" class="hover:text-indigo-500 transition-colors">
                    <?php echo t('من نحن', 'About Us'); ?>
                </a>
                <a href="#how-it-works" class="hover:text-indigo-500 transition-colors">
                    <?php echo t('كيف نعمل', 'How It Works'); ?>
                </a>
                <a href="#courses" class="hover:text-indigo-500 transition-colors">
                    <?php echo t('الدورات', 'Courses'); ?>
                </a>
                <a href="#contact" class="hover:text-indigo-500 transition-colors">
                    <?php echo t('تواصل معنا', 'Contact'); ?>
                </a>
            </nav>

            <div class="flex items-center gap-4">
                <!-- Theme Toggler (The Magic Switch) -->
                <button @click="toggleTheme()"
                    class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all bg-indigo-600/10 text-indigo-500 hover:bg-indigo-600 hover:text-white border border-indigo-600/20">
                    <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </button>

                <a href="?lang=<?php echo ($lang == 'ar') ? 'en' : 'ar'; ?>"
                    class="text-[10px] font-black bg-white/5 px-5 py-2 rounded-xl border border-white/10 uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-600 border-black/5 bg-black/5'">
                    <?php echo ($lang == 'ar') ? 'EN' : 'AR'; ?>
                </a>
                <a href="login.php"
                    class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-black uppercase tracking-widest text-xs shadow-2xl hover:bg-indigo-700 transition-all">
                    <?php echo t('دخول', 'Login'); ?>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6">

        <!-- Hero Section -->
        <section class="relative pt-48 pb-32 grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
            <div class="relative z-10">
                <div
                    class="inline-flex items-center gap-3 bg-indigo-500/10 border border-indigo-500/20 px-5 py-2 rounded-full mb-10">
                    <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full animate-pulse"></span>
                    <span class="text-[11px] font-black uppercase tracking-[0.3em] text-indigo-400">
                        <?php echo t('نور العلم والهدى', 'Light of Knowledge'); ?>
                    </span>
                </div>
                <h1 class="text-6xl md:text-8xl font-black leading-none tracking-tighter mb-10"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <?php echo t('تعلم <span class="gradient-text">القرآن</span> <br>بمقاييس عالمية', 'Master <span class="gradient-text">Quran</span> <br>With Global Standards'); ?>
                </h1>
                <p class="text-xl md:text-2xl max-w-xl mb-14 leading-relaxed font-medium"
                    :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                    <?php echo t('نحن لا نقدم مجرد دورات، بل نصحبك في رحلة إيمانية وتعليمية متكاملة باستخدام أحدث وسائل التكنولوجيا الرقمية لخدمة كتاب الله.', 'We don\'t just provide courses, we take you on a spiritual journey.'); ?>
                </p>
                <div class="flex flex-wrap gap-8 items-center">
                    <a href="register.php"
                        class="bg-white text-slate-900 px-12 py-5 rounded-2xl font-black text-sm shadow-2xl hover:scale-105 transition-all uppercase tracking-widest flex items-center gap-3">
                        <?php echo t('ابدأ الآن مجاناً', 'Join for Free'); ?>
                    </a>
                    <a href="#about-us"
                        class="px-10 py-5 rounded-2xl font-black text-sm hover:translate-y-[-2px] transition-all uppercase tracking-widest border"
                        :class="darkMode ? 'bg-white/5 text-white border-white/10' : 'bg-black/5 text-slate-700 border-black/5'">
                        <?php echo t('تعرف علينا', 'Our Story'); ?>
                    </a>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute inset-0 bg-indigo-500/10 blur-[150px] animate-pulse"></div>
                <div class="rounded-[4rem] p-4 relative overflow-hidden animate-float backdrop-blur-3xl"
                    :class="darkMode ? 'bg-white/5 border border-white/10' : 'bg-black/5 border border-black/5'">
                    <img src="assets/images/hero_academy_modern.png"
                        class="w-full h-full object-cover rounded-[3.5rem] shadow-2xl opacity-90 group-hover:opacity-100 transition-opacity"
                        alt="Ahl El-Sona Academy Hero">
                </div>
            </div>
        </section>

        <!-- Why Us -->
        <section id="why-us" class="py-32">
            <div class="text-center mb-24">
                <h2 class="text-5xl font-black tracking-tighter mb-6 uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <?php echo t('لماذا أكاديمية <span class="text-indigo-500">أهل السنة</span>؟', 'Why <span class="text-indigo-500">Ahl El-Sona</span> Academy?'); ?>
                </h2>
                <div class="h-1 w-24 bg-indigo-600 mx-auto rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Features with Dynamic Theme Classes -->
                <div class="p-12 rounded-[3.5rem] text-center group transition-all duration-500 border backdrop-blur-xl"
                    :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-xl shadow-slate-200/50 hover:shadow-2xl'">
                    <div
                        class="w-20 h-20 bg-indigo-600/10 rounded-3xl flex items-center justify-center mx-auto mb-10 text-brand_primary group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black mb-6" :class="darkMode ? 'text-white' : 'text-slate-900'">
                        <?php echo t('سند متصل', 'Connected Sanad'); ?>
                    </h3>
                    <p class="font-medium leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                        <?php echo t('نحن نمنحك إجازة معتمدة بالسند المتصل إلى النبي ﷺ عبر نخبة من المشايخ المجازين.', 'Ijazah connected to Prophet Muhammad ﷺ through elite scholars.'); ?>
                    </p>
                </div>
                <!-- Box 2 -->
                <div class="p-12 rounded-[3.5rem] text-center group transition-all duration-500 border backdrop-blur-xl"
                    :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-xl shadow-slate-200/50 hover:shadow-2xl'">
                    <div
                        class="w-20 h-20 bg-indigo-600/10 rounded-3xl flex items-center justify-center mx-auto mb-10 text-brand_primary group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black mb-6" :class="darkMode ? 'text-white' : 'text-slate-900'">
                        <?php echo t('منهج فردي', 'Individual Path'); ?>
                    </h3>
                    <p class="font-medium leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                        <?php echo t('خطة تعليمية مخصصة لكل طالب تناسب عمره ومستواه وسرعته في الحفظ والتعلم.', 'Personalized plan for every student tailored to age and level.'); ?>
                    </p>
                </div>
                <!-- Box 3 -->
                <div class="p-12 rounded-[3.5rem] text-center group transition-all duration-500 border backdrop-blur-xl"
                    :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-xl shadow-slate-200/50 hover:shadow-2xl'">
                    <div
                        class="w-20 h-20 bg-indigo-600/10 rounded-3xl flex items-center justify-center mx-auto mb-10 text-brand_primary group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black mb-6" :class="darkMode ? 'text-white' : 'text-slate-900'">
                        <?php echo t('تقنية متطورة', 'Advanced Tech'); ?>
                    </h3>
                    <p class="font-medium leading-relaxed" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                        <?php echo t('نظام إلكتروني ذكي لمتابعة الواجبات، حضور الحلقات، والتفاعل المباشر مع المعلم.', 'Smart digital world to track progress and attend sessions.'); ?>
                    </p>
                </div>
            </div>
        </section>

        <!-- About Us -->
        <section id="about-us" class="py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
                <div class="relative group">
                    <div class="absolute inset-0 bg-indigo-500/10 blur-[100px]"></div>
                    <div class="rounded-[4rem] p-4 relative overflow-hidden animate-float backdrop-blur-3xl"
                        :class="darkMode ? 'bg-white/5 border border-white/10' : 'bg-white border-black/5 shadow-2xl'">
                        <img src="assets/images/about_us_academy.png"
                            class="w-full h-full object-cover rounded-[3.5rem] shadow-2xl opacity-90 group-hover:opacity-100 transition-opacity"
                            alt="Ahl El-Sona Academy">
                    </div>
                </div>
                <div>
                    <h2 class="text-5xl font-black tracking-tighter mb-10 uppercase"
                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                        <?php echo t('من هي أكاديمية <br><span class="text-indigo-500">أهل السنة</span>؟', 'Who is <br><span class="text-indigo-500">Ahl El-Sona</span> Academy?'); ?>
                    </h2>
                    <p class="text-xl leading-relaxed mb-12 font-medium"
                        :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                        <?php echo t('نحن مؤسسة تعليمية عالمية تهدف إلى نشر علوم القرآن الكريم والسنة النبوية واللغة العربية بمنهجية سلفنا الصالح، مستخدمين أرقى الوسائل التقنية الحديثة لدمج العلم في الحياة اليومية لكل مسلم.', 'Global world academy spreading Quran and Sunnah studies with the methodology of righteous ancestors.'); ?>
                    </p>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <div class="text-4xl font-black mb-2" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                +15,000</div>
                            <div class="text-xs font-black text-indigo-500 uppercase tracking-widest">
                                <?php echo t('طالب عالمي', 'Global Students'); ?>
                            </div>
                        </div>
                        <div>
                            <div class="text-4xl font-black mb-2" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                +250</div>
                            <div class="text-xs font-black text-indigo-500 uppercase tracking-widest">
                                <?php echo t('شيخ ومعلم', 'Elite Scholars'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courses -->
        <!-- How It Works -->
        <section id="how-it-works" class="py-32 relative">
            <div class="text-center mb-24">
                <h2 class="text-5xl font-black tracking-tighter mb-6 uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <?php echo t('كيف يبدأ <span class="text-indigo-500">مسارك</span>؟', 'How Your <span class="text-indigo-500">Journey</span> Begins?'); ?>
                </h2>
                <div class="h-1 w-24 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Connecting Line (Desktop) -->
                <div
                    class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-indigo-500/30 to-transparent -translate-y-1/2 z-0">
                </div>

                <!-- Step 1 -->
                <div class="relative z-10 group">
                    <div
                        class="bg-indigo-600 w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-black mx-auto mb-8 shadow-lg shadow-indigo-500/30 ring-8 ring-white/5 dark:ring-white/5 transition-transform group-hover:scale-110 group-hover:rotate-6">
                        1
                    </div>
                    <div class="p-8 rounded-[3rem] text-center border backdrop-blur-xl transition-all duration-500"
                        :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-xl hover:shadow-2xl'">
                        <h3 class="text-xl font-black mb-4" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <?php echo t('سجل مجاناً', 'Register Free'); ?>
                        </h3>
                        <p class="text-sm font-medium leading-relaxed"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('أنشئ حسابك في ثوانٍ وانضم لمجتمعنا القرآني العالمي.', 'Create your account in seconds and join our global community.'); ?>
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 group mt-12 md:mt-0">
                    <div
                        class="bg-indigo-600 w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-black mx-auto mb-8 shadow-lg shadow-indigo-500/30 ring-8 ring-white/5 dark:ring-white/5 transition-transform group-hover:scale-110 group-hover:-rotate-6">
                        2
                    </div>
                    <div class="p-8 rounded-[3rem] text-center border backdrop-blur-xl transition-all duration-500"
                        :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-xl hover:shadow-2xl'">
                        <h3 class="text-xl font-black mb-4" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <?php echo t('اختر مسارك', 'Choose Path'); ?>
                        </h3>
                        <p class="text-sm font-medium leading-relaxed"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('حدد البرنامج المناسب لك ولجدولك من بين برامجنا المتنوعة.', 'Select the best program for you and your schedule.'); ?>
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 group mt-12 md:mt-0">
                    <div
                        class="bg-indigo-600 w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-black mx-auto mb-8 shadow-lg shadow-indigo-500/30 ring-8 ring-white/5 dark:ring-white/5 transition-transform group-hover:scale-110 group-hover:rotate-6">
                        3
                    </div>
                    <div class="p-8 rounded-[3rem] text-center border backdrop-blur-xl transition-all duration-500"
                        :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-xl hover:shadow-2xl'">
                        <h3 class="text-xl font-black mb-4" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <?php echo t('ابدأ التعلم', 'Start Learning'); ?>
                        </h3>
                        <p class="text-sm font-medium leading-relaxed"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('التقِ بمعلمك في بث مباشر وابدأ رحلة حفظك.', 'Meet your teacher live and start your memorization journey.'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courses -->
        <section id="courses" class="py-32">
            <div class="text-center mb-24">
                <h2 class="text-5xl font-black tracking-tighter mb-6 uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <?php echo t('مسارات <span class="text-indigo-500">النخبة</span> المعرفية', 'Elite <span class="text-indigo-500">Science</span> Paths'); ?>
                </h2>
                <div class="h-1 w-24 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                <?php foreach ($display_courses as $course): ?>
                    <div class="rounded-[3.5rem] overflow-hidden group transition-all duration-500 flex flex-col h-full border backdrop-blur-xl"
                        :class="darkMode ? 'bg-white/5 border-white/5 hover:border-indigo-500/30' : 'bg-white border-black/5 shadow-xl hover:shadow-2xl shadow-slate-200/50'">
                        <div class="h-64 relative overflow-hidden shrink-0">
                            <img src="<?php echo $course['image']; ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            <?php if (!empty($course['badge'])): ?>
                                <div
                                    class="absolute top-6 left-6 bg-black/50 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-black text-white uppercase tracking-widest border border-white/10">
                                    <?php echo $course['badge']; ?>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t via-transparent to-transparent opacity-60"
                                :class="darkMode ? 'from-slate_950' : 'from-white/20'"></div>
                        </div>
                        <div class="p-10 flex flex-col flex-grow">
                            <span
                                class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-3 block shrink-0">
                                <?php echo $course['category'] ?: t('عام', 'General'); ?>
                            </span>
                            <h4 class="text-2xl font-black mb-6 group-hover:text-indigo-500 transition-colors shrink-0"
                                :class="darkMode ? 'text-white' : 'text-slate-900'">
                                <?php echo $course['title']; ?>
                            </h4>
                            <ul class="space-y-4 mb-8 flex-grow">
                                <?php foreach ($course['features'] as $feature): ?>
                                    <li class="flex items-start gap-3 text-sm font-medium"
                                        :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                        <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>
                                            <?php echo $feature; ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="flex items-center justify-between pt-8 border-t shrink-0"
                                :class="darkMode ? 'border-white/5' : 'border-black/5'">
                                <div class="text-xs font-black text-slate-500 uppercase tracking-widest">⏱️
                                    <?php echo $course['duration']; ?>
                                </div>
                                <div class="text-3xl font-black" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                    <?php echo $course['price']; ?>
                                </div>
                            </div>
                            <button
                                class="w-full mt-10 py-5 rounded-3xl transition-all font-black text-xs uppercase tracking-widest shadow-inner shrink-0"
                                :class="darkMode ? 'bg-white/5 border border-white/10 text-white hover:bg-indigo-600' : 'bg-slate-100 border border-black/5 text-slate-900 hover:bg-indigo-600 hover:text-white'">
                                <?php echo t('انضم إلينا الآن', 'Join Now'); ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-32">
            <div class="rounded-[4rem] p-16 relative overflow-hidden text-center transition-all duration-700 border"
                :class="darkMode ? 'bg-gradient-to-br from-indigo-900/20 to-slate-950 border-white/5' : 'bg-white border-black/5 shadow-2xl shadow-indigo-100'">
                <div class="max-w-3xl mx-auto z-10 relative">
                    <h2 class="text-5xl font-black tracking-tighter mb-8 uppercase leading-tight"
                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                        <?php echo t('هل أنت مستعد لبدء <br><span class="gradient-text">رحلتك الإيمانية</span> الآن؟', 'Ready to Start Your <br><span class="gradient-text">Faith Journey</span> Now?'); ?>
                    </h2>
                    <p class="text-lg mb-12 font-medium" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                        <?php echo t('نصف مليون متعلم بدأوا رحلتهم بالفعل، انضم إلينا اليوم واحصل على تقييم مجاني لمستواك في حفظ القرآن.', 'Join half a million learners today and get a free assessment.'); ?>
                    </p>
                    <div class="flex flex-wrap justify-center gap-6">
                        <a href="register.php"
                            class="bg-indigo-600 text-white px-12 py-5 rounded-2xl font-black text-sm shadow-2xl hover:scale-105 transition-all uppercase tracking-widest italic">
                            <?php echo t('سجل الآن مجاناً', 'Free Registration'); ?>
                        </a>
                        <button
                            class="px-10 py-5 rounded-2xl font-black text-sm transition-all uppercase tracking-widest border"
                            :class="darkMode ? 'bg-white/5 text-white border-white/10 hover:bg-white/10' : 'border-black/5 text-slate-700 hover:bg-black/5 shadow-sm'">
                            <?php echo t('تحدث مع مستشار', 'Talk to Advisor'); ?>
                        </button>
                    </div>
                </div>
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500/10 blur-[120px] rounded-full"></div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-32">
            <div class="text-center mb-24">
                <h2 class="text-5xl font-black tracking-tighter mb-6 uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <?php echo t('الأسئلة <span class="text-indigo-500">الشائعة</span>', 'Frequently Asked <span class="text-indigo-500">Questions</span>'); ?>
                </h2>
                <div class="h-1 w-24 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="max-w-4xl mx-auto space-y-6" x-data="{ active: null }">
                <!-- FAQ Item 1 -->
                <div class="rounded-3xl border transition-all duration-300 overflow-hidden"
                    :class="darkMode ? (active === 1 ? 'bg-white/10 border-indigo-500/50' : 'bg-white/5 border-white/5') : (active === 1 ? 'bg-indigo-50 border-indigo-200 shadow-lg' : 'bg-white border-black/5')">
                    <button @click="active = active === 1 ? null : 1"
                        class="w-full px-8 py-6 flex items-center justify-between text-left transition-all"
                        :class="active === 1 ? 'opacity-100' : 'opacity-80'">
                        <span class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <?php echo t('هل جميع المعلمين في الأكاديمية مجازون بالسند المتصل؟', 'Are all teachers certified with a connected Sanad?'); ?>
                        </span>
                        <svg class="w-6 h-6 transition-transform duration-300" :class="{'rotate-180': active === 1}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 1" x-collapse x-transition>
                        <div class="px-8 pb-6 text-sm font-medium leading-relaxed"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('نعم، جميع علمائنا ومشايخنا يمتلكون إجازات معتمدة ومتصلة بالسند إلى النبي ﷺ، ونضمن لك جودة التعليم والأمانة العلمية.', 'Yes, all our scholars hold certified Ijazahs connected back to Prophet Muhammad ﷺ.'); ?>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="rounded-3xl border transition-all duration-300 overflow-hidden"
                    :class="darkMode ? (active === 2 ? 'bg-white/10 border-indigo-500/50' : 'bg-white/5 border-white/5') : (active === 2 ? 'bg-indigo-50 border-indigo-200 shadow-lg' : 'bg-white border-black/5')">
                    <button @click="active = active === 2 ? null : 2"
                        class="w-full px-8 py-6 flex items-center justify-between text-left transition-all"
                        :class="active === 2 ? 'opacity-100' : 'opacity-80'">
                        <span class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <?php echo t('كيف يمكنني اختيار المواعيد المناسبة لي؟', 'How can I choose suitable timings?'); ?>
                        </span>
                        <svg class="w-6 h-6 transition-transform duration-300" :class="{'rotate-180': active === 2}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 2" x-collapse x-transition>
                        <div class="px-8 pb-6 text-sm font-medium leading-relaxed"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('توفر الأكاديمية نظاماً مرناً للغاية؛ حيث يمكنك التنسيق مباشرة مع المعلم لاختيار الأوقات التي تناسب جدولك اليومي.', 'We provide a highly flexible system where you can coordinate directly with your teacher.'); ?>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="rounded-3xl border transition-all duration-300 overflow-hidden"
                    :class="darkMode ? (active === 3 ? 'bg-white/10 border-indigo-500/50' : 'bg-white/5 border-white/5') : (active === 3 ? 'bg-indigo-50 border-indigo-200 shadow-lg' : 'bg-white border-black/5')">
                    <button @click="active = active === 3 ? null : 3"
                        class="w-full px-8 py-6 flex items-center justify-between text-left transition-all"
                        :class="active === 3 ? 'opacity-100' : 'opacity-80'">
                        <span class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <?php echo t('هل توجد مناهج مخصصة للأطفال؟', 'Are there specific curricula for children?'); ?>
                        </span>
                        <svg class="w-6 h-6 transition-transform duration-300" :class="{'rotate-180': active === 3}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 3" x-collapse x-transition>
                        <div class="px-8 pb-6 text-sm font-medium leading-relaxed"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('بالتأكيد، لدينا برامج تعليمية تفاعلية مشوقة مصممة خصيصاً للأطفال تراعي أعمارهم وتنمي حب تعلم القرآن لديهم.', 'Certainly! We have engaging interactive programs designed specifically for kids.'); ?>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="rounded-3xl border transition-all duration-300 overflow-hidden"
                    :class="darkMode ? (active === 4 ? 'bg-white/10 border-indigo-500/50' : 'bg-white/5 border-white/5') : (active === 4 ? 'bg-indigo-50 border-indigo-200 shadow-lg' : 'bg-white border-black/5')">
                    <button @click="active = active === 4 ? null : 4"
                        class="w-full px-8 py-6 flex items-center justify-between text-left transition-all"
                        :class="active === 4 ? 'opacity-100' : 'opacity-80'">
                        <span class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">
                            <?php echo t('كيف يمكنني البدء في الدورة والحصول على استشارة؟', 'How can I start and get a consultation?'); ?>
                        </span>
                        <svg class="w-6 h-6 transition-transform duration-300" :class="{'rotate-180': active === 4}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 4" x-collapse x-transition>
                        <div class="px-8 pb-6 text-sm font-medium leading-relaxed"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('ببساطة قم بالضغط على زر "سجل الآن مجاناً"، وسيتواصل معك أحد مستشارينا لتحديد موعد المقابلة التجريبية مجاناً.', 'Just click "Join for Free" and one of our advisors will contact you.'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Us -->
        <section id="contact" class="py-32 relative">
            <div class="text-center mb-20">
                <h2 class="text-5xl font-black tracking-tighter mb-6 uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <?php echo t('تواصل <span class="text-indigo-500">معنا</span>', '<span class="text-indigo-500">Get</span> in Touch'); ?>
                </h2>
                <div class="h-1 w-24 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                <!-- Contact Info Cards -->
                <div class="space-y-6">
                    <!-- Email Card -->
                    <div class="group p-8 rounded-[2.5rem] border transition-all duration-500 flex items-center gap-8"
                        :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-lg hover:shadow-xl'">
                        <div
                            class="w-16 h-16 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-2">
                                <?php echo t('البريد الإلكتروني', 'Email Address'); ?>
                            </div>
                            <div class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                support@elsona.academy
                            </div>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="group p-8 rounded-[2.5rem] border transition-all duration-500 flex items-center gap-8"
                        :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-lg hover:shadow-xl'">
                        <div
                            class="w-16 h-16 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-2">
                                <?php echo t('رقم الهاتف', 'Phone Number'); ?>
                            </div>
                            <div class="text-lg font-bold" dir="ltr"
                                :class="darkMode ? 'text-white' : 'text-slate-900'">
                                +971 4 123 4567
                            </div>
                        </div>
                    </div>

                    <!-- Location Card -->
                    <div class="group p-8 rounded-[2.5rem] border transition-all duration-500 flex items-center gap-8"
                        :class="darkMode ? 'bg-white/5 border-white/5 hover:bg-white/10' : 'bg-white border-black/5 shadow-lg hover:shadow-xl'">
                        <div
                            class="w-16 h-16 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-2">
                                <?php echo t('موقعنا', 'Location'); ?>
                            </div>
                            <div class="text-lg font-bold" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                <?php echo t('دبي، الإمارات العربية المتحدة', 'Dubai, UAE'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="p-10 lg:p-12 rounded-[3.5rem] border backdrop-blur-2xl transition-all duration-500 relative overflow-hidden"
                    :class="darkMode ? 'bg-white/5 border-white/10' : 'bg-white border-black/5 shadow-2xl'">

                    <!-- Decorative Circle -->
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/10 rounded-bl-[4rem] -mr-8 -mt-8 pointer-events-none">
                    </div>

                    <form class="space-y-6 relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest px-2"
                                    :class="darkMode ? 'text-slate-400' : 'text-slate-500'"><?php echo t('الاسم الكامل', 'Full Name'); ?></label>
                                <input type="text"
                                    class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold"
                                    :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'"
                                    placeholder="<?php echo t('الاسم هنا..', 'Name here..'); ?>">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest px-2"
                                    :class="darkMode ? 'text-slate-400' : 'text-slate-500'"><?php echo t('البريد الإلكتروني', 'Email Address'); ?></label>
                                <input type="email"
                                    class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold"
                                    :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'"
                                    placeholder="example@mail.com">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest px-2"
                                :class="darkMode ? 'text-slate-400' : 'text-slate-500'"><?php echo t('رسالتك', 'Your Message'); ?></label>
                            <textarea rows="5"
                                class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold resize-none"
                                :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'"
                                placeholder="<?php echo t('اكتب استفسارك هنا..', 'Write your inquiry here..'); ?>"></textarea>
                        </div>

                        <button
                            class="w-full group bg-indigo-600 text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl hover:bg-indigo-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                            <span><?php echo t('إرسال الرسالة', 'Send Message'); ?></span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform rtl:rotate-180"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </section>
        </div>
        </section>
    </main>

    <!-- Final Footer -->
    <footer class="pt-16 pb-12 border-t transition-all duration-500"
        :class="darkMode ? 'bg-slate_950 border-white/5' : 'bg-white border-black/5'">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <!-- Logo & Brand -->
            <div class="mb-8">
                <div
                    class="w-16 h-16 bg-indigo-600 rounded-[2rem] flex items-center justify-center shadow-xl mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.232.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <h2 class="text-4xl font-black tracking-[0.2em] uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-900'">
                    <?php echo t('أكاديمية أهل السنة', 'AHL ELSONA ACADEMY'); ?>
                </h2>
                <p class="max-w-2xl mx-auto mt-6 text-sm font-medium leading-relaxed"
                    :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    <?php echo t('نحن لا نقدم مجرد دورات، بل نصحبك في رحلة إيمانية وتعليمية متكاملة باستخدام أحدث وسائل التكنولوجيا الرقمية لخدمة كتاب الله.', 'We don\'t just offer courses; we accompany you on a complete faith and educational journey using the latest digital technology to serve the Book of Allah.'); ?>
                </p>
                <div
                    class="inline-flex items-center gap-3 bg-indigo-500/10 border border-indigo-500/20 px-5 py-2 rounded-full mt-8">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400">
                        <?php echo t('نور العلم والهدى', 'Light of Knowledge'); ?>
                    </span>
                </div>
            </div>

            <!-- Social Media Icons -->
            <div class="flex justify-center gap-6 mb-8 flex-wrap">
                <!-- Facebook -->
                <a href="#" class="w-12 h-12 rounded-2xl flex items-center justify-center border transition-all"
                    :class="darkMode ? 'bg-white/5 border-white/10 text-white hover:bg-indigo-600' : 'bg-black/5 border-black/5 text-slate-500 hover:bg-indigo-600 hover:text-white'">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M18.77,7.46H14.5v-1.9c0-.9.6-1.1,1-1.1h3V.5h-4.33C10.24.5,9.5,3.44,9.5,5.32v2.14h-3v4h3v12h5v-12h3.85l.42-4Z" />
                    </svg>
                </a>
                <!-- Instagram -->
                <a href="#" class="w-12 h-12 rounded-2xl flex items-center justify-center border transition-all"
                    :class="darkMode ? 'bg-white/5 border-white/10 text-white hover:bg-indigo-600' : 'bg-black/5 border-black/5 text-slate-500 hover:bg-indigo-600 hover:text-white'">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12,2.16c3.2,0,3.58.01,4.85.07,3.25.15,4.77,1.69,4.92,4.92.05,1.27.07,1.65.07,4.85s-.01,3.58-.07,4.85c-.15,3.23-1.66,4.77-4.92,4.92-1.27.05-1.65.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92-.05-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.15-3.23,1.66-4.77,4.92-4.92,1.27-.05,1.65-.07,4.85-.07m0-2C8.74,0,8.33.02,7.05.08,2.7.27.27,2.7.08,7.05.02,8.33,0,8.74,0,12s.02,3.67.08,4.95c.19,4.35,2.62,6.78,6.97,6.97,1.28.06,1.69.08,4.95.08s3.67-.02,4.95-.08c4.35-.19,6.78-2.62,6.97-6.97.06-1.28.08-1.69.08-4.95s-.02-3.67-.08-4.95c-.19-4.35-2.62-6.78-6.97-6.97C15.67.02,15.26,0,12,0Z" />
                        <path
                            d="M12,5.84A6.16,6.16,0,1,0,18.16,12,6.16,6.16,0,0,0,12,5.84Zm0,10.16A4,4,0,1,1,16,12,4,4,0,0,1,12,16Z" />
                        <circle cx="18.41" cy="5.59" r="1.44" />
                    </svg>
                </a>
                <!-- WhatsApp -->
                <a href="#" class="w-12 h-12 rounded-2xl flex items-center justify-center border transition-all"
                    :class="darkMode ? 'bg-white/5 border-white/10 text-white hover:bg-indigo-600' : 'bg-black/5 border-black/5 text-slate-500 hover:bg-indigo-600 hover:text-white'">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12.04,2c-5.46,0-9.91,4.45-9.91,9.91,0,1.75,0.46,3.45,1.32,4.95L2.05,22l5.25-1.38c1.45,0.79,3.08,1.21,4.74,1.21 c5.46,0,9.91-4.45,9.91-9.91C21.95,6.45,17.5,2,12.04,2z M17.59,16.27c-0.24,0.67-1.38,1.24-1.9,1.32c-0.47,0.08-1.09,0.14-1.72-0.05 c-0.39-0.12-0.89-0.28-1.52-0.56c-2.68-1.18-4.41-3.92-4.55-4.1c-0.13-0.18-1.08-1.44-1.08-2.75c0-1.31,0.68-1.96,0.92-2.22 c0.24-0.26,0.51-0.33,0.68-0.33c0.17,0,0.34,0,0.49,0.01c0.16,0.01,0.37-0.06,0.57,0.42c0.21,0.5,0.73,1.76,0.79,1.89 c0.07,0.13,0.11,0.28,0.02,0.45c-0.09,0.18-0.14,0.31-0.28,0.47c-0.14,0.17-0.29,0.38-0.42,0.51c-0.14,0.15-0.3,0.32-0.13,0.6 c0.17,0.28,0.75,1.23,1.6,1.98c1.1,0.98,2.03,1.28,2.32,1.42c0.29,0.14,0.46,0.12,0.63-0.08c0.17-0.19,0.73-0.85,0.93-1.13 c0.2-0.28,0.39-0.24,0.66-0.14c0.27,0.1,1.71,0.81,2.01,0.96c0.3,0.15,0.5,0.22,0.57,0.35C17.9,15.11,17.83,15.6,17.59,16.27z" />
                    </svg>
                </a>
            </div>

            <!-- Contact Details -->
            <div class="space-y-4 mb-8 text-sm font-bold uppercase tracking-[0.2em]"
                :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                <div class="flex flex-wrap justify-center gap-x-12 gap-y-8">
                    <!-- Email -->
                    <div class="flex items-center gap-4 group">
                        <div
                            class="bg-indigo-600 w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 ring-4 ring-white/5 transition-transform group-hover:scale-110 group-hover:rotate-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="lowercase">support@elsona.academy</span>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-center gap-4 group">
                        <div
                            class="bg-indigo-600 w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 ring-4 ring-white/5 transition-transform group-hover:scale-110 group-hover:-rotate-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <span dir="ltr">+971 4 123 4567</span>
                    </div>

                    <!-- Location -->
                    <div class="flex items-center gap-4 group">
                        <div
                            class="bg-indigo-600 w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 ring-4 ring-white/5 transition-transform group-hover:scale-110 group-hover:rotate-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span><?php echo t('دبي، الإمارات العربية المتحدة', 'Dubai, UAE'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Rights & Branding Footer -->
            <div class="pt-8 border-t text-[10px] font-black uppercase tracking-[0.3em]"
                :class="darkMode ? 'border-white/5 text-slate-600' : 'border-black/5 text-slate-300'">
                &copy; <?php echo date('Y'); ?>
                <?php echo t('أكاديمية أهل السنة - جميع الحقوق محفوظة', 'Ahl El-Sona Academy - All Rights Reserved'); ?>.
            </div>
        </div>
    </footer>
</body>

</html>