<?php

namespace Architecture\Domain\RuleValidationRequest\SPPD;

class CreateSPPDRuleReq{
    public static function create() { 
        return [
            "jenis_sppd"        => "required",
            "tanggal_berangkat" => "required|date",
            "tanggal_kembali"   => "required|date|after_or_equal:tanggal_berangkat",
            "tujuan"            => "required",
            "keterangan"        => "required",
            "anggota"           => ['required', 'array'],
            "anggota.*.nidn"    => 'required_if:anggota.*.nip,null',
            "anggota.*.nip"     => 'required_if:anggota.*.nidn,null',
        ]; 
    }

    // public static function message() { 
    //     return [
    //         "jenis_sppd"        => "required",
    //         "tanggal_berangkat" => "required|date",
    //         "tanggal_kembali"   => "required|date|after_or_equal:tanggal_berangkat",
    //         "tujuan"            => "required",
    //         "keterangan"        => "required",
    //         "anggota"           => ['required', 'array'],
    //         "anggota.*.nidn"    => 'required_if:anggota.*.nip,null',
    //         "anggota.*.nip"     => 'required_if:anggota.*.nidn,null',
    //     ]; 
    // }
}