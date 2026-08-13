<?php
/** Requires $applications, $statuses, $statusLabels, $counties, $filters in scope. */
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

$filterParams = array_filter([
    'status' => $filters['status'] ?? '',
    'county' => $filters['county'] ?? '',
], fn($value) => $value !== '' && $value !== null);
$hasFilters = $filterParams !== [];
$excelUrl = url('/admin/reports/export') . '?' . http_build_query($filterParams + ['format' => 'excel']);
$pdfUrl = url('/admin/reports/export') . '?' . http_build_query($filterParams + ['format' => 'pdf']);
$allExcelUrl = url('/admin/reports/export') . '?' . http_build_query(['format' => 'excel', 'scope' => 'all']);
$allPdfUrl = url('/admin/reports/export') . '?' . http_build_query(['format' => 'pdf', 'scope' => 'all']);
$reportFields = \App\Models\Application::REPORT_FIELDS;
$total = count($applications);
?>

<p class="text-sm text-neutral-500 mb-6 max-w-2xl">
  Download the full applicant report, or filter by county and status first. Excel and PDF both include the same fields shown below.
</p>

<form method="get" action="<?= url('/admin/reports') ?>" class="bg-white border border-neutral-200 rounded-xl shadow-sm p-4 mb-6 flex flex-wrap items-end gap-3">
  <div>
    <label class="text-[11px] font-bold text-neutral-500 uppercase">County</label>
    <select name="county" class="w-full mt-1 min-w-[180px] bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]">
      <option value="">All counties</option>
      <?php foreach ($counties as $county): ?>
        <option value="<?= e($county) ?>" <?= ($filters['county'] ?? '') === $county ? 'selected' : '' ?>><?= e($county) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label class="text-[11px] font-bold text-neutral-500 uppercase">Status</label>
    <select name="status" class="w-full mt-1 min-w-[180px] bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]">
      <option value="">All statuses</option>
      <?php foreach ($statuses as $status): ?>
        <option value="<?= e($status) ?>" <?= ($filters['status'] ?? '') === $status ? 'selected' : '' ?>><?= e($statusLabels[$status]) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">Filter</button>
  <?php if ($hasFilters): ?>
    <a href="<?= url('/admin/reports') ?>" class="text-xs font-bold text-neutral-500 hover:text-neutral-800">Clear</a>
  <?php endif; ?>
</form>

<div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-4 mb-6 flex flex-wrap items-center justify-between gap-3">
  <p class="text-sm text-neutral-700">
    <span class="font-bold text-[#0f2852]"><?= (int) $total ?></span>
    <?= $total === 1 ? 'application' : 'applications' ?>
    <?php if ($hasFilters): ?>
      matching this filter
      <?php if (!empty($filters['county'])): ?>
        &middot; <span class="font-semibold"><?= e($filters['county']) ?></span>
      <?php endif; ?>
      <?php if (!empty($filters['status'])): ?>
        &middot; <span class="font-semibold"><?= e($statusLabels[$filters['status']] ?? $filters['status']) ?></span>
      <?php endif; ?>
    <?php else: ?>
      in the full report
    <?php endif; ?>
  </p>
  <div class="flex flex-wrap gap-2">
    <a href="<?= e($excelUrl) ?>" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors border border-amber-400/30"><?= $hasFilters ? 'Download filtered Excel' : 'Download Excel' ?></a>
    <a href="<?= e($pdfUrl) ?>" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors border border-amber-400/30"><?= $hasFilters ? 'Download filtered PDF' : 'Download PDF' ?></a>
    <?php if ($hasFilters): ?>
      <a href="<?= e($allExcelUrl) ?>" class="bg-white hover:border-[#1c3d7a] text-neutral-700 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors border border-neutral-300">Download entire Excel</a>
      <a href="<?= e($allPdfUrl) ?>" class="bg-white hover:border-[#1c3d7a] text-neutral-700 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors border border-neutral-300">Download entire PDF</a>
    <?php endif; ?>
  </div>
</div>

<div class="bg-white border border-neutral-200 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-neutral-50 text-neutral-500 uppercase tracking-wider text-[10px]">
        <tr>
          <?php foreach ($reportFields as $label): ?>
            <th class="px-5 py-3"><?= e($label) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody class="divide-y divide-neutral-100">
        <?php if (!$applications): ?>
          <tr><td colspan="<?= count($reportFields) ?>" class="px-5 py-8 text-center text-neutral-400">No applications match this filter.</td></tr>
        <?php endif; ?>
        <?php foreach ($applications as $app): ?>
          <tr class="hover:bg-neutral-50">
            <?php foreach (array_keys($reportFields) as $field): ?>
              <?php if ($field === 'status'): ?>
                <td class="px-5 py-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $statusStyles[$app['status']] ?? 'bg-neutral-100 text-neutral-700' ?>"><?= e(\App\Models\Application::formatPublicValue($app, $field)) ?></span></td>
              <?php else: ?>
                <td class="px-5 py-3 <?= $field === 'fullname' ? 'font-bold text-neutral-900' : 'text-neutral-500' ?>"><?= e(\App\Models\Application::formatPublicValue($app, $field)) ?></td>
              <?php endif; ?>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout-footer.php'; ?>
