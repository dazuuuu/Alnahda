<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\View;
use App\Models\Country;
use App\Services\UploadException;
use App\Services\UploadService;

class CountryController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('countries');
    }

    public function index(): void
    {
        View::render('admin.countries.index', [
            'pageTitle' => 'Countries',
            'activeNav' => 'countries',
            'countries' => Country::all(),
        ]);
    }

    public function create(): void
    {
        View::render('admin.countries.form', [
            'pageTitle' => 'Add Country',
            'activeNav' => 'countries',
            'mode' => 'create',
            'errors' => [],
            'form' => ['name' => '', 'description' => '', 'sort_order' => 0, 'is_active' => true],
            'country' => null,
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate();

        if (!$errors) {
            [$flagImage, $coverImage, $uploadErrors] = $this->handleUploads(null, null);
            $errors = $uploadErrors;
        }

        if (!$errors) {
            Country::create([
                'name' => trim((string) Request::post('name')),
                'flag_image' => $flagImage,
                'cover_image' => $coverImage,
                'description' => trim((string) Request::post('description', '')) ?: null,
                'sort_order' => (int) Request::post('sort_order', 0),
                'is_active' => Request::post('is_active') ? 1 : 0,
            ]);
            flashSuccess('Country added.');
            redirect('/admin/countries');
        }

        View::render('admin.countries.form', [
            'pageTitle' => 'Add Country',
            'activeNav' => 'countries',
            'mode' => 'create',
            'errors' => $errors,
            'form' => $this->oldInput(),
            'country' => null,
        ]);
    }

    public function edit(string $id): void
    {
        $country = Country::find((int) $id);
        if (!$country) {
            redirect('/admin/countries');
        }

        View::render('admin.countries.form', [
            'pageTitle' => 'Edit Country',
            'activeNav' => 'countries',
            'mode' => 'edit',
            'errors' => [],
            'form' => [
                'name' => $country['name'],
                'description' => $country['description'] ?? '',
                'sort_order' => $country['sort_order'],
                'is_active' => (bool) $country['is_active'],
            ],
            'country' => $country,
        ]);
    }

    public function update(string $id): void
    {
        $country = Country::find((int) $id);
        if (!$country) {
            redirect('/admin/countries');
        }

        $errors = $this->validate();

        if (!$errors) {
            [$flagImage, $coverImage, $uploadErrors] = $this->handleUploads($country['flag_image'], $country['cover_image']);
            $errors = $uploadErrors;
        }

        if (!$errors) {
            Country::update((int) $country['id'], [
                'name' => trim((string) Request::post('name')),
                'flag_image' => $flagImage,
                'cover_image' => $coverImage,
                'description' => trim((string) Request::post('description', '')) ?: null,
                'sort_order' => (int) Request::post('sort_order', 0),
                'is_active' => Request::post('is_active') ? 1 : 0,
            ]);
            flashSuccess('Country updated.');
            redirect('/admin/countries');
        }

        View::render('admin.countries.form', [
            'pageTitle' => 'Edit Country',
            'activeNav' => 'countries',
            'mode' => 'edit',
            'errors' => $errors,
            'form' => $this->oldInput(),
            'country' => $country,
        ]);
    }

    public function destroy(string $id): void
    {
        $country = Country::find((int) $id);
        if ($country && csrfVerify(Request::post('csrf_token'))) {
            UploadService::delete($country['flag_image']);
            UploadService::delete($country['cover_image']);
            Country::delete((int) $country['id']);
            flashSuccess('Country removed.');
        }
        redirect('/admin/countries');
    }

    /** @return string[] */
    private function validate(): array
    {
        $errors = [];
        if (!csrfVerify(Request::post('csrf_token'))) {
            $errors[] = 'Your session expired. Please resubmit the form.';
            return $errors;
        }
        if (trim((string) Request::post('name', '')) === '') {
            $errors[] = 'Please enter the country name.';
        }
        return $errors;
    }

    /** @return array{0:?string,1:?string,2:string[]} [flagImagePath, coverImagePath, errors] */
    private function handleUploads(?string $existingFlag, ?string $existingCover): array
    {
        $errors = [];
        $flagImage = $existingFlag;
        $coverImage = $existingCover;

        $flagFile = Request::file('flag_image');
        if ($flagFile && !empty($flagFile['name'])) {
            try {
                $new = UploadService::store($flagFile, 'countries');
                UploadService::delete($existingFlag);
                $flagImage = $new;
            } catch (UploadException $e) {
                $errors[] = 'Flag image: ' . $e->getMessage();
            }
        }

        $coverFile = Request::file('cover_image');
        if ($coverFile && !empty($coverFile['name'])) {
            try {
                $new = UploadService::store($coverFile, 'countries');
                UploadService::delete($existingCover);
                $coverImage = $new;
            } catch (UploadException $e) {
                $errors[] = 'Cover image: ' . $e->getMessage();
            }
        }

        return [$flagImage, $coverImage, $errors];
    }

    private function oldInput(): array
    {
        return [
            'name' => Request::post('name', ''),
            'description' => Request::post('description', ''),
            'sort_order' => (int) Request::post('sort_order', 0),
            'is_active' => (bool) Request::post('is_active'),
        ];
    }
}
