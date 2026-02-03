<?php
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'ar';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';

function t($ar, $en)
{
    global $lang;
    return ($lang == 'ar') ? $ar : $en;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo t('إنشاء حساب - أكاديمية أهل السنة', 'Register - Ahl El-Sona Academy'); ?>
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
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;600;700&family=Outfit:wght@300;400;600;900&display=swap');

        body {
            font-family:
                <?php echo ($lang == 'ar') ? "'IBM Plex Sans Arabic', sans-serif" : "'Outfit', sans-serif"; ?>
            ;
            scroll-behavior: smooth;
            transition: background-color 0.5s ease, color 0.5s ease;
        }
    </style>
    <!-- Intl Tel Input CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

    <style>
        .iti {
            width: 100%;
        }

        .iti__dropdown-content {
            background-color: white;
            color: black;
        }

        .dark .iti__dropdown-content {
            background-color: #1e293b;
            color: white;
            border-color: #334155;
        }

        .iti__country-list {
            z-index: 50;
        }

        /* CustomScroll for dropdowns */
        select option {
            background-color: white;
            color: black;
        }

        .dark select option {
            background-color: #020617;
            color: white;
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden min-h-screen flex flex-col relative"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark', role: 'student' }"
    :class="darkMode ? 'dark bg-slate-950 text-slate-200' : 'bg-slate-50 text-slate-900'"
    x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))">

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
                <!-- Theme Toggler -->
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

                <a href="?lang=<?php echo ($lang == 'ar') ? 'en' : 'ar'; ?>"
                    class="text-[10px] font-black bg-white/5 px-4 py-2 rounded-xl border border-white/10 uppercase"
                    :class="darkMode ? 'text-white' : 'text-slate-600 border-black/5 bg-black/5'">
                    <?php echo ($lang == 'ar') ? 'EN' : 'AR'; ?>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center relative z-10 px-6 py-24">
        <div class="w-full max-w-2xl">
            <div class="p-10 rounded-[3rem] border backdrop-blur-2xl transition-all duration-500 relative shadow-2xl"
                :class="darkMode ? 'bg-slate-900/60 border-white/10' : 'bg-white/80 border-black/5'">

                <div class="text-center mb-10">
                    <h1 class="text-3xl font-black uppercase tracking-tighter mb-2"
                        :class="darkMode ? 'text-white' : 'text-slate-900'">
                        <?php echo t('انضم إلينا', 'Join Us'); ?>
                    </h1>
                    <p class="text-sm font-medium" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                        <?php echo t('ابدأ مسيرتك في تعلم القرآن والعلوم الإسلامية', 'Start your journey in Quran and Islamic sciences'); ?>
                    </p>
                </div>

                <form class="space-y-8" style="overflow: visible;">

                    <!-- Role Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" value="student" class="peer sr-only" x-model="role">
                            <div class="p-6 rounded-[2rem] border text-center transition-all duration-300 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white"
                                :class="darkMode ? 'bg-white/5 border-white/10 hover:bg-white/10' : 'bg-white border-black/10 hover:bg-slate-50'">
                                <div
                                    class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center mx-auto mb-4 peer-checked:bg-white/20 transition-colors">
                                    <svg class="w-7 h-7 text-indigo-500 peer-checked:text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                                        </path>
                                    </svg>
                                </div>
                                <div class="text-xs font-black uppercase tracking-wider">
                                    <?php echo t('طالب', 'Student'); ?>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" value="parent" class="peer sr-only" x-model="role">
                            <div class="p-6 rounded-[2rem] border text-center transition-all duration-300 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white"
                                :class="darkMode ? 'bg-white/5 border-white/10 hover:bg-white/10' : 'bg-white border-black/10 hover:bg-slate-50'">
                                <div
                                    class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center mx-auto mb-4 peer-checked:bg-white/20 transition-colors">
                                    <svg class="w-7 h-7 text-indigo-500 peer-checked:text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="text-xs font-black uppercase tracking-wider">
                                    <?php echo t('ولي أمر', 'Parent'); ?>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="role" value="teacher" class="peer sr-only" x-model="role">
                            <div class="p-6 rounded-[2rem] border text-center transition-all duration-300 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white"
                                :class="darkMode ? 'bg-white/5 border-white/10 hover:bg-white/10' : 'bg-white border-black/10 hover:bg-slate-50'">
                                <div
                                    class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center mx-auto mb-4 peer-checked:bg-white/20 transition-colors">
                                    <svg class="w-7 h-7 text-indigo-500 peer-checked:text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                </div>
                                <div class="text-xs font-black uppercase tracking-wider">
                                    <?php echo t('معلم', 'Teacher'); ?>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class=" space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest px-2"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('الاسم الكامل', 'Full Name'); ?>
                        </label>
                        <input type="text"
                            class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold"
                            :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'"
                            placeholder="<?php echo t('الاسم هنا..', 'Name here..'); ?>">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest px-2"
                                :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                <?php echo t('البريد الإلكتروني', 'Email Address'); ?>
                            </label>
                            <input type="email"
                                class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold"
                                :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'"
                                placeholder="example@mail.com">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest px-2"
                                :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                <?php echo t('رقم الهاتف', 'Phone Number'); ?>
                            </label>
                            <input type="tel" id="phone"
                                class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold"
                                :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest px-2"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('المنطقة الزمنية', 'Timezone'); ?>
                        </label>
                        <div class="relative">
                            <select
                                class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold appearance-none cursor-pointer"
                                :class="darkMode ? 'border-white/10 text-white' : 'border-black/10 text-slate-900'">
                                <?php
                                $zones = DateTimeZone::listIdentifiers();
                                $continentMap = [
                                    'Africa' => 'أفريقيا',
                                    'Asia' => 'آسيا',
                                    'Europe' => 'أوروبا',
                                    'America' => 'أمريكا',
                                    'Australia' => 'أستراليا',
                                    'Antarctica' => 'القطب الجنوبي',
                                    'Atlantic' => 'المحيط الأطلسي',
                                    'Pacific' => 'المحيط الهادئ',
                                    'Indian' => 'المحيط الهندي',
                                    'Arctic' => 'القطب الشمالي',
                                    'US' => 'الولايات المتحدة',
                                    'Canada' => 'كندا',
                                    'Brazil' => 'البرازيل',
                                    'Mexico' => 'المكسيك',
                                    'Chile' => 'تشيلي',
                                    'UTC' => 'التوقيت العالمي'
                                ];

                                $cityMap = [
                                    // Africa
                                    'Abidjan' => 'أبيدجان',
                                    'Accra' => 'أكرا',
                                    'Addis_Ababa' => 'أديس أبابا',
                                    'Algiers' => 'الجزائر',
                                    'Asmara' => 'أسمرة',
                                    'Bamako' => 'باماكو',
                                    'Bangui' => 'بانغي',
                                    'Banjul' => 'بانجول',
                                    'Bissau' => 'بيساو',
                                    'Blantyre' => 'بلانتير',
                                    'Brazzaville' => 'برازافيل',
                                    'Bujumbura' => 'بوجومبور',
                                    'Cairo' => 'القاهرة',
                                    'Casablanca' => 'الدار البيضاء',
                                    'Ceuta' => 'سبتة',
                                    'Conakry' => 'كوناكري',
                                    'Dakar' => 'داكار',
                                    'Dar_es_Salaam' => 'دار السلام',
                                    'Djibouti' => 'جيبوتي',
                                    'Douala' => 'دوالا',
                                    'El_Aaiun' => 'العيون',
                                    'Freetown' => 'فريتاون',
                                    'Gaborone' => 'غابورون',
                                    'Harare' => 'هراري',
                                    'Johannesburg' => 'جوهانسبرغ',
                                    'Juba' => 'جوبا',
                                    'Kampala' => 'كامبالا',
                                    'Khartoum' => 'الخرطوم',
                                    'Kigali' => 'كيغالي',
                                    'Kinshasa' => 'كينشاسا',
                                    'Lagos' => 'لاغوس',
                                    'Libreville' => 'ليبرفيل',
                                    'Lome' => 'لومي',
                                    'Luanda' => 'لواندا',
                                    'Lubumbashi' => 'لوبومباشي',
                                    'Lusaka' => 'لوساكا',
                                    'Malabo' => 'مالابو',
                                    'Maputo' => 'مابوتو',
                                    'Maseru' => 'ماسيرو',
                                    'Mbabane' => 'مبابان',
                                    'Mogadishu' => 'مقديشو',
                                    'Monrovia' => 'مونروفيا',
                                    'Nairobi' => 'نيروبي',
                                    'Ndjamena' => 'نجامينا',
                                    'Niamey' => 'نيامي',
                                    'Nouakchott' => 'نواكشوط',
                                    'Ouagadougou' => 'واغادوغو',
                                    'Porto-Novo' => 'بورتو نوفو',
                                    'Sao_Tome' => 'ساو تومي',
                                    'Tripoli' => 'طرابلس',
                                    'Tunis' => 'تونس',
                                    'Windhoek' => 'ويندهوك',

                                    // Asia
                                    'Aden' => 'عدن',
                                    'Almaty' => 'ألماتي',
                                    'Amman' => 'عمان',
                                    'Anadyr' => 'أنادير',
                                    'Aqtau' => 'أكتاو',
                                    'Aqtobe' => 'أكتوبي',
                                    'Ashgabat' => 'عشق آباد',
                                    'Atyrau' => 'أتيراو',
                                    'Baghdad' => 'بغداد',
                                    'Bahrain' => 'البحرين',
                                    'Baku' => 'باكو',
                                    'Bangkok' => 'بانكوك',
                                    'Barnaul' => 'بارناول',
                                    'Beirut' => 'بيروت',
                                    'Bishkek' => 'بيشكيك',
                                    'Brunei' => 'بروناي',
                                    'Chita' => 'تشيتا',
                                    'Choibalsan' => 'تشويبالسان',
                                    'Colombo' => 'كولومبو',
                                    'Damascus' => 'دمشق',
                                    'Dhaka' => 'دكا',
                                    'Dili' => 'ديلي',
                                    'Dubai' => 'دبي',
                                    'Dushanbe' => 'دوشنبه',
                                    'Famagusta' => 'فاماغوستا',
                                    'Gaza' => 'غزة',
                                    'Hebron' => 'الخليل',
                                    'Ho_Chi_Minh' => 'هو تشي منه',
                                    'Hong_Kong' => 'هونغ كونغ',
                                    'Hovd' => 'هوفد',
                                    'Irkutsk' => 'إيركوتسك',
                                    'Jakarta' => 'جاكرتا',
                                    'Jayapura' => 'جايابورا',
                                    'Jerusalem' => 'القدس',
                                    'Kabul' => 'كابول',
                                    'Kamchatka' => 'كامتشاتكا',
                                    'Karachi' => 'كراتشي',
                                    'Kathmandu' => 'كاتماندو',
                                    'Khandyga' => 'خانديغا',
                                    'Kolkata' => 'كلكتا',
                                    'Krasnoyarsk' => 'كراسنويارسك',
                                    'Kuala_Lumpur' => 'كوالالمبور',
                                    'Kuching' => 'كوتشينغ',
                                    'Kuwait' => 'الكويت',
                                    'Macau' => 'ماكاو',
                                    'Magadan' => 'ماجادان',
                                    'Makassar' => 'مكسر',
                                    'Manila' => 'مانيلا',
                                    'Muscat' => 'مسقط',
                                    'Nicosia' => 'نيقوسيا',
                                    'Novokuznetsk' => 'نوفوكوزنيتس',
                                    'Novosibirsk' => 'نوفوسيبيرسك',
                                    'Omsk' => 'أومسك',
                                    'Oral' => 'أورال',
                                    'Phnom_Penh' => 'بنوم بنه',
                                    'Pontianak' => 'بونتياناك',
                                    'Pyongyang' => 'بيونغ يانغ',
                                    'Qatar' => 'قطر',
                                    'Qostanay' => 'كوستاناي',
                                    'Qyzylorda' => 'كيزيلوردا',
                                    'Riyadh' => 'الرياض',
                                    'Sakhalin' => 'ساخالين',
                                    'Samarkand' => 'سمرقند',
                                    'Seoul' => 'سيول',
                                    'Shanghai' => 'شنغهاي',
                                    'Singapore' => 'سنغافورة',
                                    'Srednekolymsk' => 'سريدنيكوليمسك',
                                    'Taipei' => 'تايبيه',
                                    'Tashkent' => 'طشقند',
                                    'Tbilisi' => 'تبليسي',
                                    'Tehran' => 'طهران',
                                    'Thimphu' => 'تيمفو',
                                    'Tokyo' => 'طوكيو',
                                    'Tomsk' => 'تومسك',
                                    'Ulaanbaatar' => 'أولان باتور',
                                    'Urumqi' => 'أورومتشي',
                                    'Ust-Nera' => 'أوست-نيرا',
                                    'Vientiane' => 'فيينتيان',
                                    'Vladivostok' => 'فلاديفوستوك',
                                    'Yakutsk' => 'ياكوتسك',
                                    'Yangon' => 'يانغون',
                                    'Yekaterinburg' => 'يكاترينبورغ',
                                    'Yerevan' => 'يريفان',

                                    // Europe
                                    'Amsterdam' => 'أمستردام',
                                    'Andorra' => 'أندورا',
                                    'Astrakhan' => 'أستراخان',
                                    'Athens' => 'أثينا',
                                    'Belgrade' => 'بلغراد',
                                    'Berlin' => 'برلين',
                                    'Bratislava' => 'براتيسلافا',
                                    'Brussels' => 'بروكسل',
                                    'Bucharest' => 'بوخارست',
                                    'Budapest' => 'بودابست',
                                    'Busingen' => 'بوزنغن',
                                    'Chisinau' => 'كيشيناو',
                                    'Copenhagen' => 'كوبنهاغن',
                                    'Dublin' => 'دبلن',
                                    'Gibraltar' => 'جبل طارق',
                                    'Guernsey' => 'غيرنزي',
                                    'Helsinki' => 'هلسنكي',
                                    'Isle_of_Man' => 'جزيرة مان',
                                    'Istanbul' => 'إسطنبول',
                                    'Jersey' => 'جيرسي',
                                    'Kaliningrad' => 'كالينينغراد',
                                    'Kiev' => 'كييف',
                                    'Kirov' => 'كيروف',
                                    'Lisbon' => 'لشبونة',
                                    'Ljubljana' => 'ليوبليانا',
                                    'London' => 'لندن',
                                    'Luxembourg' => 'لوكسمبورغ',
                                    'Madrid' => 'مدريد',
                                    'Malta' => 'مالطا',
                                    'Mariehamn' => 'ماريهامن',
                                    'Minsk' => 'مينسك',
                                    'Monaco' => 'موناكو',
                                    'Moscow' => 'موسكو',
                                    'Oslo' => 'أوسلو',
                                    'Paris' => 'باريس',
                                    'Podgorica' => 'بودغوريتسا',
                                    'Prague' => 'براغ',
                                    'Riga' => 'ريغا',
                                    'Rome' => 'روما',
                                    'Samara' => 'سامارا',
                                    'Saratov' => 'ساراتوف',
                                    'Simferopol' => 'سيمفروبول',
                                    'Skopje' => 'سكوبيه',
                                    'Sofia' => 'صوفيا',
                                    'Stockholm' => 'ستوكهولم',
                                    'Tallinn' => 'تالين',
                                    'Tirane' => 'تيرانا',
                                    'Ulyanovsk' => 'أوليانوفسك',
                                    'Uzhgorod' => 'أوجهورود',
                                    'Vaduz' => 'فادوز',
                                    'Vatican' => 'الفاتيكان',
                                    'Vienna' => 'فيينا',
                                    'Vilnius' => 'فيلنيوس',
                                    'Volgograd' => 'فولغوغراد',
                                    'Warsaw' => 'وارسو',
                                    'Zagreb' => 'زغرب',
                                    'Zaporozhye' => 'زابوروجي',
                                    'Zurich' => 'زيورخ',

                                    // America & others
                                    'New_York' => 'نيويورك',
                                    'Chicago' => 'شيكاغو',
                                    'Denver' => 'دنفر',
                                    'Los_Angeles' => 'لوس أنجلوس',
                                    'Phoenix' => 'فينيكس',
                                    'Anchorage' => 'أنكوريج',
                                    'Honolulu' => 'هونولولو',
                                    'Toronto' => 'تورونتو',
                                    'Vancouver' => 'فانكوفر',
                                    'Montreal' => 'مونتريال',
                                    'Mexico_City' => 'مكسيكو سيتي',
                                    'Bogota' => 'بوغوتا',
                                    'Lima' => 'ليما',
                                    'Santiago' => 'سانتياغو',
                                    'Buenos_Aires' => 'بوينس آيرس',
                                    'Sao_Paulo' => 'ساو باولو',
                                    'Caracas' => 'كاراكاس',
                                    'Havana' => 'هافانا',
                                    'Santo_Domingo' => 'سانتو دومينغو',
                                    'Guatemala' => 'غواتيمالا',
                                    'Panama' => 'بنما',
                                    'Asuncion' => 'أسونسيون',
                                    'Montevideo' => 'مونتيفيديو',
                                    'Sydney' => 'سيدني',
                                    'Melbourne' => 'ملبورن',
                                    'Brisbane' => 'بريزبان',
                                    'Adelaide' => 'أديلايد',
                                    'Perth' => 'بيرث',
                                    'Hobart' => 'هوبارت',
                                    'Darwin' => 'داروين',
                                    'Auckland' => 'أوكلاند',
                                    'Fiji' => 'فيجي',
                                    'Guam' => 'غوام',
                                    'Port_Moresby' => 'بورت مورسبي',
                                    'Adak' => 'أداك',
                                    'Anguilla' => 'أنغيلا',
                                    'Antigua' => 'أنتيغوا',
                                    'Araguaina' => 'أراغوينا',
                                    'Argentina' => 'الأرجنتين',
                                    'Aruba' => 'أروبا',
                                    'Atikokan' => 'أتيكوكان',
                                    'Bahia' => 'باهيا',
                                    'Bahia_Banderas' => 'باهيا بانديراس',
                                    'Barbados' => 'بربادوس',
                                    'Belem' => 'بيليم',
                                    'Belize' => 'بليز',
                                    'Blanc-Sablon' => 'بلانك سابلون',
                                    'Boa_Vista' => 'بوا فيستا',
                                    'Boise' => 'بويز',
                                    'Cambridge_Bay' => 'خليج كامبريدج',
                                    'Campo_Grande' => 'كامبو غراندي',
                                    'Cancun' => 'كانكون',
                                    'Cayenne' => 'كايين',
                                    'Cayman' => 'كايمان',
                                    'Chihuahua' => 'تشيهواهوا',
                                    'Costa_Rica' => 'كوستاريكا',
                                    'Creston' => 'كريستون',
                                    'Cuiaba' => 'كويابا',
                                    'Curacao' => 'كوراساو',
                                    'Danmarkshavn' => 'دانماركشافن',
                                    'Dawson' => 'داوسون',
                                    'Dawson_Creek' => 'داوسون كريك',
                                    'Detroit' => 'ديترويت',
                                    'Dominica' => 'دومينيكا',
                                    'Edmonton' => 'إدمحنتون',
                                    'Eirunepe' => 'إيرونيبي',
                                    'El_Salvador' => 'السلفادور',
                                    'Fort_Nelson' => 'فورت نيلسون',
                                    'Fortaleza' => 'فورتاليزا',
                                    'Glace_Bay' => 'غلاس باي',
                                    'Godthab' => 'نوك',
                                    'Goose_Bay' => 'غوس باي',
                                    'Grand_Turk' => 'غراند ترك',
                                    'Grenada' => 'غرينادا',
                                    'Guadeloupe' => 'غوادلوب',
                                    'Guayaquil' => 'غواياكيل',
                                    'Guyana' => 'غويانا',
                                    'Halifax' => 'هاليفاكس',
                                    'Hermosillo' => 'هيرموسيلو',
                                    'Indiana' => 'إنديانا',
                                    'Inuvik' => 'إنوفيك',
                                    'Iqaluit' => 'إكالويت',
                                    'Jamaica' => 'جامايكا',
                                    'Juneau' => 'جونو',
                                    'Kentucky' => 'كنتاكي',
                                    'La_Paz' => 'لاباز',
                                    'Louisville' => 'لويزفيل',
                                    'Maceio' => 'ماسيو',
                                    'Managua' => 'ماناغوا',
                                    'Manaus' => 'ماناوس',
                                    'Marigot' => 'ماريغوت',
                                    'Martinique' => 'مارتينيك',
                                    'Matamoros' => 'ماتاموروس',
                                    'Mazatlan' => 'مازاتلان',
                                    'Menominee' => 'مينوميني',
                                    'Merida' => 'ميريدا',
                                    'Metlakatla' => 'ميتلاكاتلا',
                                    'Miquelon' => 'ميكلون',
                                    'Moncton' => 'مونكتون',
                                    'Monterrey' => 'مونتيري',
                                    'Montserrat' => 'مونتسيرات',
                                    'Nassau' => 'ناساو',
                                    'Nipigon' => 'نيبغون',
                                    'Nome' => 'نوم',
                                    'Noronha' => 'نورونها',
                                    'North_Dakota' => 'نورث داكوتا',
                                    'Ojinaga' => 'أوجيناغا',
                                    'Pangnirtung' => 'بانغنيرتونغ',
                                    'Paramaribo' => 'باراماريبو',
                                    'Port-au-Prince' => 'بورت أو برنس',
                                    'Port_of_Spain' => 'بورت أوف سبين',
                                    'Porto_Velho' => 'بورتو فيلهو',
                                    'Puerto_Rico' => 'بورتوريكو',
                                    'Punta_Arenas' => 'بونتا أريناس',
                                    'Rainy_River' => 'ريني ريفر',
                                    'Rankin_Inlet' => 'رانكين إنليت',
                                    'Recife' => 'ريسيفي',
                                    'Regina' => 'ريجينا',
                                    'Resolute' => 'ريزوليوت',
                                    'Rio_Branco' => 'ريو برانكو',
                                    'Santa_Isabel' => 'سانتا إيزابيل',
                                    'Santarem' => 'سانتاريم',
                                    'Scoresbysund' => 'سكورسبيسوند',
                                    'Sitka' => 'سيتكا',
                                    'St_Barthelemy' => 'سانت بارتيليمي',
                                    'St_Johns' => 'سانت جونز',
                                    'St_Kitts' => 'سانت كيتس',
                                    'St_Lucia' => 'سانت لوسيا',
                                    'St_Thomas' => 'سانت توماس',
                                    'St_Vincent' => 'سانت فنسنت',
                                    'Swift_Current' => 'سويفت كارنت',
                                    'Tegucigalpa' => 'تيغوسيغالبا',
                                    'Thule' => 'ثول',
                                    'Thunder_Bay' => 'ثاندر باي',
                                    'Tijuana' => 'تيخوانا',
                                    'Tortola' => 'تورتولا',
                                    'Whitehorse' => 'وايت هورس',
                                    'Winnipeg' => 'وينيبيغ',
                                    'Yakutat' => 'ياكوتات',
                                    'Yellowknife' => 'يلونايف',

                                    // Antarctic & Others
                                    'Casey' => 'كيسي',
                                    'Davis' => 'ديفيس',
                                    'DumontDUrville' => 'دومونت دورفيل',
                                    'Macquarie' => 'ماكواري',
                                    'Mawson' => 'ماوسون',
                                    'McMurdo' => 'ماكموردو',
                                    'Palmer' => 'بالمر',
                                    'Rothera' => 'روثيرا',
                                    'Syowa' => 'سيووا',
                                    'Troll' => 'ترول',
                                    'Vostok' => 'فوستوك',
                                    'Longyearbyen' => 'لونغييربين',
                                    'Azores' => 'جزر الأزور',
                                    'Bermuda' => 'برمودا',
                                    'Canary' => 'جزر الكناري',
                                    'Cape_Verde' => 'الرأس الأخضر',
                                    'Faroe' => 'جزر فارو',
                                    'Madeira' => 'ماديرا',
                                    'Reykjavik' => 'ريكيافيك',
                                    'South_Georgia' => 'جورجيا الجنوبية',
                                    'St_Helena' => 'سانت هيلينا',
                                    'Stanley' => 'ستانلي',
                                    'Antananarivo' => 'أنتاناناريفو',
                                    'Chagos' => 'تشاغوس',
                                    'Christmas' => 'جزيرة الكريسماس',
                                    'Cocos' => 'جزر كوكوس',
                                    'Comoro' => 'جزر القمر',
                                    'Kerguelen' => 'كيرغولين',
                                    'Mahe' => 'ماهي',
                                    'Maldives' => 'المالديف',
                                    'Mauritius' => 'موريشيوس',
                                    'Mayotte' => 'مايوت',
                                    'Reunion' => 'ريونيون',
                                    'Apia' => 'أبيا',
                                    'Bougainville' => 'بوغانفيل',
                                    'Chatham' => 'تشاتام',
                                    'Chuuk' => 'تشوك',
                                    'Easter' => 'جزيرة القيامة',
                                    'Efate' => 'إيفات',
                                    'Enderbury' => 'إندربوري',
                                    'Fakaofo' => 'فاكاوفو',
                                    'Funafuti' => 'فونافوتي',
                                    'Galapagos' => 'غالاباغوس',
                                    'Gambier' => 'غامبير',
                                    'Guadalcanal' => 'غوادالكانال',
                                    'Kanton' => 'كانتون',
                                    'Kiritimati' => 'كيريتيقاتي',
                                    'Kosrae' => 'كوسراي',
                                    'Kwajalein' => 'كواغالين',
                                    'Majuro' => 'ماجورو',
                                    'Marquesas' => 'ماركيز',
                                    'Midway' => 'ميدواي',
                                    'Nauru' => 'ناورو',
                                    'Niue' => 'نيوي',
                                    'Norfolk' => 'نورفولك',
                                    'Noumea' => 'نوميا',
                                    'Pago_Pago' => 'باغو باغو',
                                    'Palau' => 'بالاو',
                                    'Pitcairn' => 'بيتكيرن',
                                    'Pohnpei' => 'بونبي',
                                    'Rarotonga' => 'راروتونغا',
                                    'Saipan' => 'سايبان',
                                    'Tahiti' => 'تاهيتي',
                                    'Tarawa' => 'تاراوا',
                                    'Tongatapu' => 'تونغاتابو',
                                    'Wake' => 'ويك',
                                    'Wallis' => 'واليس',
                                    'UTC' => 'التوقيت العالمي',

                                    'Davis' => 'ديفيس',
                                    'DumontDUrville' => 'دومونت دورفيل',
                                    'Macquarie' => 'ماكواري',
                                    'Mawson' => 'ماوسون',
                                    'McMurdo' => 'ماكموردو',
                                    'Palmer' => 'بالمر',
                                    'Rothera' => 'روثيرا',
                                    'Syowa' => 'سيووا',
                                    'Troll' => 'ترول',
                                    'Vostok' => 'فوستوك',
                                    'Longyearbyen' => 'لونغييربين',
                                    'Azores' => 'جزر الأزور',
                                    'Bermuda' => 'برمودا',
                                    'Canary' => 'جزر الكناري',
                                    'Cape_Verde' => 'الرأس الأخضر',
                                    'Faroe' => 'جزر فارو',
                                    'Madeira' => 'ماديرا',
                                    'Reykjavik' => 'ريكيافيك',
                                    'South_Georgia' => 'جورجيا الجنوبية',
                                    'St_Helena' => 'سانت هيلينا',
                                    'Stanley' => 'ستانلي',
                                    'Antananarivo' => 'أنتاناناريفو',
                                    'Chagos' => 'تشاغوس',
                                    'Christmas' => 'جزيرة الكريسماس',
                                    'Cocos' => 'جزر كوكوس',
                                    'Comoro' => 'جزر القمر',
                                    'Kerguelen' => 'كيرغولين',
                                    'Mahe' => 'ماهي',
                                    'Maldives' => 'المالديف',
                                    'Mauritius' => 'موريشيوس',
                                    'Mayotte' => 'مايوت',
                                    'Reunion' => 'ريونيون',
                                    'GMT' => 'توقيت غرينتش'
                                ];

                                foreach ($zones as $zone) {
                                    if ($lang == 'ar') {
                                        $parts = explode('/', $zone);
                                        $display = $zone;

                                        if (count($parts) > 1) {
                                            $continent = $parts[0];
                                            $city = end($parts);

                                            $displayContinent = isset($continentMap[$continent]) ? $continentMap[$continent] : $continent;
                                            $displayCity = isset($cityMap[$city]) ? $cityMap[$city] : str_replace('_', ' ', $city);
                                            $display = "$displayContinent / $displayCity";
                                        } else {
                                            $display = isset($cityMap[$zone]) ? $cityMap[$zone] : (isset($continentMap[$zone]) ? $continentMap[$zone] : $zone);
                                        }
                                    } else {
                                        $display = str_replace(['_', '/'], [' ', ' / '], $zone);
                                    }
                                    echo "<option value='$zone'>$display</option>";
                                }
                                ?>
                            </select>
                            <div class="absolute top-1/2 right-6 -translate-y-1/2 pointer-events-none"
                                :class="$dir == 'rtl' ? 'left-6 right-auto' : ''">
                                <svg class="w-4 h-4" :class="darkMode ? 'text-white' : 'text-slate-900'" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest px-2"
                                :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                <?php echo t('كلمة المرور', 'Password'); ?>
                            </label>
                            <input type="password"
                                class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold"
                                :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'"
                                placeholder="••••••••">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest px-2"
                                :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                <?php echo t('تأكيد الكلمة', 'Confirm'); ?>
                            </label>
                            <input type="password"
                                class="w-full bg-transparent border rounded-2xl px-6 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all text-sm font-bold"
                                :class="darkMode ? 'border-white/10 text-white placeholder-white/20' : 'border-black/10 text-slate-900 placeholder-black/20'"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-2">
                        <input type="checkbox" id="terms"
                            class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="terms" class="text-xs font-bold cursor-pointer"
                            :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <?php echo t('أوافق على الشروط والأحكام وسياسة الخصوصية', 'I agree to Terms & Conditions and Privacy Policy'); ?>
                        </label>
                    </div>

                    <button
                        class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl hover:bg-indigo-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                        <span><?php echo t('إنشاء حساب', 'Create Account'); ?></span>
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <div class="mt-8 text-center text-sm font-bold" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    <?php echo t('لديك حساب بالفعل؟', 'Already have an account?'); ?>
                    <a href="login.php"
                        class="text-indigo-500 hover:text-indigo-400 ml-1 underline decoration-2 underline-offset-4">
                        <?php echo t('سجل دخول', 'Login'); ?>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Intl Tel Input JS -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <style>
        /* Dark Mode Support for Intl Tel Input */
        .dark .iti__country-list {
            background-color: #020617 !important;
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }

        .dark .iti__country:hover,
        .dark .iti__country.iti__highlight {
            background-color: #1e293b !important;
        }

        .dark .iti__dial-code {
            color: #cbd5e1 !important;
        }

        .dark .iti__selected-country {
            background-color: transparent !important;
        }

        .iti__flag {
            box-shadow: none !important;
        }

        /* Fix dropdown width/position */
        .iti__dropdown-content {
            border-radius: 1rem;
            overflow: hidden;
        }
    </style>
    <script>
        const input = document.querySelector("#phone");
        const lang = '<?php echo $lang; ?>';

        let initOptions = {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
            initialCountry: "ae",
            preferredCountries: ["ae", "sa", "eg", "kw", "qa", "om", "bh"],
            separateDialCode: true,
        };

        const arabicCountries = {
            "af": "أفغانستان", "al": "ألبانيا", "dz": "الجزائر", "as": "ساموا الأمريكية", "ad": "أندورا",
            "ao": "أنغولا", "ai": "أنغيلا", "aq": "أنتاركتيكا", "ag": "أنتيغوا وبربودا", "ar": "الأرجنتين",
            "am": "أرمينيا", "aw": "أروبا", "au": "أستراليا", "at": "النمسا", "az": "أذربيجان",
            "bs": "جزر البهاما", "bh": "البحرين", "bd": "بنغلاديش", "bb": "بربادوس", "by": "بيلاروسيا",
            "be": "بلجيكا", "bz": "بليز", "bj": "بنين", "bm": "برمودا", "bt": "بوتان",
            "bo": "بوليفيا", "ba": "البوسنة والهرسك", "bw": "بوتسوانا", "bv": "جزيرة بوفيه", "br": "البرازيل",
            "io": "الإقليم البريطاني في المحيط الهندي", "bn": "بروناي", "bg": "بلغاريا", "bf": "بوركينا فاسو", "bi": "بوروندي",
            "kh": "كمبوديا", "cm": "الكاميرون", "ca": "كندا", "cv": "الرأس الأخضر", "ky": "جزر كايمان",
            "cf": "جمهورية أفريقيا الوسطى", "td": "تشاد", "cl": "تشيلي", "cn": "الصين", "cx": "جزيرة الكريسماس",
            "cc": "جزر كوكوس (كيلينغ)", "co": "كولومبيا", "km": "جزر القمر", "cg": "الكونغو - برازاديل", "cd": "الكونغو - كينشاسا",
            "ck": "جزر كوك", "cr": "كوستاريكا", "ci": "ساحل العاج", "hr": "كرواتيا", "cu": "كوبا",
            "cy": "قبرص", "cz": "جمهورية التشيك", "dk": "الدانمرك", "dj": "جيبوتي", "dm": "دومينيكا",
            "do": "جمهورية الدومينيكان", "ec": "الإكوادور", "eg": "مصر", "sv": "السلفادور", "gq": "غينيا الاستوائية",
            "er": "إريتريا", "ee": "إستونيا", "et": "إثيوبيا", "fk": "جزر فوكلاند", "fo": "جزر فارو",
            "fj": "فيجي", "fi": "فنلندا", "fr": "فرنسا", "gf": "غويانا الفرنسية", "pf": "بولينيزيا الفرنسية",
            "tf": "الأقاليم الجنوبية الفرنسية", "ga": "الغابون", "gm": "غامبيا", "ge": "جورجيا", "de": "ألمانيا",
            "gh": "غانا", "gi": "جبل طارق", "gr": "اليونان", "gl": "جرينلاند", "gd": "غرينادا",
            "gp": "جوادلوب", "gu": "غوام", "gt": "غواتيمالا", "gn": "غينيا", "gw": "غينيا بيساو",
            "gy": "غويانا", "ht": "هايتي", "hm": "جزيرة هيرد وجزر ماكدونالد", "va": "الفاتيكان", "hn": "هندوراس",
            "hk": "هونغ كونغ", "hu": "المجر", "is": "أيسلندا", "in": "الهند", "id": "إندونيسيا",
            "ir": "إيران", "iq": "العراق", "ie": "أيرلندا", "il": "إسرائيل", "it": "إيطاليا",
            "jm": "جامايكا", "jp": "اليابان", "jo": "الأردن", "kz": "كازاخستان", "ke": "كينيا",
            "ki": "كيريباتي", "kp": "كوريا الشمالية", "kr": "كوريا الجنوبية", "kw": "الكويت", "kg": "قرغيزستان",
            "la": "لاوس", "lv": "لاتفيا", "lb": "لبنان", "ls": "ليسوتو", "lr": "ليبيريا",
            "ly": "ليبيا", "li": "ليختنشتاين", "lt": "ليتوانيا", "lu": "لوكسمبورغ", "mo": "ماكاو",
            "mk": "مقـدونيا", "mg": "مدغشقر", "mw": "ملاوي", "my": "ماليزيا", "mv": "جزر المالديف",
            "ml": "مالي", "mt": "مالطا", "mh": "جزر مارشال", "mq": "مارتينيك", "mr": "موريتانيا",
            "mu": "موريشيوس", "yt": "مايوت", "mx": "المكسيك", "fm": "ميكرونيزيا", "md": "مولدوفا",
            "mc": "موناكو", "mn": "منغوليا", "ms": "مونتسيرات", "ma": "المغرب", "mz": "موزمبيق",
            "mm": "ميانمار", "na": "ناميبيا", "nr": "ناورو", "np": "نيبال", "nl": "هولندا",
            "an": "جزر الأنتيل الهولندية", "nc": "كاليدونيا الجديدة", "nz": "نيوزيلندا", "ni": "نيكاراغوا", "ne": "النيجر",
            "ng": "نيجيريا", "nu": "نيوي", "nf": "جزيرة نورفولك", "mp": "جزر ماريانا الشمالية", "no": "النرويج",
            "om": "عمان", "pk": "باكستان", "pw": "بالاو", "ps": "فلسطين", "pa": "بنما",
            "pg": "بابوا غينيا الجديدة", "py": "باراغواي", "pe": "بيرو", "ph": "الفلبين", "pn": "بيتكيرن",
            "pl": "بولندا", "pt": "البرتغال", "pr": "بورتوريكو", "qa": "قطر", "re": "ريونيون",
            "ro": "رومانيا", "ru": "روسيا", "rw": "رواندا", "sh": "سانت هيلينا", "kn": "سانت كيتس ونيفيس",
            "lc": "سانت لوسيا", "pm": "سانت بيير وميكلون", "vc": "سانت فنسنت وجزر غرينادين", "ws": "ساموا", "sm": "سان مارينو",
            "st": "ساو تومي وبرينسيبي", "sa": "السعودية", "sn": "السنغال", "cs": "صربيا والجبل الأسود", "sc": "سيشيل",
            "sl": "سيراليون", "sg": "سنغافورة", "sk": "سلوفاكيا", "si": "سلوفينيا", "sb": "جزر سليمان",
            "so": "الصومال", "za": "جنوب أفريقيا", "gs": "جورجيا الجنوبية وجزر ساندويتش الجنوبية", "es": "إسبانيا", "lk": "سريلانكا",
            "sd": "السودان", "sr": "سورينام", "sj": "سفالبارد ويانية ماين", "sz": "سوازيلاند", "se": "السويد",
            "ch": "سويسرا", "sy": "سوريا", "tw": "تايوان", "tj": "طاجيكستان", "tz": "تنزانيا",
            "th": "تايلاند", "tl": "تيمور الشرقية", "tg": "توغو", "tk": "توكيلاو", "to": "تونغا",
            "tt": "ترينيداد وتوباغو", "tn": "تونس", "tr": "تركيا", "tm": "تركمانستان", "tc": "جزر تركس وكايكوس",
            "tv": "توفالو", "ug": "أوغندا", "ua": "أوكرانيا", "ae": "الإمارات العربية المتحدة", "gb": "المملكة المتحدة",
            "us": "الولايات المتحدة", "um": "جزر الولايات المتحدة الصغيرة النائية", "uy": "أوروغواي", "uz": "أوزبكستان", "vu": "فانواتو",
            "ve": "فنزويلا", "vn": "فيتنام", "vg": "جزر العذراء البريطانية", "vi": "جزر العذراء الأمريكية", "wf": "واليس وفوتونا",
            "eh": "الصحراء الغربية", "ye": "اليمن", "zm": "زامبيا", "zw": "زيمبابوي"
        };

        const englishCountries = { "af": "Afghanistan", "al": "Albania", "dz": "Algeria", "as": "American Samoa", "ad": "Andorra", "ao": "Angola", "ai": "Anguilla", "aq": "Antarctica", "ag": "Antigua and Barbuda", "ar": "Argentina", "am": "Armenia", "aw": "Aruba", "au": "Australia", "at": "Austria", "az": "Azerbaijan", "bs": "Bahamas", "bh": "Bahrain", "bd": "Bangladesh", "bb": "Barbados", "by": "Belarus", "be": "Belgium", "bz": "Belize", "bj": "Benin", "bm": "Bermuda", "bt": "Bhutan", "bo": "Bolivia", "ba": "Bosnia and Herzegovina", "bw": "Botswana", "bv": "Bouvet Island", "br": "Brazil", "io": "British Indian Ocean Territory", "bn": "Brunei", "bg": "Bulgaria", "bf": "Burkina Faso", "bi": "Burundi", "kh": "Cambodia", "cm": "Cameroon", "ca": "Canada", "cv": "Cape Verde", "ky": "Cayman Islands", "cf": "Central African Republic", "td": "Chad", "cl": "Chile", "cn": "China", "cx": "Christmas Island", "cc": "Cocos (Keeling) Islands", "co": "Colombia", "km": "Comoros", "cg": "Congo (Brazzaville)", "cd": "Congo (Kinshasa)", "ck": "Cook Islands", "cr": "Costa Rica", "ci": "Côte d'Ivoire", "hr": "Croatia", "cu": "Cuba", "cy": "Cyprus", "cz": "Czech Republic", "dk": "Denmark", "dj": "Djibouti", "dm": "Dominica", "do": "Dominican Republic", "ec": "Ecuador", "eg": "Egypt", "sv": "El Salvador", "gq": "Equatorial Guinea", "er": "Eritrea", "ee": "Estonia", "et": "Ethiopia", "fk": "Falkland Islands", "fo": "Faroe Islands", "fj": "Fiji", "fi": "Finland", "fr": "France", "gf": "French Guiana", "pf": "French Polynesia", "tf": "French Southern Territories", "ga": "Gabon", "gm": "Gambia", "ge": "Georgia", "de": "Germany", "gh": "Ghana", "gi": "Gibraltar", "gr": "Greece", "gl": "Greenland", "gd": "Grenada", "gp": "Guadeloupe", "gu": "Guam", "gt": "Guatemala", "gn": "Guinea", "gw": "Guinea-Bissau", "gy": "Guyana", "ht": "Haiti", "hm": "Heard Island and McDonald Islands", "va": "Vatican City", "hn": "Honduras", "hk": "Hong Kong", "hu": "Hungary", "is": "Iceland", "in": "India", "id": "Indonesia", "ir": "Iran", "iq": "Iraq", "ie": "Ireland", "il": "Israel", "it": "Italy", "jm": "Jamaica", "jp": "Japan", "jo": "Jordan", "kz": "Kazakhstan", "ke": "Kenya", "ki": "Kiribati", "kp": "North Korea", "kr": "South Korea", "kw": "Kuwait", "kg": "Kyrgyzstan", "la": "Laos", "lv": "Latvia", "lb": "Lebanon", "ls": "Lesotho", "lr": "Liberia", "ly": "Libya", "li": "Liechtenstein", "lt": "Lithuania", "lu": "Luxembourg", "mo": "Macau", "mk": "North Macedonia", "mg": "Madagascar", "mw": "Malawi", "my": "Malaysia", "mv": "Maldives", "ml": "Mali", "mt": "Malta", "mh": "Marshall Islands", "mq": "Martinique", "mr": "Mauritania", "mu": "Mauritius", "yt": "Mayotte", "mx": "Mexico", "fm": "Micronesia", "md": "Moldova", "mc": "Monaco", "mn": "Mongolia", "ms": "Montserrat", "ma": "Morocco", "mz": "Mozambique", "mm": "Myanmar", "na": "Namibia", "nr": "Nauru", "np": "Nepal", "nl": "Netherlands", "an": "Netherlands Antilles", "nc": "New Caledonia", "nz": "New Zealand", "ni": "Nicaragua", "ne": "Niger", "ng": "Nigeria", "nu": "Niue", "nf": "Norfolk Island", "mp": "Northern Mariana Islands", "no": "Norway", "om": "Oman", "pk": "Pakistan", "pw": "Palau", "ps": "Palestine", "pa": "Panama", "pg": "Papua New Guinea", "py": "Paraguay", "pe": "Peru", "ph": "Philippines", "pn": "Pitcairn", "pl": "Poland", "pt": "Portugal", "pr": "Puerto Rico", "qa": "Qatar", "re": "Réunion", "ro": "Romania", "ru": "Russia", "rw": "Rwanda", "sh": "Saint Helena", "kn": "Saint Kitts and Nevis", "lc": "Saint Lucia", "pm": "Saint Pierre and Miquelon", "vc": "Saint Vincent and the Grenadines", "ws": "Samoa", "sm": "San Marino", "st": "São Tomé and Príncipe", "sa": "Saudi Arabia", "sn": "Senegal", "cs": "Serbia and Montenegro", "sc": "Seychelles", "sl": "Sierra Leone", "sg": "Singapore", "sk": "Slovakia", "si": "Slovenia", "sb": "Solomon Islands", "so": "Somalia", "za": "South Africa", "gs": "South Georgia and the South Sandwich Islands", "es": "Spain", "lk": "Sri Lanka", "sd": "Sudan", "sr": "Suriname", "sj": "Svalbard and Jan Mayen", "sz": "Swaziland", "se": "Sweden", "ch": "Switzerland", "sy": "Syria", "tw": "Taiwan", "tj": "Tajikistan", "tz": "Tanzania", "th": "Thailand", "tl": "Timor-Leste", "tg": "Togo", "tk": "Tokelau", "to": "Tonga", "tt": "Trinidad and Tobago", "tn": "Tunisia", "tr": "Turkey", "tm": "Turkmenistan", "tc": "Turks and Caicos Islands", "tv": "Tuvalu", "ug": "Uganda", "ua": "Ukraine", "ae": "United Arab Emirates", "gb": "United Kingdom", "us": "United States", "um": "United States Minor Outlying Islands", "uy": "Uruguay", "uz": "Uzbekistan", "vu": "Vanuatu", "ve": "Venezuela", "vn": "Vietnam", "vg": "British Virgin Islands", "vi": "U.S. Virgin Islands", "wf": "Wallis and Futuna", "eh": "Western Sahara", "ye": "Yemen", "zm": "Zambia", "zw": "Zimbabwe" };

        if (lang === 'ar') {
            initOptions.localizedCountries = arabicCountries;
        } else {
            initOptions.localizedCountries = englishCountries;
        }

        const iti = window.intlTelInput(input, initOptions);
    </script>


</body>

</html>