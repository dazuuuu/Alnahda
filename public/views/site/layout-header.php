<?php
/**
 * Shared <head> + site header for the public marketing site. Requires
 * $pageTitle and $active ('home' | 'apply') in scope.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= versionedAsset('assets/site/css/styles.css') ?>">
    <?php if ($active === 'apply'): ?>
    <link rel="stylesheet" href="<?= versionedAsset('assets/site/css/apply.css') ?>">
    <?php endif; ?>
    <link rel="icon" href="<?= asset('assets/site/img/logo-no-bg.png') ?>" type="image/png">
</head>
<body>
    <header class="site-header">
        <div class="container nav-container">
            <a class="brand" href="<?= url('/') ?>" aria-label="Al NAHDA Agency home">
                <img src="<?= asset('assets/site/img/logo-no-bg.png') ?>" alt="Al NAHDA Agency logo" class="brand-logo">
                <div>
                    <div class="brand-name">Al NAHDA AGENCY</div>
                    <div class="brand-tagline">Recruitment Excellence</div>
                </div>
            </a>
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">
                <span class="nav-toggle-bar"></span>
                <span class="nav-toggle-bar"></span>
                <span class="nav-toggle-bar"></span>
                <span class="sr-only">Toggle navigation</span>
            </button>
            <?php if ($active === 'home'): ?>
            <nav id="primary-nav" class="site-nav" aria-label="Primary navigation">
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#countries">Countries</a>
                <a href="#why">Why Us</a>
                <a href="<?= url('/portal/login') ?>">Track Record</a>
                <a href="#testimonials">Testimonials</a>
                <a href="<?= url('/apply') ?>" class="btn btn-small">Apply</a>
            </nav>
            <?php else: ?>
            <nav id="primary-nav" class="site-nav" aria-label="Primary navigation">
                <a href="<?= url('/') ?>">Home</a>
                <a href="<?= url('/') . '#services' ?>">Services</a>
                <a href="<?= url('/testimonials') ?>">Testimonials</a>
                <a href="<?= url('/portal/login') ?>">Track Record</a>
                <?php if ($active !== 'apply'): ?>
                    <a href="<?= url('/apply') ?>" class="btn btn-small">Apply</a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>
        </div>
    </header>
