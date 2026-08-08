<?php
/** Requires $applicant, $testimonials, $error in scope. */
require __DIR__ . '/layout-header.php';

$statusStyles = [
    'pending' => 'bg-amber-100 text-amber-800',
    'approved' => 'bg-emerald-100 text-emerald-800',
    'rejected' => 'bg-rose-100 text-rose-700',
];
?>

<div class="mb-8">
  <span class="text-xs font-bold text-[#1c3d7a] uppercase tracking-widest block mb-1">Applicant Portal</span>
  <h1 class="font-serif-heading text-3xl font-bold text-[#0f2852]">Leave a Testimonial</h1>
  <p class="text-sm text-neutral-500 mt-2">Share your experience with Al NAHDA Agency. Approved testimonials appear on our website.</p>
</div>

<div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-6 mb-8">
  <?php if ($error): ?>
    <div class="bg-rose-50 border border-rose-300 text-rose-800 text-sm rounded-lg p-3 mb-4"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= url('/portal/testimonial') ?>" class="space-y-4">
    <?= csrfField() ?>
    <div>
      <label class="text-[11px] font-bold text-neutral-600 uppercase">Your Role / Placement <span class="text-neutral-400 font-normal normal-case">(optional)</span></label>
      <input type="text" name="author_role" placeholder="e.g. Housemaid, placed in Dubai" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]" />
    </div>
    <div>
      <label class="text-[11px] font-bold text-neutral-600 uppercase block mb-1">Rating</label>
      <div class="star-rating">
        <?php for ($i = 5; $i >= 1; $i--): ?>
          <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
          <label for="star<?= $i ?>">★</label>
        <?php endfor; ?>
      </div>
    </div>
    <div>
      <label class="text-[11px] font-bold text-neutral-600 uppercase">Your Testimonial</label>
      <textarea name="message" rows="4" required placeholder="Tell us about your experience…" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]"></textarea>
    </div>
    <button type="submit" class="w-full bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold py-3 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">Submit Testimonial</button>
  </form>
</div>

<?php if ($testimonials): ?>
  <h2 class="font-serif-heading text-lg font-bold text-[#0f2852] mb-3">Your Submissions</h2>
  <div class="space-y-3">
    <?php foreach ($testimonials as $t): ?>
      <div class="bg-white border border-neutral-200 rounded-xl shadow-sm p-4">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-amber-500 text-sm"><?= str_repeat('★', (int) $t['rating']) . str_repeat('☆', 5 - (int) $t['rating']) ?></span>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $statusStyles[$t['status']] ?? 'bg-neutral-100 text-neutral-600' ?>"><?= e($t['status']) ?></span>
        </div>
        <p class="text-sm text-neutral-700 italic">&ldquo;<?= e($t['message']) ?>&rdquo;</p>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<style>
/* Pure-CSS 1-5 star picker: radios are laid out 5..1 then flipped visually
   with row-reverse, so ~ (general sibling) lights up every star at or below
   the checked/hovered one. */
.star-rating {
  display: flex;
  flex-direction: row-reverse;
  justify-content: flex-end;
  gap: 0.15rem;
  width: fit-content;
}
.star-rating input {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}
.star-rating label {
  font-size: 1.6rem;
  line-height: 1;
  color: #d4d4d8;
  cursor: pointer;
  transition: color 0.15s ease;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
  color: #f59e0b;
}
</style>

<?php require __DIR__ . '/layout-footer.php'; ?>
