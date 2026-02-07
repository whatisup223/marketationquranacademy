<?php
require '../includes/header.php';
require '../db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Fetch payments for this student
$stmt = $pdo->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$payments = $stmt->fetchAll();
?>

<div class="mb-12">
    <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-2">
        <?php echo t('سجل المدفوعات', 'Billing & Payments'); ?>
    </h2>
    <p class="text-slate-500 font-medium">
        <?php echo t('متابعة اشتراكاتك وفواتيرك المالية', 'Track your subscriptions and financial invoices'); ?>
    </p>
</div>

<div
    class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
    <table class="w-full text-right">
        <thead class="bg-slate-50 dark:bg-slate-850 border-b border-slate-100 dark:border-slate-800">
            <tr>
                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <?php echo t('رقم الفاتورة', 'Invoice ID'); ?>
                </th>
                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <?php echo t('القيمة', 'Amount'); ?>
                </th>
                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <?php echo t('الطريقة', 'Method'); ?>
                </th>
                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <?php echo t('الحالة', 'Status'); ?>
                </th>
                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">
                    <?php echo t('تاريخ المعاملة', 'Date'); ?>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td class="px-8 py-6 font-bold text-slate-800 dark:text-gray-200">#PAY-
                        <?php echo $payment['id']; ?>
                    </td>
                    <td class="px-8 py-6 font-black text-slate-900 dark:text-white">$
                        <?php echo number_format($payment['amount'], 2); ?>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-xs font-bold text-slate-500 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg">
                            <?php echo htmlspecialchars($payment['method']); ?>
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                            <?php echo $payment['status'] === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
                            <?php echo t($payment['status'], ucfirst($payment['status'])); ?>
                        </span>
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500 text-left">
                        <?php echo date('Y-m-d', strtotime($payment['created_at'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($payments)): ?>
                <tr>
                    <td colspan="5" class="py-24 text-center">
                        <div class="text-slate-300 font-black text-xs uppercase tracking-widest italic">
                            <?php echo t('لا توجد معاملات مالية حتى الآن', 'No payment history available'); ?>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require '../includes/footer.php'; ?>