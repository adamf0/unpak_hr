<?php

namespace Architecture\Shared\Facades;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use ReflectionClass;
use ReflectionProperty;
use Architecture\Domain\ValueObject\Date;
use Carbon\Carbon;

trait Utility
{
    public static function isLate($tanggal_jam_masuk = null, $tanggal = null)
    {
        $masuk = new Date($tanggal_jam_masuk);
        $keluar = new Date($tanggal . " 08:01:00");
        // dump([
        //     $tanggal_jam_masuk, $tanggal." 08:01:00", $masuk->isGreater($keluar)
        // ]);
        return $masuk->isGreater($keluar);
    }
    public static function is8Hour($tanggal = null, $tanggal_jam_masuk = null, $tanggal_jam_keluar = null)
    {
        if (!empty($tanggal_jam_keluar) && !self::isLate($tanggal_jam_masuk, $tanggal)) {
            $jam_pulang = "14:59:00";
            if (($tanggal != date('Y-m-d') ? Carbon::parse($tanggal) : Carbon::now())->setTimezone('Asia/Jakarta')->dayOfWeek == Carbon::FRIDAY) {
                $jam_pulang = "13:59:00";
            }
            // elseif (($tanggal!=date('Y-m-d')? Carbon::parse($tanggal):Carbon::now())->setTimezone('Asia/Jakarta')->dayOfWeek == Carbon::SATURDAY) {
            //     $jam_pulang = "11:59:00";
            // }
            $aturanKeluar = new Date($tanggal . " $jam_pulang");
            $keluar = new Date($tanggal_jam_keluar);

            // dump([
            //     $tanggal." $jam_pulang", $tanggal_jam_keluar, $keluar->isGreater($aturanKeluar)
            // ]);
            return $keluar->isGreater($aturanKeluar);
        } else if (!empty($tanggal_jam_keluar) && self::isLate($tanggal_jam_masuk, $tanggal)) {
            $aturanKeluar = new Date(Carbon::parse($tanggal_jam_masuk)->setTimezone('Asia/Jakarta')->addHour(7)->toISOString());
            $keluar = new Date($tanggal_jam_keluar);
            return $keluar->isGreater($aturanKeluar);
        } else
            return false;
    }
    public static function pushData($data = [])
    {
        $response = Http::withBody(json_encode($data), 'json')
            ->withHeaders([
                'Accept' => '*/*',
                'Authorization' => 'Bearer ' . env('token_ibnu', null),
                'Content-Type' => 'application/json',
            ])
            ->post(env('url_ibnu', 'localhost') . '/api/darihrportal');
        return $response->body();
    }
    public static function showNotif()
    {
        return AlertNotif::show();
    }
    public static function stateMenu($segment = [], Request $request, $step = 0)
    {
        if (count($segment) == 0) throw new Exception('invalid argument');
        if ($step > 0) {
            $result = true;
            for ($i = 0; $i <= $step; $i++) {
                if (!in_array($request->segments()[$i], $segment)) {
                    $result = false;
                    break;
                }
            }
            return $result;
        }
        return in_array($request->segments()[$step], $segment);
    }
    public static function getName()
    {
        return Session::get('name') ?? 'N/A';
    }
    public static function getLevel($toLower = false)
    {
        return match (Session::get('levelActive')) {
            "admin"     => $toLower ? "admin" : "Admin",
            "sdm"       => $toLower ? "sdm" : "SDM",
            "dosen"     => $toLower ? "dosen" : "Dosen",
            "pegawai"   => $toLower ? "pegawai" : "Pegawai",
            "warek"     => $toLower ? "warek" : "Warek",
            "keuangan"  => $toLower ? "keuangan" : "Keuangan",
            default     => $toLower ? "n/a" : "N/A"
        };
    }
    public static function getLevels()
    {
        return Session::get('level');
    }
    public static function hasMultiLevel()
    {
        return (Session::get('level') ?? collect([]))->count() > 1;
    }
    public static function hasSDM()
    {
        return Session::get('levelActive') == "sdm";
    }
    public static function hasDosen()
    {
        return Session::get('levelActive') == "dosen";
    }
    public static function hasPegawai()
    {
        return Session::get('levelActive') == "pegawai";
    }
    public static function hasWarek()
    {
        return Session::get('levelActive') == "warek";
    }
    public static function hasAdmin()
    {
        return Session::get('levelActive') == "admin";
    }
    public static function hasUser()
    {
        return self::hasDosen() || self::hasPegawai();
    }

    public static function loadAsset($path)
    {
        return env('DEPLOY', 'dev') == 'dev' ? asset($path) : secure_asset($path);
    }
    public static function toStatusPengajuan($val)
    {
        return match ($val) {
            "menunggu"  => null,
            "verif"     => 1,
            "tolak"     => 0,
            default     => -1
        };
    }
    public static function checkValidList(?Collection $list = null, object $targetTypeOf, $label)
    {
        if (is_null($list)) throw new Exception("value list can't be null");
        if (is_null($targetTypeOf)) throw new Exception("value targetTypeOf can't be null");
        if (is_null($label)) throw new Exception("value label can't be null");

        foreach ($list as $item) {
            if (!is_a($item, get_class($targetTypeOf))) throw new Exception("invalid typeOf in $label");
        }
        return true;
    }

    public static function checkValidStep($step)
    {
        $stepNumber = (int) str_replace('step', '', $step);
        if (
            !in_array($stepNumber, [1, 2, 3, 4]) ||
            (is_integer($step) && !in_array($step, [1, 2, 3, 4]))
        ) throw new Exception("invalid to access step");

        return true;
    }
    public static function createStepper($maxStep, $startStep = 1)
    {
        $listStepper = collect([]);
        for ($i = 1; $i <= 4; $i++) {
            $listStepper->push((object)[
                'isActive'      => $i == $startStep,
                'isDisable'     => $i > $startStep,
                'numberStep'    => $i,
                'isEndStep'     => $i == $maxStep,
            ]);
        }
        return $listStepper;
    }
    public static function getAttributClass($class, $except = [], $mapping = [])
    {
        $reflect = new ReflectionClass($class);
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $index = 0;
        return collect($properties)->mapWithKeys(function ($property) use (&$index, &$class, &$except, &$mapping) {
            $propertyName = isset($mapping[$property->getName()]) ? $mapping[$property->getName()] : $property->getName();
            $propertyValue = $property->getValue($class);

            // if(!($propertyValue instanceof TypeStatusAdministrasi)) return [];
            if (in_array($propertyName, $except)) return [];

            $output = [$index => (object)["key" => $propertyName, "value" => $propertyValue]];
            $index++;
            return $output;
        });
    }

    static function getSinta($author_id = 0, $maxRetry = 3)
    {
        $url = env("URL_SINTA", "http://localhost:81") . "/$author_id";
        for ($attempt = 1; $attempt <= $maxRetry; $attempt++) {
            try {
                $response = Http::get($url);
                $response->header('Access-Control-Allow-Origin', '*');

                $response = $response->json();
                return (int) str_replace(".", "", $response["data"]["score"]["overall"]);
            } catch (\Exception $e) {
                if ($attempt < $maxRetry) {
                    sleep(1); // Delay 1 detik sebelum retry
                }
            }
        }
        return 0;
    }
}
