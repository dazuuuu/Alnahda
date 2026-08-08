<?php
/**
 * Shared admin shell (sidebar + topbar). Include after setting:
 *   $pageTitle  — shown in <title> and the topbar
 *   $activeNav  — one of: dashboard, applications, countries, testimonials, admin-users
 * Requires App\Core\AdminSession::require() to have already run.
 */

use App\Core\AdminSession;
use App\Models\Admin;

$admin = AdminSession::current();

$navItems = [
    ['id' => 'dashboard', 'href' => url('/admin'), 'label' => 'Dashboard'],
];
if (Admin::hasPermission($admin, 'applications')) {
    $navItems[] = ['id' => 'applications', 'href' => url('/admin/applications'), 'label' => 'Applications'];
}
if (Admin::hasPermission($admin, 'testimonials')) {
    $navItems[] = ['id' => 'testimonials', 'href' => url('/admin/testimonials'), 'label' => 'Testimonials'];
}
if (Admin::hasPermission($admin, 'countries')) {
    $navItems[] = ['id' => 'countries', 'href' => url('/admin/countries'), 'label' => 'Countries'];
}
if (Admin::isSuperAdmin($admin)) {
    $navItems[] = ['id' => 'admin-users', 'href' => url('/admin/users'), 'label' => 'Manage Admins'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle ?? 'Admin') ?> | Al NAHDA Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= versionedAsset('assets/css/tailwind.css') ?>">
  <link rel="stylesheet" href="<?= versionedAsset('assets/css/app.css') ?>">
  <link rel="icon" href="<?= asset('assets/site/img/logo-no-bg.png') ?>" type="image/png">
</head>
<body class="bg-[#f5f7fb] text-[#1a1a1a] antialiased min-h-screen flex flex-col lg:flex-row">

  <!-- Mobile top bar (sidebar takes over at lg:) -->
  <header class="lg:hidden bg-[#132c5c] text-white border-b border-[#24447f]">
    <div class="flex items-center gap-2.5 px-4 py-3">
      <img src="<?= asset('assets/site/img/logo-no-bg.png') ?>" alt="" class="w-7 h-7 object-contain shrink-0" />
      <div class="flex flex-col leading-none flex-1 min-w-0">
        <span class="font-serif-heading text-xs font-extrabold tracking-[0.15em] uppercase">AL NAHDA</span>
        <span class="text-[7px] tracking-[0.2em] text-amber-300/80 uppercase mt-0.5">Admin Panel</span>
      </div>
      <a href="<?= url('/admin/logout') ?>" class="text-[10px] font-bold uppercase text-rose-300 hover:text-rose-200 shrink-0">Sign out</a>
    </div>
    <div class="px-4 pb-2">
      <h1 class="font-serif-heading text-lg font-bold"><?= e($pageTitle ?? '') ?></h1>
    </div>
    <div class="px-4 pb-3">
      <select onchange="if(this.value) window.location.href=this.value;" class="w-full bg-[#0f2038] border border-amber-500/30 text-white text-xs font-bold uppercase tracking-wider rounded-lg px-3 py-2.5">
        <?php foreach ($navItems as $item): $active = ($activeNav ?? '') === $item['id']; ?>
          <option value="<?= e($item['href']) ?>" <?= $active ? 'selected' : '' ?>><?= e($item['label']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </header>

  <!-- Sidebar (lg: and up) -->
  <aside class="hidden lg:flex w-60 shrink-0 bg-[#132c5c] text-white min-h-screen flex-col justify-between border-r border-[#24447f] sticky top-0 self-start h-screen overflow-y-auto">
    <div>
      <div class="flex items-center gap-2.5 px-5 py-5 border-b border-[#24447f]">
        <img src="<?= asset('assets/site/img/logo-no-bg.png') ?>" alt="" class="w-8 h-8 object-contain shrink-0" />
        <div class="flex flex-col leading-none">
          <span class="font-serif-heading text-sm font-extrabold tracking-[0.15em] uppercase">AL NAHDA</span>
          <span class="text-[8px] tracking-[0.25em] text-amber-300/80 uppercase mt-0.5">Admin Panel</span>
        </div>
      </div>

      <nav class="p-3 space-y-1 text-xs font-semibold uppercase tracking-wider">
        <?php foreach ($navItems as $item): $active = ($activeNav ?? '') === $item['id']; ?>
          <a href="<?= e($item['href']) ?>" class="block px-3 py-2.5 rounded-lg transition-colors <?= $active ? 'bg-amber-400 text-black font-bold' : 'text-amber-100/80 hover:bg-[#1c3d7a] hover:text-white' ?>">
            <?= e($item['label']) ?>
          </a>
        <?php endforeach; ?>
      </nav>
    </div>

    <div class="p-4 border-t border-[#24447f] text-xs">
      <p class="text-amber-200/60">Signed in as</p>
      <p class="font-bold text-amber-300 truncate"><?= e($admin['name'] ?: $admin['email'] ?? '') ?></p>
      <p class="text-[9px] font-bold uppercase tracking-wider text-amber-200/50 mb-2"><?= Admin::isSuperAdmin($admin) ? 'Super Admin' : 'Junior Admin' ?></p>
      <div class="flex flex-col gap-1.5">
        <a href="<?= url('/') ?>" target="_blank" class="text-amber-100/70 hover:text-white">View website &rarr;</a>
        <a href="<?= url('/admin/logout') ?>" class="text-rose-300 hover:text-rose-200">Sign out</a>
      </div>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 min-w-0">
    <header class="hidden lg:block bg-white border-b border-neutral-200 px-6 py-4 sticky top-0 z-10">
      <h1 class="font-serif-heading text-xl font-bold text-[#0f2852]"><?= e($pageTitle ?? '') ?></h1>
    </header>
    <main class="p-4 lg:p-6">
      <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="mb-5 bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm font-semibold rounded-lg p-3">
          <?= e($_SESSION['flash_success']) ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
      <?php endif; ?>
      <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="mb-5 bg-rose-50 border border-rose-300 text-rose-800 text-sm font-semibold rounded-lg p-3">
          <?= e($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
      <?php endif; ?>
