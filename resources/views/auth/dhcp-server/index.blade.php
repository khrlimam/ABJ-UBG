@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{route('dhcp-server.create')}}" class="btn btn-primary float-right"><i
                                    class="fa fa-plus" aria-hidden="true"></i> DHCP Server</a>
                        DHCP Server
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">Dibawah adalah daftar DHCP Server yang ada pada MikroTik
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
                            <th>Server name</th>
                            <th>Interface</th>
                            <th>Pool</th>
                            <th>Lease Time</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dhcpServers as $server)
                            <tr>
                                <td class="text-md-right">
                                    @if ($server['disabled'] == 'true') <span class="badge badge-pill badge-danger">Disabled</span> @endif
                                    @if ($server['invalid'] == 'true') <span class="badge badge-pill badge-warning">Invalid</span> @endif
                                </td>
                                <td>
                                    {{ $server['name'] }} <br>
                                    <span class="badge badge-primary"><i class="fa fa-laptop"
                                                                         aria-hidden="true"></i> DHCP client: {{ !empty($clientConnected[$server['name']])? $clientConnected[$server['name']]->count():0 }}</span>
                                </td>
                                <td>{{ $server['interface'] }}</td>
                                <td>{{ $server['address-pool'] }}</td>
                                <td>{{ $server['lease-time'] }}</td>
                                <td>
                                    <form onsubmit="event.preventDefault(); confirmDeleteForm(this)"
                                          class="btn-group btn-group-sm" role="group" aria-label="..."
                                          action="{{ route('dhcp-server.destroy', $server['.id']) }}" method="POST">
                                        <a href="{{ route('dhcp-server.show', $server['.id']) }}"
                                           class="btn btn-success"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <input type="hidden" name="_method" value="DELETE">
                                        @csrf
                                        @if ($server['disabled'] == 'true')
                                            <a href="{{ route('dhcp-server.toggle', ['id' => $server['.id'],'toggle' => 'no']) }}"
                                               class="btn btn-warning">Enable</a>
                                        @else
                                            <a href="{{ route('dhcp-server.toggle', ['id' => $server['.id'],'toggle' => 'yes']) }}"
                                               class="btn btn-warning">Disable</a>
                                        @endif
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"
                                                                                        aria-hidden="true"></i></button>
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
@section('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        function confirmDeleteForm(form) {
            swal("Apakah anda yakin ingin menghapus data DHCP Server?", {
                buttons: {
                    cancel: "Kembali",
                    yes: {
                        text: "Ya",
                        value: "yes",
                    },
                },
            })
                .then((value) => {
                    if (value == 'yes') form.submit();
                });
        }
    </script>
@endsection