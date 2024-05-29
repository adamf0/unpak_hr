<?php

namespace Architecture\Domain\RuleValidationRequest\KlaimAbsen;

class CreateKlaimAbsenRuleReq{
    public static function create() { 
        return [
            "tanggal_absen"     => "required",
            "jam_masuk"         => "required",
            "jam_keluar"        => "required",
            "tujuan"            => "required",
            "dokumen"           => "required|file|mimes:pdf,jpg,jpeg,png|max:10000",
            // "catatan"           => "required",
        ]; 
    }
}