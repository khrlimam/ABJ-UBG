@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        DHCP Network
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">Dibawah adalah daftar DHCP Network yang ada pada mikrotik
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
                            <th>Address</th>
                            <th>Gateway</th>
                            <th>DNS Servers</th>
                            <th>Domain</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dhcpNetworks as $network)
                            <tr>
                                <td>{{ $network['address'] }}</td>
                                <td>{{ $network['gateway'] }}</td>
                                <td>
                                    <h6>
                                        @foreach(explode(",", $network['dns-server']) as $dns)
                                            <span class="badge badge-pill badge-info">{{ $dns }}</span>
                                        @endforeach
                                    </h6>
                                </td>
                                <td>{{ array_key_exists('domain', $network)? $network['domain']: '-'}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection