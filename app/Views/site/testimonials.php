    <main>
        <section class="section" id="testimonials-page">
            <div class="container">
                <div class="application-header">
                    <h2>What Our Applicants Say</h2>
                    <p>Every testimonial below was submitted by a real applicant through their Al NAHDA Agency dashboard and approved by our team before publishing.</p>
                </div>

                <?php if ($testimonials): ?>
                <div class="card-grid">
                    <?php foreach ($testimonials as $t): ?>
                        <article class="testimonial-card">
                            <span class="testimonial-stars" aria-hidden="true"><?= str_repeat('&#9733;', (int) $t['rating']) . str_repeat('&#9734;', 5 - (int) $t['rating']) ?></span>
                            <p>&ldquo;<?= e($t['message']) ?>&rdquo;</p>
                            <h3><?= e($t['author_name']) ?></h3>
                            <?php if ($t['author_role']): ?><span><?= e($t['author_role']) ?></span><?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <p style="text-align:center;">No testimonials have been published yet. <a href="<?= url('/portal/login') ?>">Sign in to your applicant dashboard</a> to be the first to share your experience.</p>
                <?php endif; ?>

                <p class="testimonials-more">
                    <a href="<?= url('/portal/login') ?>" class="btn">Leave Your Own Testimonial</a>
                </p>
            </div>
        </section>
    </main>
