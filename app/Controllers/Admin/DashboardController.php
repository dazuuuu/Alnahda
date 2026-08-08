<?php

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\View;
use App\Models\Application;

class DashboardController extends BaseAdminController
{
    public function index(): void
    {
        $pdo = Database::connection();
        $counts = Application::counts();

        $stats = [
            'total' => $counts['total'],
            'thisWeek' => $counts['this_week'],
            'pending' => $counts['pending'],
            'shortlisted' => $counts['shortlisted'],
            'interview' => $counts['interview'],
            'placed' => $counts['placed'],
            'applicants' => (int) $pdo->query('SELECT COUNT(*) FROM applicants')->fetchColumn(),
        ];

        View::render('admin.dashboard', [
            'pageTitle' => 'Dashboard',
            'activeNav' => 'dashboard',
            'stats' => $stats,
            'statusCounts' => $counts,
            'recentApplications' => Application::recent(8),
        ]);
    }
}
