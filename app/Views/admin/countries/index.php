<?php
/** Requires $countries in scope. */
require __DIR__ . '/../layout-header.php';
?>

<div class="flex items-center justify-between mb-6">
  <p class="text-sm text-neutral-500 max-w-xl">
    These appear in the "Countries We Recruit To" section on the homepage, in the order shown below.
  </p>
  <a href="<?= url('/admin/countries/create') ?>" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30 whitespace-nowrap">+ Add Country</a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
  <?php foreach ($countries as $c): ?>
    <div class="bg-white border border-neutral-200 rounded-xl shadow-sm overflow-hidden">
      <div class="h-28 bg-neutral-100 <?= $c['cover_image'] ? '' : 'flex items-center justify-center text-4xl' ?>" <?= $c['cover_image'] ? 'style="background-image:url(\'' . e(imageUrl($c['cover_image'])) . '\');background-size:cover;background-position:center;"' : '' ?>>
        <?php if (!$c['cover_image']): ?><?= \App\Models\Country::flagFallback($c['name']) ?><?php endif; ?>
      </div>
      <div class="p-4">
        <div class="flex items-center gap-2 mb-1">
          <?php if ($c['flag_image']): ?>
            <img src="<?= e(imageUrl($c['flag_image'])) ?>" alt="" class="w-6 h-4 object-cover rounded-sm border border-neutral-200" />
          <?php else: ?>
            <span class="text-base leading-none"><?= \App\Models\Country::flagFallback($c['name']) ?></span>
          <?php endif; ?>
          <p class="font-bold text-neutral-900 text-sm"><?= e($c['name']) ?></p>
          <?php if (!$c['is_active']): ?>
            <span class="ml-auto px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-neutral-100 text-neutral-500">Hidden</span>
          <?php endif; ?>
        </div>
        <p class="text-xs text-neutral-500 line-clamp-2 mb-3"><?= e($c['description'] ?: 'No description yet.') ?></p>
        <div class="flex items-center justify-between text-xs">
          <a href="<?= url('/admin/countries/' . $c['id'] . '/edit') ?>" class="font-bold text-[#1c3d7a] hover:underline">Edit</a>
          <form method="post" action="<?= url('/admin/countries/' . $c['id'] . '/delete') ?>" onsubmit="return confirm('Remove this country?');">
            <?= csrfField() ?>
            <button type="submit" class="font-bold text-rose-600 hover:underline cursor-pointer">Remove</button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (!$countries): ?>
    <p class="text-sm text-neutral-400 col-span-full text-center py-10">No countries added yet.</p>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout-footer.php'; ?>
