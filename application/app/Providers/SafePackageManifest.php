<?php

// app/Providers/SafePackageManifest.php

namespace App\Providers;

use Illuminate\Foundation\PackageManifest as BasePackageManifest;

class SafePackageManifest extends BasePackageManifest
{
    public function build()
    {
        $packages = [];

        if ($this->files->exists($path = $this->vendorPath.'/composer/installed.json')) {
            $installed = json_decode($this->files->get($path), true);
            $packages = $installed['packages'] ?? $installed;

            // Filtrar Breeze y cualquier paquete problemático
            $packages = array_filter($packages, function ($package) {
                $blacklist = ['laravel/breeze'];

                return ! in_array($package['name'] ?? '', $blacklist);
            });
        }

        $this->write($packages);

        return $packages;
    }
}
