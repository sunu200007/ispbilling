@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-6">Dashboard</h2>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-sm text-gray-500">Total Pelanggan</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $total_pelanggan }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-sm text-gray-500">Pelanggan Aktif</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $pelanggan_aktif }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-sm text-gray-500">Pelanggan Isolir</p>
        <p class="text-3xl font-bold text-red-500 mt-1">{{ $pelanggan_isolir }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-sm text-gray-500">Invoice Unpaid</p>
        <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $invoice_unpaid }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-sm text-gray-500">Invoice Paid</p>
        <p class="text-3xl font-bold text="green-600 mt-1">{{ $invoice_paid }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-sm text-gray-500">Total ODP</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $total_odp }}</p>
    </div>
</div>
@endsection