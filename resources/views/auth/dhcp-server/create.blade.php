@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Tambah DHCP Server</h4>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Silakan isi data DHCP Server sesuai kebutuhan Anda.
                    </h6>
                    @if (session('fail'))
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {!! session('fail') !!}
                        </div>
                    @endif
                    <br>
                    <form method="POST" action="{{ route('dhcp-server.store') }}">
                        @csrf

                        <h5 class="card-title">Form IP Pool</h5>

                        <div class="form-group row">
                            <label for="pool-name"
                                   class="col-md-3 col-form-label">{{ __('Nama') }} <span
                                        class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="pool-name" type="text" placeholder="Contoh: Pool1"
                                       class="form-control{{ $errors->has('pool-name') ? ' is-invalid' : '' }}"
                                       name="pool-name" value="{{ old('pool-name') }}" required autofocus>

                                @if ($errors->has('pool-name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pool-name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pool-range-begin"
                                   class="col-md-3 col-form-label">{{ __('Rentang awal') }} <span
                                        class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="pool-range-begin" type="text" placeholder="Contoh: 192.168.2.100"
                                       class="form-control{{ $errors->has('pool-range-begin') ? ' is-invalid' : '' }}"
                                       name="pool-range-begin" value="{{ old('pool-range-begin') }}" required autofocus>

                                @if ($errors->has('pool-range-begin'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pool-range-begin') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pool-range-end"
                                   class="col-md-3 col-form-label">{{ __('Rentang akhir') }} <span
                                        class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="pool-range-end" type="text"
                                       placeholder="Contoh: 192.168.2.200" value="{{old('pool-range-end')}}"
                                       class="form-control{{ $errors->has('pool-range-end') ? ' is-invalid' : '' }}"
                                       name="pool-range-end" required autofocus>

                                @if ($errors->has('pool-range-end'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pool-range-end') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <br>
                        <h5 class="card-title">Form DHCP Network</h5>

                        <div class="form-group row">
                            <label for="network-address"
                                   class="col-md-3 col-form-label">{{ __('Alamat jaringan') }} <span
                                        class="text-danger">*</span></label>

                            <div class="col-md-8">

                                <div class="row">
                                    <div class="col-md-8">
                                        <input id="network-address" type="text"
                                            placeholder="Contoh: 192.168.2.0"
                                            class="form-control{{ $errors->has('network-address') ? ' is-invalid' : '' }}"
                                            name="network-address" value="{{ old('network-address') }}" required autofocus>

                                        @if ($errors->has('network-address'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('network-address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <label class="col-md-1 col-form-label">/</label>
                                    <div class="col-md-3">
                                    <input id="network-subnetmask" type="number"
                                            placeholder="24"
                                            class="form-control{{ $errors->has('network-subnetmask') ? ' is-invalid' : '' }}"
                                            name="network-subnetmask" value="{{ old('network-subnetmask') }}" autofocus>

                                        @if ($errors->has('network-subnetmask'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('network-subnetmask') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="network-default-gateway"
                                   class="col-md-3 col-form-label">{{ __('Default gateway') }} <span
                                        class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="network-default-gateway" type="text"
                                       placeholder="Contoh: 192.168.2.1"
                                       class="form-control{{ $errors->has('network-default-gateway') ? ' is-invalid' : '' }}"
                                       name="network-default-gateway" value="{{ old('network-default-gateway') }}"
                                       required autofocus>

                                @if ($errors->has('network-default-gateway'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('network-default-gateway') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="network-dns1"
                                   class="col-md-3 col-form-label">{{ __('DNS 1') }} <span
                                        class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <input id="network-dns1" type="text"
                                       placeholder="Contoh: 1.0.0.1"
                                       class="form-control{{ $errors->has('network-dns.0') ? ' is-invalid' : '' }}"
                                       name="network-dns[]" value="{{ old('network-dns.0') }}"
                                       required autofocus>

                                @if ($errors->has('network-dns.0'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('network-dns.0') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="network-dns2" class="col-md-3 col-form-label">{{ __('DNS 2') }}</label>

                            <div class="col-md-8">
                                <input id="network-dns2" type="text" value="{{old('network-dns.1')}}"
                                       class="form-control{{ $errors->has('network-dns.1') ? ' is-invalid' : '' }}"
                                       name="network-dns[]"
                                       placeholder="Contoh: 1.1.1.1" autofocus>

                                @if ($errors->has('network-dns.1'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('network-dns.1') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="network-dns3" class="col-md-3 col-form-label">{{ __('DNS 3') }}</label>

                            <div class="col-md-8">
                                <input id="network-dns3" value="{{old('network-dns.2')}}" type="text"
                                       class="form-control{{ $errors->has('network-dns.2') ? ' is-invalid' : '' }}"
                                       name="network-dns[]"
                                       placeholder="Contoh: 202.134.1.10" autofocus>

                                @if ($errors->has('network-dns.2'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('network-dns.2') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="network-domain-name"
                                   class="col-md-3 col-form-label">{{ __('Domain name') }}</label>

                            <div class="col-md-8">
                                <input id="network-domain-name" value="{{old('network-domain-name')}}" type="text"
                                       class="form-control"
                                       name="network-domain-name" placeholder="Contoh: local.hotspot.area">
                            </div>
                        </div>

                        <br>
                        <h5 class="card-title">Form DHCP Server</h5>

                        <div class="form-group row">
                            <label for="network-domain-name"
                                   class="col-md-3 col-form-label">{{ __('Nama') }}</label>

                            <div class="col-md-8">
                                <input id="dhcp-name" value="{{old('dhcp-name')}}" type="text"
                                       class="form-control"
                                       name="dhcp-name" placeholder="Contoh: dhcp_server_pool1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dhcp-interface"
                                   class="col-md-3 col-form-label">{{ __('Interface') }} <span
                                        class="text-danger">*</span></label>

                            <div class="col-md-8">
                                <select id="dhcp-interface"
                                        class="form-control{{ $errors->has('dhcp-interface') ? ' is-invalid' : '' }}"
                                        name="dhcp-interface" required autofocus>
                                    <option value="">--- Interface Tersedia ---</option>
                                    @foreach($interfaces as $interface)
                                        <option {{ $interface['name'] == old('dhcp-interface')?'selected':'' }} value="{{ $interface['name'] }}">{{ $interface['type'] }} - {{ $interface['name'] }} - {{ $interface['address'] }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('dhcp-interface'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('dhcp-interface') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dhcp-lease-time"
                                   class="col-md-3 col-form-label">{{ __('Lease time client') }}</label>

                            <div class="col-md-8">
                                <input id="dhcp-lease-time" type="text"
                                       class="form-control" name="dhcp-lease-time"
                                       value="{{ old('dhcp-lease-time')? old('dhcp-lease-time'):'1d' }}"
                                       placeholder="ww dd hh:mm:ii:ss"
                                       autofocus>
                            </div>
                        </div>

                        <fieldset class="form-group">
                            <div class="row">
                                <label for="dhcp-status"
                                       class="col-md-3 col-form-label">{{ __('Status') }} <span
                                            class="text-danger">*</span>
                                </label>

                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="dhcp-status" id="enable"
                                               value="no" checked>
                                        <label class="form-check-label" for="enable">
                                            Enable
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="dhcp-status" id="disable"
                                               value="yes">
                                        <label class="form-check-label" for="disable">
                                            Disable
                                        </label>
                                    </div>

                                    @if ($errors->has('dhcp-status'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pool-range-end') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                        </fieldset>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Simpan') }}
                                </button>
                                <input type="reset" value="Kosongkan" class="btn btn-warning">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection