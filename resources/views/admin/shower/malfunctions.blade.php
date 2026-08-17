@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-brand-blue hover:underline">← ダッシュボードに戻る</a>

    <h1 class="text-2xl font-bold text-slate-800 mt-4 mb-6">シャワー故障報告 - 履歴</h1>

    @if (session('success'))
        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="bg-slate-100 text-left">
                <th class="p-2">日時</th>
                <th class="p-2">性別</th>
                <th class="p-2">番号</th>
                <th class="p-2">状態</th>
                <th class="p-2">報告者</th>
                <th class="p-2">コメント</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($history as $report)
                <tr class="border-b border-slate-100">
                    <td class="p-2">{{ $report->created_at->format('Y/m/d H:i') }}</td>
                    <td class="p-2">{{ $report->gender === 'male' ? '男子寮' : '女子寮' }}</td>
                    <td class="p-2">{{ $report->shower_number }}番</td>
                    <td class="p-2">{{ $report->status === 'broken' ? '故障報告' : '修理完了' }}</td>
                    <td class="p-2">{{ $report->user->name ?? '-' }}</td>
                    <td class="p-2">{{ $report->comment }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $history->links() }}
    </div>
</div>
@endsection