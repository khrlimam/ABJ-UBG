@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pool</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Dibawah adalah daftar IP Pool yang ada pada mikrotik
                        Anda.</h6>
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
                            <th>Nama</th>
                            <th>Rentang</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pools as $pool)
                            <tr>
                                <td>{{ $pool['name'] }}</td>
                                <td>
                                    <h6>
                                        @foreach(explode(',', $pool['ranges']) as $range)
                                            <span class="badge badge-pill badge-info">{{ $range }}</span>
                                        @endforeach
                                    </h6>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection