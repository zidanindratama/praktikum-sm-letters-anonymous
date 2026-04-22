<?php

namespace App\Controllers;

use App\Models\LetterModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class AdminLetters extends BaseController
{
    public function login(): string|RedirectResponse
    {
        if (session()->get('is_admin')) {
            return redirect()->to(site_url('admin'));
        }

        return view('admin/login');
    }

    public function attemptLogin(): RedirectResponse
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        if ($username === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $configuredUsername = (string) env('admin.username', 'admin');
        $configuredPasswordHash = (string) env('admin.password_hash', '');

        if ($configuredPasswordHash === '') {
            return redirect()->back()->withInput()->with('error', 'Kredensial admin di .env belum dikonfigurasi.');
        }

        if ($username !== $configuredUsername || ! password_verify($password, $configuredPasswordHash)) {
            return redirect()->back()->withInput()->with('error', 'Login admin gagal. Periksa kembali kredensialnya.');
        }

        session()->set([
            'is_admin'       => true,
            'admin_username' => $configuredUsername,
        ]);

        return redirect()->to(site_url('admin'))
            ->with('success', 'Login admin berhasil.');
    }

    public function logout(): RedirectResponse
    {
        session()->remove(['is_admin', 'admin_username']);

        return redirect()->to(site_url('admin/login'))
            ->with('success', 'Session admin berhasil diakhiri.');
    }

    public function index(): string
    {
        $keyword = trim((string) $this->request->getGet('q'));
        $sort = trim((string) $this->request->getGet('sort'));
        $direction = strtoupper(trim((string) $this->request->getGet('direction')));
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 10;

        $allowedSorts = ['recipient', 'created_at', 'updated_at'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (! in_array($direction, ['ASC', 'DESC'], true)) {
            $direction = 'DESC';
        }

        $letters = new LetterModel();

        if ($keyword !== '') {
            $letters
                ->groupStart()
                ->like('recipient', $keyword)
                ->orLike('message', $keyword)
                ->orLike('id', $keyword)
                ->groupEnd();
        }

        $results = $letters
            ->orderBy($sort, $direction)
            ->paginate($perPage, 'default', $page);

        $pager = $letters->pager;

        return view('admin/index', [
            'letters'      => $results,
            'filters'      => [
                'q'         => $keyword,
                'sort'      => $sort,
                'direction' => $direction,
            ],
            'pagination'   => [
                'currentPage' => $pager->getCurrentPage('default'),
                'pageCount'   => $pager->getPageCount('default'),
                'perPage'     => $perPage,
                'total'       => $pager->getTotal('default'),
            ],
            'adminUsername' => session()->get('admin_username'),
        ]);
    }

    public function edit(string $id): string
    {
        return view('admin/edit', [
            'letter' => $this->findLetterOrFail($id),
        ]);
    }

    public function update(string $id): RedirectResponse
    {
        $letter = $this->findLetterOrFail($id);
        $image = $this->request->getFile('image');
        $hasNewImage = $image !== null && $image->getError() !== UPLOAD_ERR_NO_FILE;

        $rules = [
            'recipient' => 'required|max_length[100]',
            'message'   => 'required|min_length[10]',
        ];

        if ($hasNewImage) {
            $rules['image'] = 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'recipient' => trim((string) $this->request->getPost('recipient')),
            'message'   => trim((string) $this->request->getPost('message')),
        ];

        if ($hasNewImage) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $imageName);
            $data['image_path'] = 'uploads/' . $imageName;
            $this->deleteImageFile($letter['image_path']);
        }

        (new LetterModel())->update($id, $data);

        return redirect()->to(site_url('admin'))
            ->with('success', 'Surat berhasil diperbarui dari dashboard admin.');
    }

    public function delete(string $id): RedirectResponse
    {
        $letter = $this->findLetterOrFail($id);

        $this->deleteImageFile($letter['image_path']);
        (new LetterModel())->delete($id);

        return redirect()->to(site_url('admin'))
            ->with('success', 'Surat berhasil dihapus dari dashboard admin.');
    }

    private function findLetterOrFail(string $id): array
    {
        $letter = (new LetterModel())->find($id);

        if ($letter === null) {
            throw PageNotFoundException::forPageNotFound('Surat tidak ditemukan.');
        }

        return $letter;
    }

    private function deleteImageFile(?string $imagePath): void
    {
        if ($imagePath === null || $imagePath === '') {
            return;
        }

        $fullPath = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $imagePath);

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
