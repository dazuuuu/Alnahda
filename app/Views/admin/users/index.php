<?php
/** Requires $admins in scope. Super-admin-only page. */
require __DIR__ . '/../layout-header.php';
?>

<div class="flex items-center justify-between mb-6">
  <p class="text-sm text-neutral-500 max-w-xl">
    Super admins have full, unrestricted access. Junior admins only see the features you allocate to them below.
  </p>
  <a href="<?= url('/admin/users/create') ?>" class="bg-[#132c5c] hover:bg-[#1c3d7a] text-amber-300 text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-widest transition-colors cursor-pointer border border-amber-400/30 whitespace-nowrap">+ Add Admin</a>
</div>

<div class="bg-white border border-neutral-200 rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-neutral-50 text-neutral-500 uppercase tracking-wider text-[10px]">
        <tr>
          <th class="px-5 py-3">Admin</th>
          <th class="px-5 py-3">Role</th>
          <th class="px-5 py-3">Access</th>
          <th class="px-5 py-3">Added</th>
          <th class="px-5 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-neutral-100">
        <?php foreach ($admins as $a): ?>
          <tr class="hover:bg-neutral-50">
            <td class="px-5 py-3">
              <p class="font-bold text-neutral-900"><?= e($a['name'] ?: $a['email']) ?></p>
              <p class="text-neutral-400"><?= e($a['email']) ?></p>
            </td>
            <td class="px-5 py-3">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $a['role'] === 'super_admin' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' ?>">
                <?= $a['role'] === 'super_admin' ? 'Super Admin' : 'Junior Admin' ?>
              </span>
            </td>
            <td class="px-5 py-3 text-neutral-600">
              <?php if ($a['role'] === 'super_admin'): ?>
                Full access
              <?php elseif ($a['permissions']): ?>
                <?= e(implode(', ', array_map(fn($k) => \App\Models\Admin::PERMISSIONS[$k] ?? $k, $a['permissions']))) ?>
              <?php else: ?>
                <span class="text-neutral-400">No features allocated yet</span>
              <?php endif; ?>
            </td>
            <td class="px-5 py-3 text-neutral-500"><?= e(date('M j, Y', strtotime($a['created_at']))) ?></td>
            <td class="px-5 py-3 text-right whitespace-nowrap">
              <a href="<?= url('/admin/users/' . $a['id'] . '/edit') ?>" class="font-bold text-[#1c3d7a] hover:underline mr-3">Edit</a>
              <?php if ((int) $a['id'] !== (int) $admin['id']): ?>
                <form method="post" action="<?= url('/admin/users/' . $a['id'] . '/delete') ?>" class="inline" onsubmit="return confirm('Remove this admin account?');">
                  <?= csrfField() ?>
                  <button type="submit" class="font-bold text-rose-600 hover:underline cursor-pointer">Remove</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout-footer.php'; ?>
