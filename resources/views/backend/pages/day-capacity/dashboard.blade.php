@extends('backend.layout.app')
@section('title', 'Capacity Dashboard | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Capacity Dashboard - {{ $today }}</h4>

        <div class="row">
            @foreach($units as $unit)
                @php
                    $capacity = $todayCapacities[$unit->id] ?? null;
                    $initialTargetPerHour = 1000 / 24;
                    $mgTargetPerHour = 956.52 / 24;
                @endphp
                
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0">{{ $unit->unitName }}</h5>
                            <span class="badge bg-primary">{{ $unit->machineCount }} Machines</span>
                        </div>
                        <div class="card-body">
                            @if($capacity)
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="text-center p-2 bg-light rounded">
                                            <h6 class="mb-1">Initial Target</h6>
                                            <h4 class="text-primary mb-0">{{ number_format($capacity->total_initial_target, 2) }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-2 bg-light rounded">
                                            <h6 class="mb-1">MG Target</h6>
                                            <h4 class="text-success mb-0">{{ number_format($capacity->total_mg_target, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>

                                <h6>Hourly Performance</h6>
                                <div class="hourly-chart">
                                    @for($hour = 0; $hour < 24; $hour++)
                                        @php
                                            $hourField = 'hour_' . sprintf('%02d', $hour);
                                            $hourValue = $capacity->$hourField;
                                            $hourInitial = $hourValue * $initialTargetPerHour * $unit->machineCount;
                                            $hourMg = $hourValue * $mgTargetPerHour * $unit->machineCount;
                                        @endphp
                                        <div class="hour-row mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted" style="width: 50px;">{{ sprintf('%02d:00', $hour) }}</small>
                                                <div class="progress flex-grow-1 mx-2" style="height: 20px;">
                                                    <div class="progress-bar bg-primary" style="width: {{ $hourValue * 100 }}%"
                                                         title="Initial: {{ number_format($hourInitial, 2) }} | MG: {{ number_format($hourMg, 2) }}">
                                                        {{ number_format($hourValue * 100, 0) }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fa-solid fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                    <p class="text-muted">No capacity data for today</p>
                                    <a href="{{ route('admin.dayCapacity.index') }}" class="btn btn-sm btn-primary">
                                        Set Capacity
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="m-0">Daily Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h6>Total Initial Target</h6>
                                <h3>{{ number_format($todayCapacities->sum('total_initial_target'), 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h6>Total MG Target</h6>
                                <h3>{{ number_format($todayCapacities->sum('total_mg_target'), 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h6>Active Units</h6>
                                <h3>{{ $todayCapacities->count() }}/{{ $units->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <h6>Total Machines</h6>
                                <h3>{{ $units->sum('machineCount') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hourly-chart {
            max-height: 300px;
            overflow-y: auto;
        }
        .hour-row {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 5px;
        }
        .progress {
            background-color: #e9ecef;
        }
    </style>
@endsection