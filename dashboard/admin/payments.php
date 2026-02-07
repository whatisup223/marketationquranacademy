<?php
require '../includes/header.php';
require '../db.php';

// Fetch payments with user details
$payments = $pdo->query("
    SELECT p.*, u.name as user_name 
    FROM payments p 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
")->fetchAll();

$total_revenue = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'completed'")->fetchColumn() ?: 0;
?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-black text-slate-800 dark:text-white">
            <?php echo t('الإدارة المالية', 'Financial Management'); ?>
        </h2>
        <p class="text-slate-500 text-sm mt-1">
            <?php echo t('تتبع المدفوعات والاشتراكات والتقارير المالية', 'Track payments, subscriptions and financial reports'); ?>
        </p>
    </div>
    <div class="bg-emerald-50 dark:bg-emerald-900/20 px-6 py-4 rounded-3xl border border-emerald-100 dark:border-emerald-500/20">
        <div class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1"><?php echo t('إجمالي الإيرادات', 'Total Revenue'); ?></div>
        <div class="text-2xl font-black text-emerald-700 dark:text-emerald-300">$<?php echo number_format($total_revenue, 2); ?></div>
    </div>
</div>

<div class="grid grid-cols-1 gap-8">
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo t('الطالب', 'Student'); ?></th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo t('المبلغ', 'Amount'); ?></th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo t('الطريقة', 'Method'); ?></th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo t('الحالة', 'Status'); ?></th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]"><?php echo t('التاريخ', 'Date'); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    <?php foreach ($payments as $payment): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-8 py-5">
                                <div class="font-bold text-slate-800 dark:text-white"><?php echo htmlspecialchars($payment['user_name']); ?></div>
                                <div class="text-[10px] text-slate-400 uppercase font-black">ID: #<?php echo $payment['id']; ?></div>
                            </td>
                            <td class="px-8 py-5 font-black text-slate-700 dark:text-slate-300">
                                $<?php echo number_format($payment['amount'], 2); ?>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-500 bg-slate-100 dark:bg-slate-700 px-3 py-1 rounded-lg">
                                    <?php echo htmlspecialchars($payment['method']); ?>
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                    <?php echo $payment['status'] === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
                                    <?php echo t($payment['status'], ucfirst($payment['status'])); ?>
                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-500">
                                <?php echo date('Y-m-d H:i', strtotime($payment['created_at'])); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($payments)): ?>
                        <tr><td colspan="5" class="py-20 text-center text-slate-400 font-bold"><?php echo t('لا توجد معاملات مالية حتى الآن', 'No financial transactions yet'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
