@extends('layouts.cashier-layouts')

@section('content-cashier')
    <h1 class="font-bold text-2xl">Edit Member</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cashier.member.update', $member->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Member</label>
                    <input type="text" name="name" required value="{{ $member->name }}"
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">No Telpon</label>
                    <input type="text" name="phone_number" required value="{{ $member->phone_number }}"
                        class="border border-gray-300 focus:border-blue-500 rounded-lg px-4 h-10 w-full outline-none">
                </div>
            </div>

            <!-- Hidden input for points to maintain current value -->
            <input type="hidden" name="points" value="{{ $member->points }}">

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                Perbarui Data Member
            </button>
        </form>
    </div>
@endsection
