<?php
// Database connection
try {
    $db_path = __DIR__ . '/../database.sqlite';
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create tables if they don't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'user',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS courses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            teacher_id INTEGER,
            image TEXT,
            category TEXT,
            badge TEXT,
            features TEXT, -- JSON string
            duration TEXT,
            price DECIMAL(10,2) DEFAULT 0,
            status TEXT DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (teacher_id) REFERENCES users(id)
        )
    ");

    // Add columns if they don't exist (Migration)
    $columns = [
        'image' => 'TEXT',
        'category' => 'TEXT',
        'badge' => 'TEXT',
        'features' => 'TEXT',
        'duration' => 'TEXT'
    ];
    foreach ($columns as $col => $type) {
        try {
            $pdo->exec("ALTER TABLE courses ADD COLUMN $col $type");
        } catch (Exception $e) {
        } // Ignore if already exists
    }

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key TEXT UNIQUE NOT NULL,
            setting_value TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Seed Courses if empty
    $count = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    if ($count == 0) {
        $default_courses = [
            [
                'title' => 'تحفيظ القرآن الكريم بالقرصان',
                'description' => 'دورة شاملة لحفظ كتاب الله بالتلقي والمشافهة مع خطة فردية لكل طالب.',
                'image' => 'assets/images/quran_course_img_1770121397437.png',
                'category' => 'القرآن الكريم',
                'badge' => 'الأكثر طلباً',
                'duration' => 'مستمر',
                'price' => '$49',
                'features' => json_encode(['حفظ بالتلقي والمشافهة', 'تصحيح التلاوة بدقة', 'خطة حفظ فردية مستدامة', 'متابعة يومية مع الشيخ'])
            ],
            [
                'title' => 'دورة نور البيان للأطفال',
                'description' => 'تأسيس قوي للأطفال في قراءة اللغة العربية والقرآن الكريم بأساليب ممتعة.',
                'image' => 'assets/images/noor_bayan_course_img_1770121413393.png',
                'category' => 'اللغة العربية',
                'badge' => 'للمبتدئين',
                'duration' => '8 أسابيع',
                'price' => '$29',
                'features' => json_encode(['إتقان القراءة بالحركات', 'تمكين مخارج الحروف', 'تأسيس قوي في الكتابة', 'أساليب مشوقة للأطفال'])
            ],
            [
                'title' => 'إتقان التجويد العملي',
                'description' => 'شرح متون التجويد مع تطبيق عملي مكثف لتحسين الصوت والأداء.',
                'image' => 'assets/images/tajweed_course_img_1770121430041.png',
                'category' => 'التجويد',
                'badge' => 'احترافي',
                'duration' => '12 أسبوع',
                'price' => '$59',
                'features' => json_encode(['شرح نظري وتطبيق عملي', 'دراسة متون التجويد', 'تحسين الصوت والأداء', 'اختبارات دورية مكثفة'])
            ],
            [
                'title' => 'أساسيات الدراسات الإسلامية',
                'description' => 'تعلم العقيدة والفقه والأخلاق بأسلوب عصري يربط العلم بالعمل.',
                'image' => 'assets/images/islamic_studies_course_img_1770121444719.png',
                'category' => 'شرعي',
                'badge' => 'شامل',
                'duration' => '10 أسابيع',
                'price' => '$39',
                'features' => json_encode(['أساسيات العقيدة الصحيحة', 'فقه العبادات والمعاملات', 'شرح الأحاديث النبوية', 'تزكية النفس والأخلاق'])
            ],
            [
                'title' => 'تفسير آيات الأحكام',
                'description' => 'فهم مقاصد الآيات واستنباط الأحكام الشرعية بأسلوب ميسر.',
                'image' => 'assets/images/tafsir_course_img_v2_1770121485567.png',
                'category' => 'تفسير',
                'badge' => 'مميز',
                'duration' => '14 أسبوع',
                'price' => '$69',
                'features' => json_encode(['فهم مقاصد الآيات', 'استنباط الأحكام الشرعية', 'ربط التفسير بالواقع', 'تحليل لغوي وبياني'])
            ],
            [
                'title' => 'اللغة العربية لغير الناطقين',
                'description' => 'تعلم النحو والصرف وتطوير مهارات التحدث بطلاقة.',
                'image' => 'assets/images/arabic_lang_course_img_1770121459816.png',
                'category' => 'لغة',
                'badge' => 'مكثف',
                'duration' => '24 أسبوع',
                'price' => '$129',
                'features' => json_encode(['النحو والصرف بتبسيط', 'تنمية مهارات التحدث', 'فهم الأدب والبلاغة', 'إعداد لاختبارات الكفاءة'])
            ]
        ];

        $insert_stmt = $pdo->prepare("INSERT INTO courses (title, description, image, category, badge, duration, price, features) VALUES (:title, :description, :image, :category, :badge, :duration, :price, :features)");
        foreach ($default_courses as $course) {
            $insert_stmt->execute($course);
        }
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
