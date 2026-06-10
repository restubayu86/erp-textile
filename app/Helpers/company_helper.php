<?php

if (!function_exists('getCompanyProfile')) {
    function getCompanyProfile()
    {
        $db = \Config\Database::connect();

        // Cek apakah tabel company_profile ada
        if (!$db->tableExists('company_profile')) {
            return [
                'name' => 'PT. SINAR CONTINENTAL',
                'address' => 'PT. Sinar Continental Jl. Industri II No. 20, Leuwigajah Cimahi 40535. Jawa Barat',
                'phone' => '(+62) 22 6030 500',
                'fax' => '',
                'email' => '',
                'website' => '',
                'logo_left' => null,
                'logo_right' => null,
            ];
        }

        $profile = $db->table('company_profile')->get()->getRowArray();

        return [
            'name' => $profile['company_name'] ?? 'PT. SINAR CONTINENTAL',
            'address' => $profile['address'] ?? 'PT. Sinar Continental Jl. Industri II No. 20, Leuwigajah Cimahi 40535. Jawa Barat',
            'phone' => $profile['phone'] ?? '(+62) 22 6030 500',
            'fax' => $profile['fax'] ?? '',
            'email' => $profile['email'] ?? '',
            'website' => $profile['website'] ?? '',
            'logo_left' => $profile['logo_left'] ?? null,
            'logo_right' => $profile['logo_right'] ?? null,
        ];
    }
}

if (!function_exists('getCompanyLogo')) {
    function getCompanyLogo($position = 'left')
    {
        // Cek file manual di folder assets
        $filePath = FCPATH . "assets/img/logo-{$position}.png";

        if (file_exists($filePath)) {
            return base_url("assets/img/logo-{$position}.png");
        }

        // Cek dari database
        $profile = getCompanyProfile();
        $logoField = 'logo_' . $position;

        if (!empty($profile[$logoField]) && file_exists(FCPATH . 'assets/img/' . $profile[$logoField])) {
            return base_url('assets/img/' . $profile[$logoField]);
        }

        // Return SVG placeholder
        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='1'%3E%3Crect x='2' y='2' width='20' height='20' rx='2'/%3E%3Ctext x='12' y='16' font-size='8' text-anchor='middle' fill='%23999'%3E{$position}%3C/text%3E%3C/svg%3E";
    }
}

if (!function_exists('formatCompanyAddress')) {
    function formatCompanyAddress()
    {
        $profile = getCompanyProfile();
        $address = $profile['address'];
        $phone = $profile['phone'];
        $fax = $profile['fax'];
        $email = $profile['email'];
        $website = $profile['website'];

        $result = $address;
        if ($phone) $result .= "<br>Telp: {$phone}";
        if ($fax) $result .= " | Fax: {$fax}";
        if ($email) $result .= "<br>Email: {$email}";
        if ($website) $result .= " | Website: {$website}";

        return $result;
    }
}
