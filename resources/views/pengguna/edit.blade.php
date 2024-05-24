@extends('template.index')
 
@section('page-title')
    <x-page-title title="Pengguna">
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
            <li class="breadcrumb-item active">Ubah</li>
            </ol>
        </nav>
    </x-page-title>
@stop

@section('content') 
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-12">
                    {{ Utility::showNotif() }}
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('pengguna.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="id" class="@error('id') is-invalid @enderror" value="{{ old('id',$Pengguna->GetId()) }}">
                                    <div class="mb-3">
                                        <x-input-text title="Nama" name="nama" default="{{ old('nama',$Pengguna->GetName()) }}"/>
                                    </div>
                                    <div class="mb-3">
                                        <x-input-text title="Username" name="username" default="{{ old('username',$Pengguna->GetUsername()) }}"/>
                                    </div>
                                    <div class="mb-3">
                                        <x-input-text title="Password" name="password" default=""/>
                                        <small>*kosongkan jika tidak ingin ubah password</small>
                                    </div>
                                    <div class="mb-3">
                                        <x-input-select title="Level" name="level" class="level"></x-input-select>
                                    </div>
                                </div>
                                
                                <input type="submit" name="submit" class="btn btn-primary" value="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script type="text/javascript" src="{{ Utility::loadAsset('my.js') }}"></script>
    <script>
        $(document).ready(function () {
            const level = [
                {
                    "id":"admin",
                    "text":"Admin",
                },
                {
                    "id":"sdm",
                    "text":"SDM",
                },
                {
                    "id":"warek",
                    "text":"Warek",
                },
                {
                    "id":"keuangan",
                    "text":"Keuangan",
                },
                {
                    "id":"baum",
                    "text":"BAUM",
                },
            ]
            load_dropdown('.level', level, null, "{{ old('level',$Pengguna->GetLevel()) }}", '-- Pilih Level --');
        });
    </script>
@endpush