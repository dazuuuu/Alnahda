<?php
/**
 * Shared applicant-portal shell. Include after setting $pageTitle.
 * Visually distinct from the public site (Tailwind, like the admin panel)
 * but in Al NAHDA Agency's navy/gold brand colors.
 */
use App\Core\ApplicantSession;
$loggedInApplicant = ApplicantSession::current();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle ?? 'My Applications') ?> | Al NAHDA Agency</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= versionedAsset('assets/css/tailwind.css') ?>">
  <link rel="stylesheet" href="<?= versionedAsset('assets/css/app.css') ?>">
  <link rel="icon" href="<?= asset('assets/site/img/logo-no-bg.png') ?>" type="image/png">
</head>
<body class="bg-[#f5f7fb] text-[#273140] antialiased min-h-screen flex flex-col">

  <header class="w-full bg-[#132c5c] py-3 sm:py-4 border-b border-[#24447f] text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 flex items-center justify-between">
      <a href="<?= url('/') ?>" class="inline-flex items-center gap-2 group">
        <img src="<?= asset('assets/site/img/logo-no-bg.png') ?>" alt="Al NAHDA Agency" class="w-8 h-8 sm:w-9 sm:h-9 object-contain shrink-0" />
        <div class="flex flex-col text-left leading-none">
          <span class="font-serif-heading text-base sm:text-lg font-extrabold tracking-[0.18em] text-white uppercase">AL NAHDA</span>
          <span class="text-[8px] tracking-[0.3em] text-amber-300/90 font-sans font-semibold uppercase mt-0.5">Applicant Portal</span>
        </div>
      </a>
      <nav class="flex items-center gap-3 sm:gap-4 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-amber-100/90">
        <?php if ($loggedInApplicant): ?>
          <a href="<?= url('/portal') ?>" class="hover:text-amber-300">My Applications</a>
          <a href="<?= url('/portal/testimonial') ?>" class="hover:text-amber-300">Testimonial</a>
          <a href="<?= url('/portal/logout') ?>" class="hover:text-amber-300">Sign Out</a>
        <?php else: ?>
          <a href="<?= url('/portal/login') ?>" class="hover:text-amber-300">Track Application</a>
        <?php endif; ?>
        <a href="<?= url('/') ?>" class="hover:text-amber-300">Website &rarr;</a>
      </nav>
    </div>
  </header>

  <main class="flex-1 w-full max-w-3xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm font-semibold rounded-lg p-3">
        <?= e($_SESSION['flash_success']) ?>
      </div>
      <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="mb-6 bg-rose-50 border border-rose-300 text-rose-800 text-sm font-semibold rounded-lg p-3">
        <?= e($_SESSION['flash_error']) ?>
      </div>
      <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
