<?php
/** Requires $application, $notes, $statuses, $statusLabels in scope. */
require __DIR__ . '/../layout-header.php';
$a = $application;
?>

<p class="mb-6"><a href="<?= url('/admin/applications') ?>" class="text-xs font-bold text-[#1c3d7a] hover:underline">&larr; Back to applications</a></p>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 space-y-6">

    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-6">
      <h2 class="font-serif-heading text-lg font-bold text-[#0f2852] mb-4">Personal Details</h2>
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Full Name</dt><dd class="text-neutral-800"><?= e($a['fullname']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Email</dt><dd class="text-neutral-800"><a href="mailto:<?= e($a['email']) ?>" class="hover:text-[#1c3d7a]"><?= e($a['email']) ?></a></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Phone 1</dt><dd class="text-neutral-800"><a href="tel:<?= e($a['phone']) ?>" class="hover:text-[#1c3d7a]"><?= e($a['phone']) ?></a></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Phone 2</dt><dd class="text-neutral-800"><?= $a['phone2'] ? e($a['phone2']) : '—' ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">County</dt><dd class="text-neutral-800"><?= e($a['county']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Age</dt><dd class="text-neutral-800"><?= (int) $a['age'] ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Gender</dt><dd class="text-neutral-800"><?= e($a['gender']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Weight (kg)</dt><dd class="text-neutral-800"><?= e($a['weight']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Preferred Role</dt><dd class="text-neutral-800"><?= e($a['preferredRole']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Languages</dt><dd class="text-neutral-800"><?= e($a['languages']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Preferred Appointment</dt><dd class="text-neutral-800"><?= $a['appointmentPreference'] ? e(date('M j, Y', strtotime($a['appointmentPreference']))) : '—' ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Consent to Contact</dt><dd class="text-neutral-800"><?= $a['consent'] ? 'Yes' : 'No' ?></dd></div>
      </dl>
    </div>

    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-6">
      <h2 class="font-serif-heading text-lg font-bold text-[#0f2852] mb-4">Travel &amp; Work History</h2>
      <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3 text-sm">
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Worked in Saudi Arabia</dt><dd class="text-neutral-800"><?= yesNo($a['travelledSaudia']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Last Returned</dt><dd class="text-neutral-800"><?= $a['returnYear'] ? e($a['returnYear']) : '—' ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Years Worked</dt><dd class="text-neutral-800"><?= $a['durationYears'] ? e($a['durationYears']) : '—' ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Finished Contract</dt><dd class="text-neutral-800"><?= yesNo($a['finishedContract']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Issue With Sponsor</dt><dd class="text-neutral-800"><?= yesNo($a['issueWithSponsor']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Deported</dt><dd class="text-neutral-800"><?= yesNo($a['deported']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Final Exit Visa</dt><dd class="text-neutral-800"><?= yesNo($a['exitVisa']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Re-entry Visa</dt><dd class="text-neutral-800"><?= yesNo($a['reentryVisa']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Traveled to Lebanon</dt><dd class="text-neutral-800"><?= yesNo($a['lebanon']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Traveled to Jordan</dt><dd class="text-neutral-800"><?= yesNo($a['jordan']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Medically Fit</dt><dd class="text-neutral-800"><?= yesNo($a['medicalFit']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Willing to (Re)Travel</dt><dd class="text-neutral-800"><?= yesNo($a['willingToReturn']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Valid Passport</dt><dd class="text-neutral-800"><?= yesNo($a['validPassport']) ?></dd></div>
        <div><dt class="text-[11px] font-bold text-neutral-500 uppercase">Certificate of Good Conduct</dt><dd class="text-neutral-800"><?= yesNo($a['validConduct']) ?></dd></div>
      </dl>
      <?php if ($a['contractExplain']): ?>
        <div class="mt-4 bg-neutral-50 border border-neutral-200 rounded-lg p-3">
          <p class="text-[11px] font-bold text-neutral-500 uppercase mb-1">Sponsor Issue — Explanation</p>
          <p class="text-sm text-neutral-800 whitespace-pre-line"><?= e($a['contractExplain']) ?></p>
        </div>
      <?php endif; ?>
    </div>

    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-neutral-200">
        <h2 class="font-serif-heading text-lg font-bold text-[#0f2852]">Respond to Applicant</h2>
        <p class="text-xs text-neutral-500 mt-1">Notes always appear on the applicant's portal dashboard. Check the box to also email it to them right now.</p>
      </div>

      <?php if (!$notes): ?>
        <p class="px-5 py-6 text-center text-sm text-neutral-400">No notes yet.</p>
      <?php else: ?>
        <div class="divide-y divide-neutral-100 max-h-80 overflow-y-auto">
          <?php foreach ($notes as $note): ?>
            <div class="px-5 py-4">
              <div class="flex items-center justify-between mb-1.5 gap-2 flex-wrap">
                <span class="text-[11px] font-bold text-[#1c3d7a] uppercase tracking-wider"><?= e($note['admin_email'] ?? 'Al NAHDA Team') ?></span>
                <span class="flex items-center gap-2">
                  <?php if ($note['notified_at']): ?>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-emerald-100 text-emerald-700">Emailed</span>
                  <?php endif; ?>
                  <span class="text-[11px] text-neutral-400"><?= e(date('M j, Y g:ia', strtotime($note['created_at']))) ?></span>
                </span>
              </div>
              <p class="text-sm text-neutral-800 whitespace-pre-line"><?= e($note['message']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= url('/admin/applications/' . $a['id'] . '/notes') ?>" class="p-5 border-t border-neutral-200 space-y-3">
        <?= csrfField() ?>
        <textarea name="message" rows="4" required placeholder="Write an update for this applicant…" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]"></textarea>
        <div class="flex items-center justify-between">
          <label class="flex items-center gap-2 text-xs font-semibold text-neutral-600">
            <input type="checkbox" name="notify_email" value="1" checked class="rounded border-neutral-300">
            Also email this to <?= e($a['email']) ?>
          </label>
          <button type="submit" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">Send Note</button>
        </div>
      </form>
    </div>
  </div>

  <div class="space-y-6">
    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-6">
      <h2 class="font-serif-heading text-base font-bold text-[#0f2852] mb-3">Application Status</h2>
      <form method="post" action="<?= url('/admin/applications/' . $a['id'] . '/status') ?>" class="space-y-3">
        <?= csrfField() ?>
        <select name="status" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]">
          <?php foreach ($statuses as $status): ?>
            <option value="<?= e($status) ?>" <?= $a['status'] === $status ? 'selected' : '' ?>><?= e($statusLabels[$status]) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="w-full bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold py-2.5 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">Update Status</button>
      </form>
    </div>

    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-6">
      <h2 class="font-serif-heading text-base font-bold text-[#0f2852] mb-3">Contact Directly</h2>
      <div class="space-y-2">
        <a href="tel:<?= e($a['phone']) ?>" class="flex items-center justify-center gap-2 w-full bg-white border border-neutral-300 hover:border-[#1c3d7a] text-neutral-700 text-xs font-bold py-2.5 rounded-lg uppercase tracking-wider">Call <?= e($a['phone']) ?></a>
        <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/', '', (strpos($a['phone'], '0') === 0 ? '254' . substr($a['phone'], 1) : $a['phone']))) ?>" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 rounded-lg uppercase tracking-wider">WhatsApp</a>
        <a href="mailto:<?= e($a['email']) ?>" class="flex items-center justify-center gap-2 w-full bg-white border border-neutral-300 hover:border-[#1c3d7a] text-neutral-700 text-xs font-bold py-2.5 rounded-lg uppercase tracking-wider">Email <?= e($a['email']) ?></a>
      </div>
    </div>

    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-6 text-xs text-neutral-500 space-y-1.5">
      <p><span class="font-bold text-neutral-700">Application ID:</span> #<?= (int) $a['id'] ?></p>
      <p><span class="font-bold text-neutral-700">Submitted:</span> <?= e(date('M j, Y g:ia', strtotime($a['submitted_at']))) ?></p>
      <p><span class="font-bold text-neutral-700">Last Updated:</span> <?= $a['updated_at'] ? e(date('M j, Y g:ia', strtotime($a['updated_at']))) : '—' ?></p>
      <p><span class="font-bold text-neutral-700">Portal Account:</span> <?= $a['applicant_id'] ? 'Linked (#' . (int) $a['applicant_id'] . ')' : 'Not linked' ?></p>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout-footer.php'; ?>
