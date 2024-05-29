<?php

namespace Architecture\Domain\RuleValidationRequest\KlaimAbsen;

class UpdateKlaimAbsenRuleReq{
    public static function create() { 
        return [
            "id"                => "required",
            "tanggal_absen"     => "required",
            "jam_masuk"         => "required",
            "jam_keluar"        => "required",
            "tujuan"            => "required",
            // "dokumen"           => "required|file|mimes:pdf,jpg,jpeg,png|max:10000",
            // "catatan"           => "required",
        ]; 
    }
}