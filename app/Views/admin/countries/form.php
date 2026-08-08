<?php
/** Requires $mode ('create'|'edit'), $country (edit only), $errors, $form in scope. */
require __DIR__ . '/../layout-header.php';

$action = $mode === 'edit' ? url('/admin/countries/' . $country['id']) : url('/admin/countries');
?>

<a href="<?= url('/admin/countries') ?>" class="text-xs font-bold text-[#1c3d7a] hover:underline">&larr; Back to countries</a>

<form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="max-w-xl bg-white border border-neutral-200 rounded-xl shadow-sm p-6 space-y-5 mt-4">
  <?= csrfField() ?>

  <?php foreach ($errors as $err): ?>
    <div class="bg-rose-50 border border-rose-300 text-rose-800 text-sm font-semibold rounded-lg p-3"><?= e($err) ?></div>
  <?php endforeach; ?>

  <div>
    <label class="text-[11px] font-bold text-neutral-600 uppercase">Country Name</label>
    <input type="text" name="name" required value="<?= e($form['name']) ?>" placeholder="e.g. Saudi Arabia" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]" />
  </div>

  <div>
    <label class="text-[11px] font-bold text-neutral-600 uppercase">Short Description</label>
    <textarea name="description" rows="3" placeholder="One or two sentences about placements in this country…" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]"><?= e($form['description']) ?></textarea>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-[11px] font-bold text-neutral-600 uppercase">Flag Image</label>
      <?php if ($country && $country['flag_image']): ?>
        <img src="<?= e(imageUrl($country['flag_image'])) ?>" alt="" class="w-16 h-10 object-cover rounded border border-neutral-200 mt-2 mb-2" />
      <?php endif; ?>
      <input type="file" name="flag_image" accept="image/*" class="w-full mt-1 text-xs" />
      <p class="text-[10px] text-neutral-400 mt-1">Falls back to an emoji flag if left empty.</p>
    </div>
    <div>
      <label class="text-[11px] font-bold text-neutral-600 uppercase">Cover Image</label>
      <?php if ($country && $country['cover_image']): ?>
        <img src="<?= e(imageUrl($country['cover_image'])) ?>" alt="" class="w-full h-16 object-cover rounded border border-neutral-200 mt-2 mb-2" />
      <?php endif; ?>
      <input type="file" name="cover_image" accept="image/*" class="w-full mt-1 text-xs" />
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-[11px] font-bold text-neutral-600 uppercase">Sort Order</label>
      <input type="number" name="sort_order" value="<?= e($form['sort_order']) ?>" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]" />
      <p class="text-[10px] text-neutral-400 mt-1">Lower numbers show first.</p>
    </div>
    <div class="flex items-center pt-6">
      <label class="flex items-center gap-2 text-sm font-semibold text-neutral-700">
        <input type="checkbox" name="is_active" value="1" <?= $form['is_active'] ? 'checked' : '' ?> class="rounded border-neutral-300">
        Visible on website
      </label>
    </div>
  </div>

  <div class="flex items-center gap-3 pt-3 border-t border-neutral-100">
    <button type="submit" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-6 py-3 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">
      <?= $mode === 'edit' ? 'Save Changes' : 'Add Country' ?>
    </button>
    <a href="<?= url('/admin/countries') ?>" class="text-xs font-bold text-neutral-500 hover:text-neutral-800">Cancel</a>
  </div>
</form>

<?php require __DIR__ . '/../layout-footer.php'; ?>
