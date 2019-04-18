@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Aktivitas terkini</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Dibawah adalah data aktivitas terkini Mikrotik Anda.</h6>
                </div>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th>Waktu</th>
                            <th>Topik</th>
                            <th>Pesan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td>{{ $activity['time'] }}</td>
                                <td>{{ $activity['topics'] }}</td>
                                <td>{{ $activity['message'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
