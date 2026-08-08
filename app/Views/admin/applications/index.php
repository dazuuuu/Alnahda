<?php
/** Requires $applications, $noteCounts, $statuses, $statusLabels, $filters in scope. */
require __DIR__ . '/../layout-header.php';

$statusStyles = [
    'pending' => 'bg-amber-100 text-amber-800',
    'reviewing' => 'bg-blue-100 text-blue-800',
    'shortlisted' => 'bg-indigo-100 text-indigo-800',
    'interview' => 'bg-purple-100 text-purple-800',
    'approved' => 'bg-emerald-100 text-emerald-800',
    'placed' => 'bg-emerald-600 text-white',
    'rejected' => 'bg-rose-100 text-rose-700',
];
?>

<p class="text-sm text-neutral-500 mb-6 max-w-2xl">
  Click <strong>Review &amp; Respond</strong> on any applicant to change their status, and send a note or email — every note also appears on their portal dashboard.
</p>

<form method="get" action="<?= url('/admin/applications') ?>" class="bg-white border border-neutral-200 rounded-xl shadow-sm p-4 mb-6 flex flex-wrap items-end gap-3">
  <div class="flex-1 min-w-[200px]">
    <label class="text-[11px] font-bold text-neutral-500 uppercase">Search</label>
    <input type="text" name="q" value="<?= e($filters['search']) ?>" placeholder="Name, email or phone" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]" />
  </div>
  <div>
    <label class="text-[11px] font-bold text-neutral-500 uppercase">Status</label>
    <select name="status" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]">
      <option value="">All statuses</option>
      <?php foreach ($statuses as $status): ?>
        <option value="<?= e($status) ?>" <?= $filters['status'] === $status ? 'selected' : '' ?>><?= e($statusLabels[$status]) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">Filter</button>
  <?php if ($filters['search'] || $filters['status']): ?>
    <a href="<?= url('/admin/applications') ?>" class="text-xs font-bold text-neutral-500 hover:text-neutral-800">Clear</a>
  <?php endif; ?>
</form>

<div class="bg-white border border-neutral-200 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-neutral-50 text-neutral-500 uppercase tracking-wider text-[10px]">
        <tr>
          <th class="px-5 py-3">Applicant</th>
          <th class="px-5 py-3">Contact</th>
          <th class="px-5 py-3">Role</th>
          <th class="px-5 py-3">County</th>
          <th class="px-5 py-3">Status</th>
          <th class="px-5 py-3">Notes</th>
          <th class="px-5 py-3">Submitted</th>
          <th class="px-5 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-neutral-100">
        <?php if (!$applications): ?>
          <tr><td colspan="8" class="px-5 py-8 text-center text-neutral-400">No applications match this filter.</td></tr>
        <?php endif; ?>
        <?php foreach ($applications as $app): $count = $noteCounts[$app['id']] ?? 0; ?>
          <tr class="hover:bg-neutral-50">
            <td class="px-5 py-3 font-bold text-neutral-900"><?= e($app['fullname']) ?></td>
            <td class="px-5 py-3 text-neutral-500"><?= e($app['email']) ?><br><?= e($app['phone']) ?></td>
            <td class="px-5 py-3"><?= e($app['preferredRole']) ?></td>
            <td class="px-5 py-3"><?= e($app['county']) ?></td>
            <td class="px-5 py-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $statusStyles[$app['status']] ?? 'bg-neutral-100 text-neutral-700' ?>"><?= e($statusLabels[$app['status']] ?? $app['status']) ?></span></td>
            <td class="px-5 py-3">
              <?php if ($count > 0): ?>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#132c5c] text-white"><?= $count ?> sent</span>
              <?php else: ?>
                <span class="text-neutral-400">None yet</span>
              <?php endif; ?>
            </td>
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

<?php require __DIR__ . '/../layout-footer.php'; ?>
