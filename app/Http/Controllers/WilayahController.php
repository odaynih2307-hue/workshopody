<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    private string $apiBase = 'https://api.emsifa.com/api';

    public function ajax()
    {
        $provinsi = $this->getProvinsiData();
        return view('wilayah.ajax', compact('provinsi'));
    }

    public function axios()
    {
        $provinsi = $this->getProvinsiData();
        return view('wilayah.axios', compact('provinsi'));
    }

    /**
     * Show Cascading Form
     */
    public function showCascadingForm()
    {
        return view('wilayah.cascading-form');
    }

    /**
     * Debug API - untuk melihat raw data dari API
     */
    public function debugApi()
    {
        return [
            'provinces' => $this->getProvinsiData(),
            'provinces_count' => count($this->getProvinsiData()),
        ];
    }

    public function getProvinsi()
    {
        $data = $this->getProvinsiData();
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data provinsi berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKota(Request $request)
    {
        $data = $this->getKotaData((string) $request->id_provinsi);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kota berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKecamatan(Request $request)
    {
        $data = $this->getKecamatanData((string) $request->id_kota);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kecamatan berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKelurahan(Request $request)
    {
        $data = $this->getKelurahanData((string) $request->id_kecamatan);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kelurahan berhasil diambil',
            'data'    => $data,
        ]);
    }

    private function getProvinsiData()
    {
        $apiData = $this->fetchApiList($this->apiBase . '/provinces', function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'nama_provinsi' => (string) ($item['name'] ?? ''),
            ];
        });

        if ($apiData !== null) {
            return collect($apiData)->sortBy('nama_provinsi')->values()->map(fn ($row) => (object) $row);
        }

        return Provinsi::orderBy('nama_provinsi')->get(['id', 'nama_provinsi']);
    }

    private function getKotaData(string $idProvinsi)
    {
        $apiData = $this->fetchApiList($this->apiBase . '/regencies?province_id=' . $idProvinsi, function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'nama_kota' => (string) ($item['name'] ?? ''),
            ];
        });

        if ($apiData !== null) {
            return collect($apiData)->sortBy('nama_kota')->values()->map(fn ($row) => (object) $row);
        }

        return Kota::where('provinsi_id', $idProvinsi)
            ->orderBy('nama_kota')
            ->get(['id', 'nama_kota']);
    }

    private function getKecamatanData(string $idKota)
    {
        $apiData = $this->fetchApiList($this->apiBase . '/districts?regency_id=' . $idKota, function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'nama_kecamatan' => (string) ($item['name'] ?? ''),
            ];
        });

        if ($apiData !== null) {
            return collect($apiData)->sortBy('nama_kecamatan')->values()->map(fn ($row) => (object) $row);
        }

        return Kecamatan::where('kota_id', $idKota)
            ->orderBy('nama_kecamatan')
            ->get(['id', 'nama_kecamatan']);
    }

    private function getKelurahanData(string $idKecamatan)
    {
        $apiData = $this->fetchApiList($this->apiBase . '/villages?district_id=' . $idKecamatan, function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'nama_kelurahan' => (string) ($item['name'] ?? ''),
            ];
        });

        if ($apiData !== null) {
            return collect($apiData)->sortBy('nama_kelurahan')->values()->map(fn ($row) => (object) $row);
        }

        return Kelurahan::where('kecamatan_id', $idKecamatan)
            ->orderBy('nama_kelurahan')
            ->get(['id', 'nama_kelurahan']);
    }

    private function fetchApiList(string $url, callable $transformer): ?array
    {
        try {
            $response = Http::timeout(8)->acceptJson()->get($url);
            if (!$response->ok()) {
                return null;
            }

            $payload = $response->json();
            if (!is_array($payload)) {
                return null;
            }

            $mapped = [];
            foreach ($payload as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $normalized = $transformer($item);
                // Check if the normalized data has id and at least one nama field
                $hasId = !empty($normalized['id']);
                $hasName = false;
                foreach ($normalized as $key => $value) {
                    if (strpos($key, 'nama_') === 0 && !empty($value)) {
                        $hasName = true;
                        break;
                    }
                }
                
                if ($hasId && $hasName) {
                    $mapped[] = $normalized;
                }
            }

            return $mapped;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
