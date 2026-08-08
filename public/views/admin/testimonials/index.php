<?php
/** Requires $testimonials, $counts, $statusFilter in scope. */
require __DIR__ . '/../layout-header.php';

$tabs = [
    'pending' => 'Pending (' . $counts['pending'] . ')',
    'approved' => 'Approved (' . $counts['approved'] . ')',
    'rejected' => 'Rejected (' . $counts['rejected'] . ')',
    '' => 'All (' . $counts['total'] . ')',
];
?>

<div class="flex flex-wrap gap-2 mb-6">
  <?php foreach ($tabs as $key => $label): ?>
    <a href="<?= url('/admin/testimonials?status=' . urlencode($key)) ?>" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider <?= $statusFilter === $key ? 'bg-[#132c5c] text-amber-300' : 'bg-white border border-neutral-200 text-neutral-600 hover:border-[#1c3d7a]' ?>">
      <?= e($label) ?>
    </a>
  <?php endforeach; ?>
</div>

<div class="space-y-4">
  <?php if (!$testimonials): ?>
    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-10 text-center text-neutral-400">Nothing here yet.</div>
  <?php endif; ?>

  <?php foreach ($testimonials as $t): ?>
    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-5">
      <div class="flex flex-wrap items-start justify-between gap-3 mb-2">
        <div>
          <p class="font-bold text-neutral-900 text-sm"><?= e($t['author_name']) ?></p>
          <p class="text-xs text-neutral-500"><?= e($t['author_role'] ?: 'Applicant') ?> &bull; <?= e(date('M j, Y', strtotime($t['created_at']))) ?></p>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-amber-500 text-sm"><?= str_repeat('★', (int) $t['rating']) . str_repeat('☆', 5 - (int) $t['rating']) ?></span>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= [
              'pending' => 'bg-amber-100 text-amber-800',
              'approved' => 'bg-emerald-100 text-emerald-800',
              'rejected' => 'bg-rose-100 text-rose-700',
          ][$t['status']] ?? 'bg-neutral-100 text-neutral-600' ?>"><?= e($t['status']) ?></span>
        </div>
      </div>
      <p class="text-sm text-neutral-700 italic mb-4">&ldquo;<?= e($t['message']) ?>&rdquo;</p>
      <div class="flex items-center gap-2">
        <?php if ($t['status'] !== 'approved'): ?>
          <form method="post" action="<?= url('/admin/testimonials/' . $t['id'] . '/status') ?>">
            <?= csrfField() ?>
            <input type="hidden" name="status" value="approved">
            <input type="hidden" name="return_status" value="<?= e($statusFilter) ?>">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-lg cursor-pointer">Approve</button>
          </form>
        <?php endif; ?>
        <?php if ($t['status'] !== 'rejected'): ?>
          <form method="post" action="<?= url('/admin/testimonials/' . $t['id'] . '/status') ?>">
            <?= csrfField() ?>
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" name="return_status" value="<?= e($statusFilter) ?>">
            <button type="submit" class="bg-white border border-rose-300 hover:bg-rose-50 text-rose-700 text-xs font-bold px-4 py-2 rounded-lg cursor-pointer">Reject</button>
          </form>
        <?php endif; ?>
        <?php if ($t['status'] !== 'pending'): ?>
          <form method="post" action="<?= url('/admin/testimonials/' . $t['id'] . '/status') ?>">
            <?= csrfField() ?>
            <input type="hidden" name="status" value="pending">
            <input type="hidden" name="return_status" value="<?= e($statusFilter) ?>">
            <button type="submit" class="bg-white border border-neutral-300 hover:bg-neutral-50 text-neutral-600 text-xs font-bold px-4 py-2 rounded-lg cursor-pointer">Reset to Pending</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../layout-footer.php'; ?>
