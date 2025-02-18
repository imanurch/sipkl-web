@extends('layouts.app')

@section('page-title', 'Logbook Bimbingan')
@section('profil', 'Advisor')
@section('content')

    <div class="space-y-8">
        <div class="grid md:grid-cols-2 gap-4">
            <div class="input-group">
                <label class="input-label" for="">Nama</label>
                <input name="name" class="input" type="text" value="{{ $studentData->name ?? '' }}" disabled>
            </div>
            <div class="input-group">
                <label class="input-label" for="">Jurusan</label>
                <input name="department" class="input" type="text" value="{{ $studentData->department->name ?? '' }}"
                    disabled>
            </div>
            <div class="input-group">
                <label class="input-label" for="">NISN</label>
                <input name="nisn" class="input" type="text" value="{{ $studentData->nisn ?? '' }}" disabled>
            </div>
            <div class="input-group">
                <label class="input-label" for="">Industri</label>
                <input name="industry" class="input" type="text" value="{{ $studentData->industry ?? '' }}" disabled>
            </div>
        </div>

        <div class="space-y-8">
            @foreach ($logbookData as $month => $logs)
                <x-logbook.content_group_logbook month="{{ $month }}">
                    <x-slot name="logbookContent">
                        @foreach ($logs as $log)
                            {{-- <x-logbook.content_logbook id="{{ $log->id }}" submission="{{ $log->activities ? 'complete' : 'incomplete' }}"> --}}
                            <x-logbook.content_logbook :data="$log">
                                <x-slot name="period">{{ $log->start_date }} s/d {{ $log->end_date }}</x-slot>
                                <x-slot name="content">{{ $log->activities }}</x-slot>
                            </x-logbook.content_logbook>
                        @endforeach
                    </x-slot>
                </x-logbook.content_group_logbook>
            @endforeach
        </div>
    </div>

@endsection
