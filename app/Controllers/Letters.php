<?php

namespace App\Controllers;

use App\Models\LetterModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Letters extends BaseController
{
    private LetterModel $letters;

    public function __construct()
    {
        $this->letters = new LetterModel();
    }

    public function index(): string
    {
        $keyword = trim((string) $this->request->getGet('q'));
        $sort = trim((string) $this->request->getGet('sort'));
        $direction = strtoupper(trim((string) $this->request->getGet('direction')));
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 9;

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

        return view('letters/index', [
            'letters' => $results,
            'filters' => [
                'q'         => $keyword,
                'sort'      => $sort,
                'direction' => $direction,
            ],
            'pagination' => [
                'currentPage' => $pager->getCurrentPage('default'),
                'pageCount'   => $pager->getPageCount('default'),
                'perPage'     => $perPage,
                'total'       => $pager->getTotal('default'),
            ],
        ]);
    }

    public function create(): string
    {
        return view('letters/create');
    }

    public function team(): string
    {
        return view('letters/team', [
            'members' => [
                [
                    'npm'  => '50422428',
                    'name' => 'DIMAS ARYA SAUKI ALAUDIN',
                ],
                [
                    'npm'  => '50422968',
                    'name' => 'MUHAMAD ZIDAN INDRATAMA',
                ],
                [
                    'npm'  => '51422157',
                    'name' => 'MUHAMMAD SANUSI AMIR BAYQUNI',
                ],
                [
                    'npm'  => '51422279',
                    'name' => 'PANGERAN MAHARESI DUNIA',
                ],
            ],
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'recipient' => 'required|max_length[100]',
            'message'   => 'required|min_length[10]',
            'image'     => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $image = $this->request->getFile('image');
        $imageName = $image->getRandomName();
        $image->move(FCPATH . 'uploads', $imageName);

        $id = $this->generateUuid();
        $passcode = $this->generatePasscode();

        $this->letters->insert([
            'id'            => $id,
            'recipient'     => trim((string) $this->request->getPost('recipient')),
            'message'       => trim((string) $this->request->getPost('message')),
            'image_path'    => 'uploads/' . $imageName,
            'passcode_hash' => password_hash($passcode, PASSWORD_DEFAULT),
        ]);

        return redirect()->to(site_url('letters/' . $id))
            ->with('success', 'Surat berhasil dikirim.')
            ->with('generatedPasscode', $passcode);
    }

    public function show(string $id): string
    {
        return view('letters/show', [
            'letter'      => $this->findLetterOrFail($id),
            'canEdit'     => $this->hasEditAccess($id),
        ]);
    }

    public function authorizeEdit(string $id): RedirectResponse
    {
        $letter = $this->findLetterOrFail($id);
        $passcode = trim((string) $this->request->getPost('passcode'));

        if ($passcode === '') {
            return redirect()->to(site_url('letters/' . $id))
                ->with('error', 'Passcode wajib diisi untuk membuka halaman edit.');
        }

        if (! password_verify($passcode, $letter['passcode_hash'])) {
            return redirect()->to(site_url('letters/' . $id))
                ->with('error', 'Passcode tidak cocok.');
        }

        $this->grantEditAccess($id);

        return redirect()->to(site_url('letters/' . $id . '/edit'))
            ->with('success', 'Passcode cocok. Kamu sekarang bisa mengedit surat ini.');
    }

    public function edit(string $id): string|RedirectResponse
    {
        $letter = $this->findLetterOrFail($id);

        if (! $this->hasEditAccess($id)) {
            return redirect()->to(site_url('letters/' . $id))
                ->with('error', 'Masukkan passcode dulu untuk mengedit surat.');
        }

        return view('letters/edit', [
            'letter' => $letter,
        ]);
    }

    public function update(string $id): RedirectResponse
    {
        $letter = $this->findLetterOrFail($id);

        if (! $this->hasEditAccess($id)) {
            return redirect()->to(site_url('letters/' . $id))
                ->with('error', 'Akses edit sudah habis atau belum diverifikasi.');
        }

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

        $this->letters->update($id, $data);

        return redirect()->to(site_url('letters/' . $id))
            ->with('success', 'Surat berhasil diperbarui.');
    }

    public function delete(string $id): RedirectResponse
    {
        $letter = $this->findLetterOrFail($id);
        $passcode = trim((string) $this->request->getPost('passcode'));

        if ($passcode === '') {
            return redirect()->to(site_url('letters/' . $id))
                ->with('error', 'Passcode wajib diisi untuk menghapus surat.');
        }

        if (! password_verify($passcode, $letter['passcode_hash'])) {
            return redirect()->to(site_url('letters/' . $id))
                ->with('error', 'Passcode tidak cocok. Surat tidak jadi dihapus.');
        }

        $this->deleteImageFile($letter['image_path']);
        $this->letters->delete($id);
        $this->revokeEditAccess($id);

        return redirect()->to(site_url('/'))
            ->with('success', 'Surat berhasil dihapus.');
    }

    private function findLetterOrFail(string $id): array
    {
        $letter = $this->letters->find($id);

        if ($letter === null) {
            throw PageNotFoundException::forPageNotFound('Surat tidak ditemukan.');
        }

        return $letter;
    }

    private function hasEditAccess(string $id): bool
    {
        $authorizedLetters = session()->get('authorized_letters') ?? [];

        return in_array($id, $authorizedLetters, true);
    }

    private function grantEditAccess(string $id): void
    {
        $authorizedLetters = session()->get('authorized_letters') ?? [];

        if (! in_array($id, $authorizedLetters, true)) {
            $authorizedLetters[] = $id;
            session()->set('authorized_letters', $authorizedLetters);
        }
    }

    private function revokeEditAccess(string $id): void
    {
        $authorizedLetters = session()->get('authorized_letters') ?? [];
        $authorizedLetters = array_values(array_filter(
            $authorizedLetters,
            static fn (string $authorizedId): bool => $authorizedId !== $id
        ));

        session()->set('authorized_letters', $authorizedLetters);
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

    private function generateUuid(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    private function generatePasscode(int $length = 6): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $maxIndex = strlen($characters) - 1;
        $passcode = '';

        for ($i = 0; $i < $length; $i++) {
            $passcode .= $characters[random_int(0, $maxIndex)];
        }

        return $passcode;
    }
}
