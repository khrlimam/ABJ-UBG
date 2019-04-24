@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{route('dhcp-server.create')}}" class="btn btn-outline-primary float-right"><i
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
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {!! session('fail') !!}
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
                                    @if ($server['disabled'] == 'true') <span data-toggle="tooltip" title="Disabled"
                                                                              class="badge badge-pill badge-danger"><i
                                                class="fa fa-times"></i></span> @endif
                                    @if ($server['invalid'] == 'true') <span data-toggle="tooltip" title="Invalid"
                                                                             class="badge badge-pill badge-warning">I</span> @endif
                                    @if(!empty($clientConnected[$server['name']]))
                                        <span data-toggle="tooltip" title="Lihat rincian client"
                                              onclick='showClients("{{ $server['name'] }}")' hover-cursor
                                              class="badge badge-primary">
                                        <i class="fa fa-laptop" aria-hidden="true"></i> {{ $clientConnected[$server['name']]->count() }}</span>
                                    @endif
                                </td>
                                <td>{{ $server['name'] }}</td>
                                <td>{{ $server['interface'] }}</td>
                                <td>{{ $server['address-pool'] }}</td>
                                <td>{{ $server['lease-time'] }}</td>
                                <td>
                                    <form onsubmit="event.preventDefault(); confirmDeleteForm(this)"
                                          class="btn-group btn-group-sm" role="group" aria-label="..."
                                          action="{{ route('dhcp-server.destroy', $server['.id']) }}" method="POST">
                                        <a data-toggle="tooltip" title="Lihat rincian"
                                           href="{{ route('dhcp-server.show', $server['.id']) }}"
                                           class="btn btn-success"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <input type="hidden" name="_method" value="DELETE">
                                        @csrf
                                        @if ($server['disabled'] == 'true')
                                            <a data-toggle="tooltip" title="Toggle"
                                               href="{{ route('dhcp-server.toggle', ['id' => $server['.id'],'toggle' => 'no']) }}"
                                               class="btn btn-warning">Enable</a>
                                        @else
                                            <a data-toggle="tooltip" title="Toggle"
                                               href="{{ route('dhcp-server.toggle', ['id' => $server['.id'],'toggle' => 'yes']) }}"
                                               class="btn btn-warning">Disable</a>
                                        @endif
                                        <a data-toggle="tooltip" title="Edit data"
                                           href="{{ route('dhcp-server.edit', $server['.id']) }}"
                                           class="btn btn-dark"><i class="fa fa-edit"
                                                                   aria-hidden="true"></i></a>
                                        <button data-toggle="tooltip" title="Hapus data"
                                                type="submit" class="btn btn-danger"><i class="fa fa-trash"
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Daftar client <label
                                id="dhcp-server-name"></label></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th>Address</th>
                        <th>MAC Address</th>
                        <th>Hostname</th>
                    </tr>
                    </thead>
                    <tbody id="tbody-dhcp-server">
                    </tbody>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({
                placement: 'bottom'
            })
        });

        function showClients(name) {
            // clients = JSON.parse($clients);
            $("#dhcp-server-name").text(name);
            tbody = document.getElementById("tbody-dhcp-server");
            let listClients = clients[name];
            console.log(listClients);
            tbody.childNodes.forEach((child) => tbody.removeChild(child));
            if (listClients !== undefined) {
                listClients.forEach((item) => {
                    let newRow = tbody.insertRow(tbody.childElementCount);
                    newRow.insertCell(0).innerText = item.address;
                    newRow.insertCell(1).innerText = item['mac-address'];
                    newRow.insertCell(2).innerText = item['host-name'];
                });
                $("#exampleModal").modal('show');
            }
        }

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
                    if (value === 'yes') form.submit();
                });
        }

        let clients = @json($clientConnected);
    </script>
@endsection