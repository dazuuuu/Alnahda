<?php
/** Requires $stats, $statusCounts, $recentApplications in scope. */
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
$canSeeApplications = \App\Models\Admin::hasPermission(\App\Core\AdminSession::current(), 'applications');
?>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <?php
  $cards = [
      ['label' => 'Total Applications', 'value' => $stats['total'], 'href' => url('/admin/applications')],
      ['label' => 'This Week', 'value' => $stats['thisWeek'], 'href' => url('/admin/applications')],
      ['label' => 'Pending Review', 'value' => $stats['pending'], 'href' => url('/admin/applications?status=pending')],
      ['label' => 'Shortlisted', 'value' => $stats['shortlisted'], 'href' => url('/admin/applications?status=shortlisted')],
      ['label' => 'Interview Scheduled', 'value' => $stats['interview'], 'href' => url('/admin/applications?status=interview')],
      ['label' => 'Placed', 'value' => $stats['placed'], 'href' => url('/admin/applications?status=placed')],
      ['label' => 'Portal Accounts', 'value' => $stats['applicants'], 'href' => null],
  ];
  if (!$canSeeApplications) {
      foreach ($cards as &$c) {
          $c['href'] = null;
      }
      unset($c);
  }
  foreach ($cards as $s): ?>
    <a href="<?= $s['href'] ? e($s['href']) : '#' ?>" class="bg-white border border-neutral-200 rounded-xl p-5 shadow-sm hover:border-amber-400 transition-colors <?= $s['href'] ? '' : 'pointer-events-none' ?>">
      <p class="text-[11px] font-bold text-neutral-500 uppercase tracking-widest"><?= e($s['label']) ?></p>
      <p class="font-serif-heading text-3xl font-bold text-[#0f2852] mt-1"><?= (int) $s['value'] ?></p>
    </a>
  <?php endforeach; ?>
</div>

<?php if ($canSeeApplications): ?>
<div class="bg-white border border-neutral-200 rounded-xl shadow-sm overflow-hidden">
  <div class="px-5 py-4 border-b border-neutral-200 flex items-center justify-between">
    <h2 class="font-serif-heading text-lg font-bold text-[#0f2852]">Recent Applications</h2>
    <a href="<?= url('/admin/applications') ?>" class="text-xs font-bold text-[#1c3d7a] hover:underline">View all &rarr;</a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-neutral-50 text-neutral-500 uppercase tracking-wider text-[10px]">
        <tr>
          <th class="px-5 py-3">Applicant</th>
          <th class="px-5 py-3">Role</th>
          <th class="px-5 py-3">County</th>
          <th class="px-5 py-3">Status</th>
          <th class="px-5 py-3">Submitted</th>
          <th class="px-5 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-neutral-100">
        <?php if (!$recentApplications): ?>
          <tr><td colspan="6" class="px-5 py-8 text-center text-neutral-400">No applications submitted yet.</td></tr>
        <?php endif; ?>
        <?php foreach ($recentApplications as $app): ?>
          <tr class="hover:bg-neutral-50">
            <td class="px-5 py-3 font-bold text-neutral-900"><?= e($app['fullname']) ?></td>
            <td class="px-5 py-3"><?= e($app['preferredRole']) ?></td>
            <td class="px-5 py-3"><?= e($app['county']) ?></td>
            <td class="px-5 py-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $statusStyles[$app['status']] ?? 'bg-neutral-100 text-neutral-700' ?>"><?= e($statusLabels[$app['status']] ?? $app['status']) ?></span></td>
            <td class="px-5 py-3 text-neutral-500"><?= e(date('M j, Y', strtotime($app['submitted_at']))) ?></td>
            <td class="px-5 py-3 text-right">
              <a href="<?= url('/admin/applications/' . $app['id']) ?>" class="inline-block bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-[10px] font-bold px-3 py-2 rounded-lg uppercase tracking-wider whitespace-nowrap">Respond</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/layout-footer.php'; ?>
