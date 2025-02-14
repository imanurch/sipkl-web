@extends('layouts.app')

@section('page-title', 'Logbook Bimbingan')
@section('profil', 'Advisor')
@section('content')

<div class="grid md:grid-cols-2 gap-4">
    <div class="input-group">
        <label class="input-label" for="">Nama</label>
        <input name="name" class="input" type="text" value="{{ $data->name }}" disabled>    
    </div> 
    <div class="input-group">
        <label class="input-label" for="">Jurusan</label>
        <input name="department" class="input" type="text" value="{{ $data->department->name }}" disabled>    
    </div> 
    <div class="input-group">
        <label class="input-label" for="">NISN</label>
        <input name="nisn" class="input" type="text" value="{{ $data->nisn }}" disabled>    
    </div> 
    <div class="input-group">
        <label class="input-label" for="">Industri</label>
        <input name="industry" class="input" type="text" value="{{ $data->industry }}" disabled>    
    </div> 
</div>

<div class="space-y-4">
    <x-content_group_logbook month="1">
        <x-slot name="logbookContent">
            <x-content_logbook>
                <x-slot name="period">Minggu 1</x-slot>
                <x-slot name="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a rhoncus erat. Nulla et diam metus. Nulla erat purus, aliquet ut nisi sit amet, posuere congue magna. Aenean hendrerit et dolor in tempor. Quisque neque magna, lacinia sed ex at, lobortis eleifend eros. Ut non velit dapibus tortor tristique lacinia. Fusce eu sollicitudin arcu. Proin cursus nulla sit amet mattis luctus. Nullam fringilla arcu vel quam tincidunt tristique. Aliquam porta justo a bibendum interdum. Vestibulum vulputate ullamcorper eleifend. Donec rutrum massa eu est fringilla congue.</x-slot>
            </x-content_logbook>
            <x-content_logbook>
                <x-slot name="period">Minggu 1</x-slot>
                <x-slot name="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a rhoncus erat. Nulla et diam metus. Nulla erat purus, aliquet ut nisi sit amet, posuere congue magna. Aenean hendrerit et dolor in tempor. Quisque neque magna, lacinia sed ex at, lobortis eleifend eros. Ut non velit dapibus tortor tristique lacinia. Fusce eu sollicitudin arcu. Proin cursus nulla sit amet mattis luctus. Nullam fringilla arcu vel quam tincidunt tristique. Aliquam porta justo a bibendum interdum. Vestibulum vulputate ullamcorper eleifend. Donec rutrum massa eu est fringilla congue.</x-slot>
            </x-content_logbook>
            <x-content_logbook>
                <x-slot name="period">Minggu 1</x-slot>
                <x-slot name="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a rhoncus erat. Nulla et diam metus. Nulla erat purus, aliquet ut nisi sit amet, posuere congue magna. Aenean hendrerit et dolor in tempor. Quisque neque magna, lacinia sed ex at, lobortis eleifend eros. Ut non velit dapibus tortor tristique lacinia. Fusce eu sollicitudin arcu. Proin cursus nulla sit amet mattis luctus. Nullam fringilla arcu vel quam tincidunt tristique. Aliquam porta justo a bibendum interdum. Vestibulum vulputate ullamcorper eleifend. Donec rutrum massa eu est fringilla congue.</x-slot>
            </x-content_logbook>
        </x-slot>
    </x-content_group_logbook>
    <x-content_group_logbook month="2">
        <x-slot name="logbookContent">
            <x-content_logbook>
                <x-slot name="period">Minggu 1</x-slot>
                <x-slot name="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a rhoncus erat. Nulla et diam metus. Nulla erat purus, aliquet ut nisi sit amet, posuere congue magna. Aenean hendrerit et dolor in tempor. Quisque neque magna, lacinia sed ex at, lobortis eleifend eros. Ut non velit dapibus tortor tristique lacinia. Fusce eu sollicitudin arcu. Proin cursus nulla sit amet mattis luctus. Nullam fringilla arcu vel quam tincidunt tristique. Aliquam porta justo a bibendum interdum. Vestibulum vulputate ullamcorper eleifend. Donec rutrum massa eu est fringilla congue.</x-slot>
            </x-content_logbook>
            <x-content_logbook>
                <x-slot name="period">Minggu 1</x-slot>
                <x-slot name="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a rhoncus erat. Nulla et diam metus. Nulla erat purus, aliquet ut nisi sit amet, posuere congue magna. Aenean hendrerit et dolor in tempor. Quisque neque magna, lacinia sed ex at, lobortis eleifend eros. Ut non velit dapibus tortor tristique lacinia. Fusce eu sollicitudin arcu. Proin cursus nulla sit amet mattis luctus. Nullam fringilla arcu vel quam tincidunt tristique. Aliquam porta justo a bibendum interdum. Vestibulum vulputate ullamcorper eleifend. Donec rutrum massa eu est fringilla congue.</x-slot>
            </x-content_logbook>
            <x-content_logbook>
                <x-slot name="period">Minggu 1</x-slot>
                <x-slot name="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a rhoncus erat. Nulla et diam metus. Nulla erat purus, aliquet ut nisi sit amet, posuere congue magna. Aenean hendrerit et dolor in tempor. Quisque neque magna, lacinia sed ex at, lobortis eleifend eros. Ut non velit dapibus tortor tristique lacinia. Fusce eu sollicitudin arcu. Proin cursus nulla sit amet mattis luctus. Nullam fringilla arcu vel quam tincidunt tristique. Aliquam porta justo a bibendum interdum. Vestibulum vulputate ullamcorper eleifend. Donec rutrum massa eu est fringilla congue.</x-slot>
            </x-content_logbook>
        </x-slot>
    </x-content_group_logbook>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection