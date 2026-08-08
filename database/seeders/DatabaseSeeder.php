<?php
/**
 * One-time/idempotent seed:
 *   1. The first admin account (from ADMIN_SEED_EMAIL / ADMIN_SEED_PASSWORD in .env).
 *   2. Legacy applications from origin_db/uhwlqvsp_alnahda.sql (the production
 *      export) — safe to re-run, existing rows (by id) are left untouched.
 *   3. Backfills applicant_id on every application so it shows up on the
 *      matching applicant's portal dashboard.
 * Run: php database/seeders/DatabaseSeeder.php
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Core\Env;
use App\Core\Database;
use App\Models\Applicant;

Env::load();
$pdo = Database::connection();

// --- Admin account ---
$adminEmail = Env::get('ADMIN_SEED_EMAIL', 'admin@example.com');
$adminPass = Env::get('ADMIN_SEED_PASSWORD', 'admin123');

$exists = $pdo->prepare('SELECT id FROM admins WHERE email = ?');
$exists->execute([$adminEmail]);
if (!$exists->fetch()) {
    $pdo->prepare('INSERT INTO admins (email, password_hash) VALUES (?, ?)')
        ->execute([$adminEmail, password_hash($adminPass, PASSWORD_DEFAULT)]);
    echo "Created admin account '{$adminEmail}'.\n";
} else {
    echo "Admin account '{$adminEmail}' already exists — left untouched.\n";
}

// --- Legacy applications import ---
$dumpFile = dirname(__DIR__, 2) . '/origin_db/uhwlqvsp_alnahda.sql';
if (is_file($dumpFile)) {
    $sql = file_get_contents($dumpFile);
    // Lazy match up to ";\n--" (the next SQL comment block) rather than the first ";" —
    // several county values contain an HTML-encoded apostrophe (Murang&#039;a) whose
    // literal ";" would otherwise truncate the match mid-statement.
    if (preg_match('/INSERT INTO `applications`.*?;\s*\n--/s', $sql, $m)) {
        $statement = rtrim(preg_replace('/\n--$/', '', $m[0]));
        // INSERT IGNORE so rows already present (matched by primary key `id`) are skipped.
        $statement = preg_replace('/^INSERT INTO/', 'INSERT IGNORE INTO', $statement, 1);
        // '0000-00-00' (a handful of legacy rows) isn't a valid DATE under strict SQL mode — null it out.
        $statement = str_replace("'0000-00-00'", 'NULL', $statement);
        $before = (int) $pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();
        $pdo->exec($statement);
        $after = (int) $pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();
        echo 'Imported ' . ($after - $before) . " new legacy application(s) from origin_db (rows already present were skipped).\n";
    } else {
        echo "No applications INSERT statement found in origin_db dump — skipped.\n";
    }
} else {
    echo "origin_db/uhwlqvsp_alnahda.sql not found — skipped legacy import.\n";
}

// --- Backfill applicant_id so every application is reachable from a portal account ---
$rows = $pdo->query("SELECT id, fullname, email, phone FROM applications WHERE applicant_id IS NULL")->fetchAll();
$linked = 0;
foreach ($rows as $row) {
    if (empty($row['email']) && empty($row['phone'])) {
        continue;
    }
    $applicantId = Applicant::findOrCreateFromApplication(
        (string) $row['email'],
        (string) $row['phone'],
        (string) $row['fullname']
    );
    $pdo->prepare('UPDATE applications SET applicant_id = ? WHERE id = ?')->execute([$applicantId, $row['id']]);
    $linked++;
}
echo "Linked {$linked} application(s) to a portal account.\n";

// --- Countries (only seeded once, if the table is empty — admin manages them afterwards) ---
$countryCount = (int) $pdo->query('SELECT COUNT(*) FROM countries')->fetchColumn();
if ($countryCount === 0) {
    $countries = [
        ['Oman', 'Responsive staffing support for domestic and hospitality operators across Muscat and emerging hubs.'],
        ['Saudi Arabia', 'Reliable personnel across household, transport, and facility management roles tailored to KSA compliance.'],
        ['Bahrain', 'Agile recruitment solutions for boutique hospitality brands and premium residences.'],
        ['Kuwait', 'Skilled workers ready for private households, logistics, and customer experience teams.'],
        ['Dubai', 'High-caliber talent matched to luxury hospitality, aviation, and lifestyle service standards.'],
        ['Lebanon', 'Trusted placements supporting domestic care, boutique hotels, and specialized service providers.'],
    ];
    $stmt = $pdo->prepare(
        'INSERT INTO countries (name, description, sort_order, is_active) VALUES (:name, :description, :sort_order, 1)'
    );
    foreach ($countries as $i => [$name, $description]) {
        $stmt->execute(['name' => $name, 'description' => $description, 'sort_order' => $i]);
    }
    echo 'Seeded ' . count($countries) . " countries.\n";
} else {
    echo "Countries already seeded ({$countryCount}) — left untouched.\n";
}

// --- Testimonials (official launch quotes, pre-approved so the site isn't empty on day one) ---
$testimonialCount = (int) $pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn();
if ($testimonialCount === 0) {
    $testimonials = [
        ['Operations Manager', 'Hospitality Group, Dubai', 5, "Al NAHDA Agency has been a game-changer for our operations. Their workers are skilled, respectful, and well-prepared. Onboarding has never been smoother."],
        ['HR Director', 'Domestic Staffing Agency, Riyadh', 5, "Their attention to detail and cultural sensitivity make them our go-to recruitment partner. They understand our needs deeply."],
        ['Agency Partner', 'Doha', 5, "Professionalism and ethical practices set Al NAHDA apart. Every placement feels like a win for us and the workers."],
        ['Logistics Coordinator', 'UAE', 5, "From documentation to deployment, everything is handled with precision. The workers are reliable and support is exceptional."],
        ['Client Relations Lead', 'Bahrain', 5, "They build relationships, not just fill vacancies. The commitment to quality and integrity is evident in every interaction."],
        ['Regional Manager', 'Saudi Arabia', 5, "Transparency and care at every step. They value both client and worker equally, which is rare in our industry."],
    ];
    $stmt = $pdo->prepare(
        "INSERT INTO testimonials (author_name, author_role, rating, message, status, approved_at)
         VALUES (:author_name, :author_role, :rating, :message, 'approved', NOW())"
    );
    foreach ($testimonials as [$name, $role, $rating, $message]) {
        $stmt->execute(['author_name' => $name, 'author_role' => $role, 'rating' => $rating, 'message' => $message]);
    }
    echo 'Seeded ' . count($testimonials) . " testimonials.\n";
} else {
    echo "Testimonials already seeded ({$testimonialCount}) — left untouched.\n";
}

echo "Seed complete.\n";
