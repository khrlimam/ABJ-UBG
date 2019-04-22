@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('dhcp-server.edit', request()->route('dhcp_server')) }}"
                       class="btn btn-outline-dark float-right"><i class="fa fa-edit"></i> Edit Data</a>
                    <h4 class="card-title">Rincian data DHCP Server</h4>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Dibawah adalah rincian data DHCP Server dengan checksum: {{ request()->route('dhcp_server') }}
                    </h6>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }} <a href="{{ route('dhcp-server.index') }}" class="alert-link">Lihat
                                semua
                                data DHCP Server</a>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <br>
                    <h5 class="card-title">Data IP Pool</h5>

                    @foreach(\Illuminate\Support\Arr::except($pool->getData(), ['ranges']) as $key => $value)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ ucfirst($key) }}</label>

                            <div class="col-md-9">
                                <label class="form-control">{{ $value }}</label>
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Pool Range</label>

                        <div class="col-md-9">
                            @foreach($pool->getRanges() as $range)
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ $loop->iteration }}</span>
                                    </div>
                                    <label type="text" class="form-control text-md-right" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">{{ $range[0] }}</label>
                                    <label type="text" class="form-control" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">{{ $range[1] }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <br>
                    <h5 class="card-title">Data DHCP Network</h5>

                    @foreach(\Illuminate\Support\Arr::except($network->getNonEmptyValue(), ['dns-server']) as $key => $value)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ ucfirst($key) }}</label>

                            <div class="col-md-9">
                                <label class="form-control">{{ $value }}</label>
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">DNS Servers</label>

                        <div class="col-md-9">
                            @foreach($network->getDnsServers() as $dns)
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ $loop->iteration }}</span>
                                    </div>
                                    <label type="text" class="form-control" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">{{ $dns }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <br>
                    <h5 class="card-title">Data DHCP Server</h5>

                    @foreach(\Illuminate\Support\Arr::except($dhcp->getNonEmptyValue(), ['disabled','invalid']) as $key => $value)

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ ucfirst($key) }}</label>

                            <div class="col-md-9">
                                <label class="form-control">{{ $value }}</label>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group row mb-0">
                        <div class="col-md-9 offset-md-3">
                            <h5>
                                @if ($dhcp->getNonEmptyValue()['disabled'] == 'true') <span
                                        class="badge badge-pill badge-danger">Disabled</span> @endif
                                @if ($dhcp->getNonEmptyValue()['invalid'] == 'true') <span
                                        class="badge badge-pill badge-warning">Invalid</span> @endif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection