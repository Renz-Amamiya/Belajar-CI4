<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RajaongkirController extends BaseController
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        // Mengambil konfigurasi dari .env
        $this->apiKey = getenv('RAJAONGKIR_API_KEY');
        $this->baseUrl = getenv('RAJAONGKIR_BASE_URL');
    }

    /**
     * Fungsi private untuk melakukan cURL request ke API RajaOngkir
     */
    private function rajarongkirRequest($endpoint, $method = 'GET', $postFields = null)
    {
        $curl = curl_init();

        $curlOptions = [
            CURLOPT_URL => $this->baseUrl . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                "key: " . $this->apiKey,
                "content-type: application/x-www-form-urlencoded"
            ],
        ];

        if ($method == 'POST' && $postFields !== null) {
            $curlOptions[CURLOPT_POSTFIELDS] = $postFields;
        }

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    /**
     * Endpoint untuk mencari destinasi (Kelurahan) via AJAX Select2
     */
    public function searchDestination()
    {
        // Select2 mengirimkan parameter pencarian dengan nama 'term' atau 'q'
        $search = $this->request->getGet('q');
        
        $endpoint = 'destination/domestic-destination?search=' . urlencode($search) . '&limit=50';
        $response = $this->rajarongkirRequest($endpoint);
        
        $data = json_decode($response, true);
        
        // Format ulang untuk Select2: butuh array objects [{id: '...', text: '...'}]
        $results = [];
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $item) {
                // Sesuai screenshot, label select2 menampilkan: {subdistrict_name}, {city_name}, dll.
                // Tapi data dari API biasanya bernama 'name' atau kita bisa gabungkan.
                // Mari asumsikan API mengembalikan 'name' yang berisi nama daerah lengkap
                $results[] = [
                    'id' => $item['id'],
                    'text' => $item['name'] ?? ($item['subdistrict_name'] . ', ' . $item['city_name'] . ', ' . $item['province_name'])
                ];
            }
        }

        return $this->response->setJSON(['results' => $results]);
    }

    /**
     * Endpoint untuk menghitung biaya pengiriman (ongkir)
     */
    public function calculateCost()
    {
        // Mengambil data dari request POST (misal dari form checkout atau AJAX)
        $origin = $this->request->getPost('origin') ?? 64999; // Default Pedurungan Tengah sesuai instruksi
        $destination = $this->request->getPost('destination');
        $weight = $this->request->getPost('weight') ?? 1000;
        $courier = $this->request->getPost('courier') ?? 'jne'; 

        $postFields = http_build_query([
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier
        ]);

        $response = $this->rajarongkirRequest('calculate/domestic-cost', 'POST', $postFields);
        return $this->response->setJSON(json_decode($response));
    }
}
