<?php
/** Requires $applicant, $applications, $noteCounts in scope. */
require __DIR__ . '/layout-header.php';

$statusStyles = [
    'pending' => 'bg-amber-100 text-amber-800',
    'reviewing' => 'bg-blue-100 text-blue-800',
    'shortlisted' => 'bg-indigo-100 text-indigo-800',
    'interview' => 'bg-purple-100 text-purple-800',
    'approved' => 'bg-emerald-100 text-emerald-800',
    'placed' => 'bg-emerald-600 text-white',
    'rejected' => 'bg-rose-100 text-rose-700',
];
$statusLabels = \App\Models\Application::STATUS_LABELS;
?>

<div class="mb-8">
  <span class="text-xs font-bold text-[#1c3d7a] uppercase tracking-widest block mb-1">Applicant Portal</span>
  <h1 class="font-serif-heading text-3xl font-bold text-[#0f2852]">Your Applications</h1>
  <p class="text-sm text-neutral-500 mt-2">
    Signed in as <strong class="text-neutral-800"><?= e($applicant['email'] ?: $applicant['phone']) ?></strong>
  </p>
</div>

<?php if (!$applications): ?>
  <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-10 text-center">
    <p class="font-serif-heading text-lg font-bold text-neutral-900 mb-2">No applications yet</p>
    <p class="text-sm text-neutral-500 mb-6">Once you submit an application, it will show up here for tracking.</p>
    <a href="<?= url('/apply') ?>" class="inline-block bg-[#132c5c] text-amber-300 text-xs font-bold px-6 py-3 rounded-lg uppercase tracking-widest hover:bg-[#1c3d7a] transition-colors border border-amber-400/30">Apply Now</a>
  </div>
<?php endif; ?>

<div class="space-y-5">
  <?php foreach ($applications as $app): ?>
    <a href="<?= url('/portal/applications/' . $app['id']) ?>" class="block bg-white border border-neutral-200 rounded-xl shadow-sm overflow-hidden hover:border-[#1c3d7a] transition-colors">
      <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-2">
        <div>
          <p class="font-mono font-bold text-sm text-neutral-900">Application #<?= (int) $app['id'] ?> &middot; <?= e($app['preferredRole']) ?></p>
          <p class="text-[11px] text-neutral-400"><?= e(date('M j, Y g:ia', strtotime($app['submitted_at']))) ?></p>
        </div>
        <div class="flex items-center gap-2">
          <?php if (($noteCounts[$app['id']] ?? 0) > 0): ?>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-[#1c3d7a] text-white"><?= (int) $noteCounts[$app['id']] ?> update<?= $noteCounts[$app['id']] > 1 ? 's' : '' ?></span>
          <?php endif; ?>
          <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $statusStyles[$app['status']] ?? 'bg-neutral-100 text-neutral-700' ?>">
            <?= e($statusLabels[$app['status']] ?? $app['status']) ?>
          </span>
        </div>
      </div>
    </a>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/layout-footer.php'; ?>
