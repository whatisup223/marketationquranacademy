<?php
// Seed database with sample data
require 'dashboard/db.php';

try {
    // Clear existing data
    $pdo->exec("DELETE FROM users");
    $pdo->exec("DELETE FROM courses");
    $pdo->exec("DELETE FROM settings");

    // Insert sample users
    $users = [
        ['أحمد محمد', 'admin@elsona.com', password_hash('admin123', PASSWORD_DEFAULT), 'admin'],
        ['محمد علي', 'teacher1@elsona.com', password_hash('teacher123', PASSWORD_DEFAULT), 'teacher'],
        ['فاطمة أحمد', 'teacher2@elsona.com', password_hash('teacher123', PASSWORD_DEFAULT), 'teacher'],
        ['عمر خالد', 'student1@elsona.com', password_hash('student123', PASSWORD_DEFAULT), 'student'],
        ['سارة محمود', 'student2@elsona.com', password_hash('student123', PASSWORD_DEFAULT), 'student'],
        ['يوسف إبراهيم', 'student3@elsona.com', password_hash('student123', PASSWORD_DEFAULT), 'student'],
    ];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    foreach ($users as $user) {
        $stmt->execute($user);
    }

    // Insert sample courses
    $courses = [
        ['تحفيظ القرآن الكريم - المستوى الأول', 'دورة تحفيظ القرآن الكريم للمبتدئين مع التجويد', 2, 299.99],
        ['التجويد المتقدم', 'دورة متقدمة في أحكام التجويد والتلاوة الصحيحة', 2, 399.99],
        ['حفظ جزء عم', 'برنامج مكثف لحفظ جزء عم كاملاً', 3, 249.99],
        ['القراءات العشر', 'دراسة القراءات العشر المتواترة', 2, 599.99],
        ['التفسير الميسر', 'دورة في تفسير القرآن الكريم بأسلوب مبسط', 3, 349.99],
    ];

    $stmt = $pdo->prepare("INSERT INTO courses (title, description, teacher_id, price) VALUES (?, ?, ?, ?)");
    foreach ($courses as $course) {
        $stmt->execute($course);
    }

    // Insert default settings
    $settings = [
        ['site_name', 'أهل السنة'],
        ['site_email', 'info@elsona.com'],
        ['site_phone', '+966 XX XXX XXXX'],
        ['timezone', 'Asia/Riyadh'],
        ['currency', 'SAR'],
        ['students_per_class', '10'],
        ['class_duration', '60'],
        ['enable_registration', '1'],
        ['enable_notifications', '1'],
    ];

    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }

    echo "✅ Database seeded successfully!\n\n";
    echo "Login credentials:\n";
    echo "Admin: admin@elsona.com / admin123\n";
    echo "Teacher: teacher1@elsona.com / teacher123\n";
    echo "Student: student1@elsona.com / student123\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
