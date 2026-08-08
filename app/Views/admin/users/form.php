<?php
/** Requires $mode ('create'|'edit'), $targetId (edit only), $permissionOptions, $errors, $form in scope. */
require __DIR__ . '/../layout-header.php';

$action = $mode === 'edit' ? url('/admin/users/' . $targetId) : url('/admin/users');
?>

<a href="<?= url('/admin/users') ?>" class="text-xs font-bold text-[#1c3d7a] hover:underline">&larr; Back to admins</a>

<form method="post" action="<?= e($action) ?>" class="max-w-xl bg-white border border-neutral-200 rounded-xl shadow-sm p-6 space-y-5 mt-4">
  <?= csrfField() ?>

  <?php foreach ($errors as $err): ?>
    <div class="bg-rose-50 border border-rose-300 text-rose-800 text-sm font-semibold rounded-lg p-3"><?= e($err) ?></div>
  <?php endforeach; ?>

  <div>
    <label class="text-[11px] font-bold text-neutral-600 uppercase">Full Name</label>
    <input type="text" name="name" value="<?= e($form['name']) ?>" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]" />
  </div>

  <div>
    <label class="text-[11px] font-bold text-neutral-600 uppercase">Email</label>
    <input type="email" name="email" required value="<?= e($form['email']) ?>" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]" />
  </div>

  <div>
    <label class="text-[11px] font-bold text-neutral-600 uppercase"><?= $mode === 'edit' ? 'New Password' : 'Password' ?></label>
    <input type="password" name="password" <?= $mode === 'create' ? 'required' : '' ?> minlength="8" placeholder="<?= $mode === 'edit' ? 'Leave blank to keep current password' : '' ?>" class="w-full mt-1 bg-white border border-neutral-300 rounded-lg p-2.5 text-sm focus:outline-none focus:border-[#1c3d7a]" />
  </div>

  <div>
    <label class="text-[11px] font-bold text-neutral-600 uppercase block mb-2">Role</label>
    <div class="space-y-2">
      <label class="flex items-start gap-3 border border-neutral-200 rounded-lg p-3 cursor-pointer has-[:checked]:border-[#1c3d7a] has-[:checked]:bg-[#f5f7fb]">
        <input type="radio" name="role" value="super_admin" class="mt-1" data-role-radio <?= $form['role'] === 'super_admin' ? 'checked' : '' ?>>
        <span>
          <span class="block text-sm font-bold text-neutral-900">Super Admin</span>
          <span class="block text-xs text-neutral-500">Equal rights to you — full access to every feature, including managing other admins.</span>
        </span>
      </label>
      <label class="flex items-start gap-3 border border-neutral-200 rounded-lg p-3 cursor-pointer has-[:checked]:border-[#1c3d7a] has-[:checked]:bg-[#f5f7fb]">
        <input type="radio" name="role" value="junior_admin" class="mt-1" data-role-radio <?= $form['role'] === 'junior_admin' ? 'checked' : '' ?>>
        <span>
          <span class="block text-sm font-bold text-neutral-900">Junior Admin</span>
          <span class="block text-xs text-neutral-500">Limited to the features you allocate below.</span>
        </span>
      </label>
    </div>
  </div>

  <div data-permissions-block class="<?= $form['role'] === 'super_admin' ? 'hidden' : '' ?>">
    <label class="text-[11px] font-bold text-neutral-600 uppercase block mb-2">Allocated Features</label>
    <div class="space-y-2">
      <?php foreach ($permissionOptions as $key => $label): ?>
        <label class="flex items-start gap-3 border border-neutral-200 rounded-lg p-3 cursor-pointer">
          <input type="checkbox" name="permissions[]" value="<?= e($key) ?>" class="mt-1" <?= in_array($key, $form['permissions'], true) ? 'checked' : '' ?>>
          <span class="text-sm text-neutral-800"><?= e($label) ?></span>
        </label>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="flex items-center gap-3 pt-3 border-t border-neutral-100">
    <button type="submit" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-6 py-3 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30">
      <?= $mode === 'edit' ? 'Save Changes' : 'Create Admin' ?>
    </button>
    <a href="<?= url('/admin/users') ?>" class="text-xs font-bold text-neutral-500 hover:text-neutral-800">Cancel</a>
  </div>
</form>

<script>
(function () {
  var radios = document.querySelectorAll('[data-role-radio]');
  var block = document.querySelector('[data-permissions-block]');
  radios.forEach(function (r) {
    r.addEventListener('change', function () {
      block.classList.toggle('hidden', r.value === 'super_admin' && r.checked);
    });
  });
})();
</script>

<?php require __DIR__ . '/../layout-footer.php'; ?>
