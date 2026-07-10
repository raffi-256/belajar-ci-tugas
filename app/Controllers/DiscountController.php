<?php

namespace App\Controllers\Api;

use App\Models\DiscountModel;
use CodeIgniter\RESTful\ResourceController;

class DiscountController extends ResourceController
{
    /**
     * Model yang digunakan ResourceController.
     */
    protected $modelName = DiscountModel::class;

    /**
     * Format respons API.
     */
    protected $format = 'json';

    /**
     * Token harus sama dengan token API Product.
     */
    private const API_TOKEN = 'my-secret-token';

    /**
     * Mengecek Bearer Token dari header Authorization.
     */
    private function isAuthorized(): bool
    {
        $authorization = trim(
            $this->request->getHeaderLine('Authorization')
        );

        if (
            !preg_match(
                '/^Bearer\s+(.+)$/i',
                $authorization,
                $matches
            )
        ) {
            return false;
        }

        $token = trim($matches[1]);

        return hash_equals(self::API_TOKEN, $token);
    }

    /**
     * Mengambil data JSON dari POST atau PUT request.
     */
    private function getRequestData(): array
    {
        try {
            $json = $this->request->getJSON(true);

            if (is_array($json)) {
                return $json;
            }
        } catch (\Throwable $exception) {
            // Lanjutkan membaca raw input.
        }

        $rawInput = $this->request->getRawInput();

        if (is_array($rawInput)) {
            return $rawInput;
        }

        return [];
    }

    /**
     * Memvalidasi data diskon.
     */
    private function validateDiscount(array $data): array
    {
        $validation = service('validation');

        $validation->setRules([
            'tanggal' => [
                'rules' => 'required|valid_date[Y-m-d]',
                'errors' => [
                    'required' =>
                        'Tanggal diskon harus diisi.',
                    'valid_date' =>
                        'Format tanggal harus YYYY-MM-DD.',
                ],
            ],

            'nominal' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' =>
                        'Nominal diskon harus diisi.',
                    'integer' =>
                        'Nominal diskon harus berupa angka.',
                    'greater_than' =>
                        'Nominal diskon harus lebih dari 0.',
                ],
            ],
        ]);

        if (!$validation->run($data)) {
            return $validation->getErrors();
        }

        return [];
    }

    /**
     * GET /api/discounts
     *
     * Menampilkan seluruh data diskon.
     */
    public function index()
    {
        if (!$this->isAuthorized()) {
            return $this->failUnauthorized(
                'Token tidak ada atau tidak valid.'
            );
        }

        $page = (int) (
            $this->request->getGet('page') ?? 1
        );

        $perPage = (int) (
            $this->request->getGet('per_page') ?? 5
        );

        if ($page < 1) {
            $page = 1;
        }

        if ($perPage < 1) {
            $perPage = 5;
        }

        if ($perPage > 100) {
            $perPage = 100;
        }

        $offset = ($page - 1) * $perPage;

        $totalData = $this->model->countAllResults();

        $discounts = $this->model
            ->orderBy('id', 'DESC')
            ->findAll($perPage, $offset);

        $totalPage = $totalData > 0
            ? (int) ceil($totalData / $perPage)
            : 1;

        return $this->respond([
            'status' => 200,
            'message' => 'Data diskon berhasil diambil.',
            'data' => $discounts,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total_data' => $totalData,
                'total_page' => $totalPage,
            ],
        ], 200);
    }

    /**
     * GET /api/discounts/{id}
     *
     * Menampilkan satu data diskon berdasarkan ID.
     */
    public function show($id = null)
    {
        if (!$this->isAuthorized()) {
            return $this->failUnauthorized(
                'Token tidak ada atau tidak valid.'
            );
        }

        $id = (int) $id;

        if ($id < 1) {
            return $this->failValidationErrors([
                'id' => 'ID diskon tidak valid.',
            ]);
        }

        $discount = $this->model->find($id);

        if (!$discount) {
            return $this->failNotFound(
                'Data diskon tidak ditemukan.'
            );
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Data diskon berhasil ditemukan.',
            'data' => $discount,
        ], 200);
    }

    /**
     * POST /api/discounts
     *
     * Menambahkan data diskon baru.
     */
    public function create()
    {
        if (!$this->isAuthorized()) {
            return $this->failUnauthorized(
                'Token tidak ada atau tidak valid.'
            );
        }

        $data = $this->getRequestData();

        $errors = $this->validateDiscount($data);

        if (!empty($errors)) {
            return $this->failValidationErrors($errors);
        }

        $dataSimpan = [
            'tanggal' => $data['tanggal'],
            'nominal' => (int) $data['nominal'],
        ];

        $id = $this->model->insert(
            $dataSimpan,
            true
        );

        if ($id === false) {
            return $this->respond([
                'status' => 500,
                'message' => 'Data diskon gagal ditambahkan.',
                'errors' => $this->model->errors(),
            ], 500);
        }

        return $this->respond([
            'status' => 201,
            'message' => 'Data diskon berhasil ditambahkan.',
            'data' => $this->model->find($id),
        ], 201);
    }

    /**
     * PUT/PATCH /api/discounts/{id}
     *
     * Mengubah data diskon berdasarkan ID.
     */
    public function update($id = null)
    {
        if (!$this->isAuthorized()) {
            return $this->failUnauthorized(
                'Token tidak ada atau tidak valid.'
            );
        }

        $id = (int) $id;

        if ($id < 1) {
            return $this->failValidationErrors([
                'id' => 'ID diskon tidak valid.',
            ]);
        }

        $discount = $this->model->find($id);

        if (!$discount) {
            return $this->failNotFound(
                'Data diskon tidak ditemukan.'
            );
        }

        $data = $this->getRequestData();

        $errors = $this->validateDiscount($data);

        if (!empty($errors)) {
            return $this->failValidationErrors($errors);
        }

        $dataUpdate = [
            'tanggal' => $data['tanggal'],
            'nominal' => (int) $data['nominal'],
        ];

        $updated = $this->model->update(
            $id,
            $dataUpdate
        );

        if (!$updated) {
            return $this->respond([
                'status' => 500,
                'message' => 'Data diskon gagal diperbarui.',
                'errors' => $this->model->errors(),
            ], 500);
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Data diskon berhasil diperbarui.',
            'data' => $this->model->find($id),
        ], 200);
    }

    /**
     * DELETE /api/discounts/{id}
     *
     * Menghapus data diskon berdasarkan ID.
     */
    public function delete($id = null)
    {
        if (!$this->isAuthorized()) {
            return $this->failUnauthorized(
                'Token tidak ada atau tidak valid.'
            );
        }

        $id = (int) $id;

        if ($id < 1) {
            return $this->failValidationErrors([
                'id' => 'ID diskon tidak valid.',
            ]);
        }

        $discount = $this->model->find($id);

        if (!$discount) {
            return $this->failNotFound(
                'Data diskon tidak ditemukan.'
            );
        }

        $deleted = $this->model->delete($id);

        if (!$deleted) {
            return $this->respond([
                'status' => 500,
                'message' => 'Data diskon gagal dihapus.',
                'errors' => $this->model->errors(),
            ], 500);
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Data diskon berhasil dihapus.',
            'data' => $discount,
        ], 200);
    }
}