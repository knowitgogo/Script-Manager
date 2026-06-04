@extends('layouts.admin')

@section('title', __('messages.deleted_managers'))

@section('admin_content')
    <div class="container">
        <div class="card">

            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <h1>{{ __('messages.deleted_managers') }}</h1>
                    <p>{{ __('messages.deleted_managers') }}.</p>
                </div>

                <a href="{{ route('admin.managers.index') }}" class="button">
                    {{ __('messages.active_managers') }}
                </a>
            </div>

            @if ($managers->isEmpty())
                <div class="empty">
                    {{ __('messages.no_deleted_managers') }}
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.label_name') }}</th>
                            <th>{{ __('messages.label_email') }}</th>
                            <th>{{ __('messages.deleted_at') ?? 'Deleted At' }}</th>
                            <th>{{ __('messages.label_actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($managers as $manager)
                            <tr>
                                <td>{{ $manager->name }}</td>
                                <td>{{ $manager->email }}</td>
                                <td>{{ $manager->deleted_at->format('Y-m-d H:i') }}</td>

                                <td>
                                    <div style="display:flex;gap:8px;">

                                        <form method="POST" action="{{ route('admin.managers.restore', $manager->id) }}">
                                            @csrf

                                            <button type="submit" class="button success">
                                                {{ __('messages.restore') }}
                                            </button>
                                        </form>



                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif

        </div>
    </div>
@endsection
