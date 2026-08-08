<?php
/** Requires $countries, $testimonials in scope. */
?>
    <main>
        <div class="brand-showcase">
  <div class="brand-showcase-track">
    <div class="brand-showcase-slide slide-girls"></div>
    <div class="brand-showcase-slide slide-team"></div>
    <div class="brand-showcase-slide slide-hq"></div>
</div>

  <!-- Navigation Arrows -->
  <button class="showcase-nav showcase-prev">&#10094;</button>
  <button class="showcase-nav showcase-next">&#10095;</button>

  <div class="brand-showcase-overlay">

                <a href="<?= url('/apply') ?>" class="btn btn-light brand-showcase-cta">Apply Now</a>
            </div>
        </div>
        <section class="hero" id="top">
            <div class="container hero-content">
                <div class="hero-text">
                    <h1>Your Trusted Recruitment Partner for the Middle East</h1>
                    <p>Discover rewarding placements with trusted employers across domestic, hospitality, transport, and general labor sectors in the Middle East.</p>
                    <div class="hero-actions">
                        <a href="<?= url('/apply') ?>" class="btn">Apply for a Job</a>
                        <a href="#services" class="btn btn-outline">Explore Services</a>
                    </div>
                    <div class="hero-highlights">
                        <div class="hero-highlight">
                            <span class="highlight-number counter" data-count="245" data-suffix="+">0</span>
                            <span class="highlight-label">Workers Deployed</span>
                        </div>
                        <div class="hero-highlight">
                            <span class="highlight-number counter" data-count="5" data-suffix="+ yrs">0</span>
                            <span class="highlight-label">Recruitment Expertise</span>
                        </div>
                        <div class="hero-highlight">
                            <span class="highlight-number counter" data-count="6" data-suffix=" Countries">0</span>
                            <span class="highlight-label">Partner Destinations</span>
                        </div>
                    </div>
                </div>
                <div class="hero-card">
                    <h2>Building Pathways to Opportunity</h2>
                    <p>We guide job seekers through ethical recruitment journeys with transparent contracts, cultural insight, and support from application to deployment.</p>
                    <ul>
                        <li>Visa and documentation guidance</li>
                        <li>Transparent contracts &amp; onboarding</li>
                        <li>Dedicated support while overseas</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="section" id="about">
            <div class="container split">
                <div>
                    <h2>Executive Summary</h2>
                    <p>Al NAHDA Agency is a Nairobi-based recruitment leader committed to sourcing and placing vetted personnel who meet international standards. We specialize in domestic care, hospitality, transportation, and general labor, ensuring every placement advances opportunity and impact.</p>
                </div>
                <div class="card">
                    <h3>About Us</h3>
                    <p>Founded on integrity, empowerment, and excellence, Al NAHDY Agency Ltd connects global partners with talent ready to perform. Our decade of experience in workforce strategy, cultural alignment, and client engagement means your staffing needs are handled with precision.</p>
                    <p class="small">We go beyond placements to build lasting partnerships grounded in transparency, respect, and shared success.</p>
                </div>
            </div>
        </section>

        <section class="section light" id="services">
            <div class="container">
                <h2>Our Services</h2>
                <p class="section-intro">End-to-end recruitment support for job seekers and employers alike — from first interview to life after deployment.</p>
                <div class="card-grid">
                    <article class="service-card">
                        <h3>Candidate Sourcing &amp; Screening</h3>
                        <p>Rigorous vetting of skills, character references, and background checks so every candidate we put forward is genuinely job-ready.</p>
                    </article>
                    <article class="service-card">
                        <h3>Documentation &amp; Visa Processing</h3>
                        <p>We handle contracts, work permits, and travel documentation end-to-end, keeping every placement fully compliant.</p>
                    </article>
                    <article class="service-card">
                        <h3>Pre-Departure Orientation</h3>
                        <p>Cultural, language, and workplace-readiness briefings that prepare candidates for life and work abroad before they ever board a flight.</p>
                    </article>
                    <article class="service-card">
                        <h3>Placement &amp; Deployment</h3>
                        <p>Coordinated travel and onboarding logistics from our Nairobi office to the employer's doorstep, with nothing left to chance.</p>
                    </article>
                    <article class="service-card">
                        <h3>Post-Placement Support</h3>
                        <p>Ongoing check-ins and a direct line of communication for both employers and workers throughout the contract period.</p>
                    </article>
                    <article class="service-card">
                        <h3>Employer Consultation</h3>
                        <p>Workforce planning and staffing advice tailored to household, hospitality, and facility management needs.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section" id="countries">
            <div class="container">
                <h2>Countries We Recruit To</h2>
                <p class="section-intro">We deliver vetted talent to key destinations across the Middle East, aligning with regional expectations and regulatory standards.</p>
                <?php if ($countries): ?>
                <div class="country-grid">
                    <?php foreach ($countries as $country): ?>
                        <article class="country-card">
                            <?php if ($country['cover_image']): ?>
                                <div class="country-card-cover" style="background-image:url('<?= e(imageUrl($country['cover_image'])) ?>');"></div>
                            <?php endif; ?>
                            <h3>
                                <?php if ($country['flag_image']): ?>
                                    <img src="<?= e(imageUrl($country['flag_image'])) ?>" alt="" class="country-flag">
                                <?php else: ?>
                                    <span class="country-flag country-flag-emoji"><?= \App\Models\Country::flagFallback($country['name']) ?></span>
                                <?php endif; ?>
                                <?= e($country['name']) ?>
                            </h3>
                            <?php if ($country['description']): ?>
                                <p><?= e($country['description']) ?></p>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <p class="section-intro">Destinations are being updated — check back shortly, or <a href="<?= url('/apply') ?>">apply now</a> and our recruiters will match you to current opportunities.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="section light" id="why">
            <div class="container">
                <h2>Why Candidates Choose Al NAHDA Agency</h2>
                <p class="section-intro">Five years of placing East African talent in trusted Middle East households and businesses — the right way, every time.</p>
                <div class="feature-grid">
                    <div class="feature-card">
                        <h3>Trusted Expertise</h3>
                        <p>Seasoned recruitment specialists guiding East African talent into reputable Middle East placements, since day one.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Ethical Recruitment</h3>
                        <p>No hidden fees, no false promises — transparent, fair hiring processes that protect your rights and meet international regulations.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Cultural Alignment</h3>
                        <p>We prepare you for the culture, language, and expectations of your new workplace, so you settle in with confidence.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Tailored Job Matching</h3>
                        <p>We match you to roles based on your real skills, experience, and preferences — not just the first available vacancy.</p>
                    </div>
                    <div class="feature-card">
                        <h3>Verified Employers</h3>
                        <p>Every partner employer is vetted for reputation and compliance before we place a single candidate with them.</p>
                    </div>
                    <div class="feature-card">
                        <h3>End-to-End Support</h3>
                        <p>From your first application to life after deployment, our team stays reachable — including through your applicant dashboard.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="track-record">
            <div class="container track-grid">
                <div>
                    <h2>Proven Track Record</h2>
                    <p>We have deployed more than 245 skilled personnel since 2022, maintaining enduring partnerships with agencies throughout the Middle East.</p>
                    <p>Our growing infrastructure allows us to scale responsibly while preserving quality, compliance, and worker welfare.</p>
                </div>
                <div class="metrics">
                    <div class="metric">
                        <span class="metric-number">245+</span>
                        <span class="metric-label">Workers Deployed</span>
                    </div>
                    <div class="metric">
                        <span class="metric-number">15</span>
                        <span class="metric-label">Regional Partners</span>
                    </div>
                    <div class="metric">
                        <span class="metric-number">100%</span>
                        <span class="metric-label">Compliance Score</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="section light" id="mission">
            <div class="container split">
                <div>
                    <h2>Mission, Vision &amp; Values</h2>
                    <p class="section-intro">We are driven by a commitment to integrity, empowerment, and enduring impact in every placement.</p>
                    <div class="mission-vision">
                        <div>
                            <h3>Mission</h3>
                            <p>Connect international clients with skilled, dependable personnel through dignified, quality-driven recruitment solutions.</p>
                        </div>
                        <div>
                            <h3>Vision</h3>
                            <p>Be East Africas most trusted recruitment agency, recognized for ethical sourcing, cultural insight, and consistent excellence.</p>
                        </div>
                    </div>
                </div>
                <div class="values">
                    <h3>Core Values</h3>
                    <ul>
                        <li>Integrity</li>
                        <li>Professionalism</li>
                        <li>Reliability</li>
                        <li>Excellence</li>
                        <li>Empowerment</li>
                        <li>Social Impact</li>
                        <li>Cultural Sensitivity</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="section" id="strategy">
            <div class="container">
                <h2>Strategic Priorities</h2>
                <div class="strategy-list">
                    <div class="strategy-item">
                        <div class="strategy-marker">01</div>
                        <div>
                            <h3>Stakeholder Engagement</h3>
                            <p>Deepen collaboration with clients, communities, and regulatory bodies to align on workforce expectations.</p>
                        </div>
                    </div>
                    <div class="strategy-item">
                        <div class="strategy-marker">02</div>
                        <div>
                            <h3>Corporate Social Responsibility</h3>
                            <p>Embed CSR into recruitment cycles to ensure sustainable impact for workers and their families.</p>
                        </div>
                    </div>
                    <div class="strategy-item">
                        <div class="strategy-marker">03</div>
                        <div>
                            <h3>Ethical Work Culture</h3>
                            <p>Champion ethical standards across all placements, reinforcing transparency and accountability.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section light" id="testimonials">
            <div class="container">
                <h2>What Our Applicants Say</h2>
                <p class="section-intro">Real feedback from candidates we've placed — approved by our team before publishing.</p>
                <?php if ($testimonials): ?>
                <div class="testimonial-controls">
                    <button class="control-btn" data-direction="prev" aria-label="Previous testimonials">&#8249;</button>
                    <button class="control-btn" data-direction="next" aria-label="Next testimonials">&#8250;</button>
                </div>
                <div class="testimonial-track" role="list">
                    <?php foreach ($testimonials as $t): ?>
                        <article class="testimonial-card" role="listitem">
                            <span class="testimonial-stars" aria-hidden="true"><?= str_repeat('&#9733;', (int) $t['rating']) . str_repeat('&#9734;', 5 - (int) $t['rating']) ?></span>
                            <p>&ldquo;<?= e($t['message']) ?>&rdquo;</p>
                            <h3><?= e($t['author_name']) ?></h3>
                            <?php if ($t['author_role']): ?><span><?= e($t['author_role']) ?></span><?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <p>Be the first to share your experience — <a href="<?= url('/portal/login') ?>">sign in to your applicant dashboard</a> to leave a testimonial once you've been placed.</p>
                <?php endif; ?>
                <p class="testimonials-more"><a href="<?= url('/testimonials') ?>" class="btn btn-outline">Read All Testimonials</a></p>
            </div>
        </section>

        <section class="section cta">
            <div class="container">
                <h2>Launch Your Overseas Career</h2>
                <p>Take the next step in your Middle East career journey. We deliver ethical recruitment, transparent documentation, and dedicated support from application through deployment.</p>
                <a href="<?= url('/apply') ?>" class="btn btn-light">Apply Today</a>
            </div>
        </section>

        <section class="section" id="contact">
            <div class="container split">
                <div>
                    <h2>Contact Us</h2>
                    <p>Viewpark Towers, Nairobi, Kenya</p>
                    <p>1st floor suite 5</p>
                    <p><a href="tel:+254793700592">+254793700592</a></p>
                    <p><a href="mailto:Alnahdaagency@gmail.com">Alnahdaagency@gmail.com</a></p>
                </div>
                <div class="contact-card">
                    <h3>Connect with Our Team</h3>
                    <p>We respond swiftly to ensure your staffing needs are met with professionalism and care.</p>
                    <a href="<?= url('/apply') ?>" class="btn">Submit Your Details</a>
                </div>
            </div>
        </section>
    </main>
