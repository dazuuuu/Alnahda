<?php
/** Requires $applicant, $application, $notes in scope. */
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
$a = $application;
?>

<p class="mb-6"><a href="<?= url('/portal') ?>" class="text-xs font-bold text-[#1c3d7a] hover:underline">&larr; Back to all applications</a></p>

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <div>
    <span class="text-xs font-bold text-[#1c3d7a] uppercase tracking-widest block mb-1">Application #<?= (int) $a['id'] ?></span>
    <h1 class="font-serif-heading text-3xl font-bold text-[#0f2852]"><?= e($a['preferredRole']) ?></h1>
    <p class="text-sm text-neutral-500 mt-1">Submitted <?= e(date('M j, Y g:ia', strtotime($a['submitted_at']))) ?></p>
  </div>
  <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider <?= $statusStyles[$a['status']] ?? 'bg-neutral-100 text-neutral-700' ?>">
    <?= e($statusLabels[$a['status']] ?? $a['status']) ?>
  </span>
</div>

<div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-6 mb-6">
  <h2 class="font-serif-heading text-lg font-bold text-[#0f2852] mb-4">Your Details</h2>
  <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Full Name</dt><dd class="text-neutral-800"><?= e($a['fullname']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Email</dt><dd class="text-neutral-800"><?= e($a['email']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Phone</dt><dd class="text-neutral-800"><?= e($a['phone']) ?><?= $a['phone2'] ? ' / ' . e($a['phone2']) : '' ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">County</dt><dd class="text-neutral-800"><?= e($a['county']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Age</dt><dd class="text-neutral-800"><?= (int) $a['age'] ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Gender</dt><dd class="text-neutral-800"><?= e($a['gender']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Languages</dt><dd class="text-neutral-800"><?= e($a['languages']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Preferred Appointment</dt><dd class="text-neutral-800"><?= $a['appointmentPreference'] ? e(date('M j, Y', strtotime($a['appointmentPreference']))) : '—' ?></dd></div>
  </dl>

  <h3 class="font-serif-heading text-base font-bold text-[#0f2852] mt-6 mb-3">Travel &amp; Readiness</h3>
  <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3 text-sm">
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Worked in Saudi Arabia</dt><dd class="text-neutral-800"><?= yesNo($a['travelledSaudia']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Traveled to Lebanon</dt><dd class="text-neutral-800"><?= yesNo($a['lebanon']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Traveled to Jordan</dt><dd class="text-neutral-800"><?= yesNo($a['jordan']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Medically Fit</dt><dd class="text-neutral-800"><?= yesNo($a['medicalFit']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Valid Passport</dt><dd class="text-neutral-800"><?= yesNo($a['validPassport']) ?></dd></div>
    <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Certificate of Good Conduct</dt><dd class="text-neutral-800"><?= yesNo($a['validConduct']) ?></dd></div>
  </dl>
</div>

<div class="bg-white border border-neutral-200 rounded-xl shadow-sm overflow-hidden">
  <div class="px-5 py-4 border-b border-neutral-200">
    <h2 class="font-serif-heading text-lg font-bold text-[#0f2852]">Updates From Our Team</h2>
  </div>
  <?php if (!$notes): ?>
    <p class="px-5 py-8 text-center text-sm text-neutral-400">No updates yet. Our recruiters will post messages here as your application progresses.</p>
  <?php else: ?>
    <div class="divide-y divide-neutral-100">
      <?php foreach ($notes as $note): ?>
        <div class="px-5 py-4">
          <div class="flex items-center justify-between mb-1.5">
            <span class="text-[11px] font-bold text-[#1c3d7a] uppercase tracking-wider">Al NAHDA Agency Team</span>
            <span class="text-[11px] text-neutral-400"><?= e(date('M j, Y g:ia', strtotime($note['created_at']))) ?></span>
          </div>
          <p class="text-sm text-neutral-800 whitespace-pre-line"><?= e($note['message']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/layout-footer.php'; ?>
