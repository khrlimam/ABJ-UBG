@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{route('dhcp-server.create')}}" class="btn btn-primary float-right">Setup DHCP
                            Server Baru</a>
                        DHCP Server
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">Dibawah adalah daftar DHCP Server yang ada pada mikrotik
                        Anda.</h6>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('fail'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('fail') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>Nama</th>
                            <th>Interface</th>
                            <th>Pool</th>
                            <th>Lease Time</th>
                            <th>???</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dhcpServers as $server)
                            <tr hover-cursor
                                onclick="window.location='{{ route('dhcp-server.show', $server['.id']) }}'">
                                <td class="text-md-right">
                                    @if ($server['disabled'] == 'true') <span class="badge badge-pill badge-danger">Disabled</span> @endif
                                    @if ($server['invalid'] == 'true') <span class="badge badge-pill badge-warning">Invalid</span> @endif
                                </td>
                                <td>{{ $server['name'] }}</td>
                                <td>{{ $server['interface'] }}</td>
                                <td>{{ $server['address-pool'] }}</td>
                                <td>{{ $server['lease-time'] }}</td>
                                <td>
                                    <form class="btn-group btn-group-sm" role="group" aria-label="..."
                                          action="{{ route('dhcp-server.destroy', $server['.id']) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        @if ($server['disabled'] == 'true')
                                            <a href="{{ route('dhcp-server.toggle', ['id' => $server['.id'],'toggle' => 'no']) }}"
                                               class="btn btn-info">Enable</a>
                                        @else
                                            <a href="{{ route('dhcp-server.toggle', ['id' => $server['.id'],'toggle' => 'yes']) }}"
                                               class="btn btn-info">Disable</a>
                                        @endif
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
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