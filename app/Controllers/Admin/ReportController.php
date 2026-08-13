<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Models\Application;
use App\Services\ApplicationExportService;

class ReportController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('applications');
    }

    public function index(): void
    {
        $filters = $this->filters();

        View::render('admin.reports.index', [
            'pageTitle' => 'Reports',
            'activeNav' => 'reports',
            'applications' => Application::all($filters),
            'statuses' => Application::STATUSES,
            'statusLabels' => Application::STATUS_LABELS,
            'counties' => Application::counties(),
            'filters' => $filters,
        ]);
    }

    public function export(): void
    {
        $scopeAll = Request::query('scope', '') === 'all';
        $filters = $scopeAll ? ['status' => '', 'county' => '', 'search' => ''] : $this->filters();
        $applications = Application::all($filters);

        $heading = $scopeAll || (!$filters['status'] && !$filters['county'])
            ? 'Al NAHDA Agency - Full Report'
            : 'Al NAHDA Agency - Report' . $this->filterSuffix($filters);

        $filename = $this->filename($filters, $scopeAll);
        $format = strtolower((string) Request::query('format', 'excel'));
        if ($format === 'pdf') {
            ApplicationExportService::downloadPdf($applications, $filename, Application::REPORT_FIELDS, $heading);
        }
        ApplicationExportService::downloadExcel($applications, $filename, Application::REPORT_FIELDS, 'Report');
    }

    /** @return array{status:string,county:string,search:string} */
    private function filters(): array
    {
        $county = trim((string) Request::query('county', ''));
        $status = (string) Request::query('status', '');
        $allowed = Application::counties();

        return [
            'status' => in_array($status, Application::STATUSES, true) ? $status : '',
            'county' => in_array($county, $allowed, true) ? $county : '',
            'search' => '',
        ];
    }

    /** @param array{status:string,county:string,search:string} $filters */
    private function filename(array $filters, bool $scopeAll): string
    {
        $parts = ['alnahda-report', date('Y-m-d')];
        if (!$scopeAll) {
            if ($filters['county'] !== '') {
                $parts[] = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $filters['county']) ?: 'county');
            }
            if ($filters['status'] !== '') {
                $parts[] = $filters['status'];
            }
        }
        return implode('-', $parts);
    }

    /** @param array{status:string,county:string,search:string} $filters */
    private function filterSuffix(array $filters): string
    {
        $bits = [];
        if ($filters['county'] !== '') {
            $bits[] = $filters['county'];
        }
        if ($filters['status'] !== '' && isset(Application::STATUS_LABELS[$filters['status']])) {
            $bits[] = Application::STATUS_LABELS[$filters['status']];
        }
        return $bits ? ' (' . implode(', ', $bits) . ')' : '';
    }
}
