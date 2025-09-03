@extends('master')
@section('judul', 'Perhitungan FP-Growth')
@section('breadcrumb')
    <h1 class="page-heading d-flex title-custom fw-bolder fs-2hx flex-column justify-content-center my-0">
        Halaman Perhitungan FP-Growth</h1>
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-gray-700">
            <a href="{{ url('dashboard') }}" class="text-gray-700 text-hover-primary"><i class="ki-duotone ki-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Perhitungan FP-Growth</li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Proses 2</li>
    </ul>
@endsection

@section('css')
    <style>
        .node circle {
            fill: #999;
            stroke: steelblue;
            stroke-width: 3px;
        }

        .node text {
            font: 12px sans-serif;
        }

        .link {
            fill: none;
            stroke: #555;
            stroke-width: 2px;
        }

        .stepper.stepper-links .stepper-nav .stepper-item.current:after {
            background-color: var(--bs-warning);
        }
    </style>
@endsection

@section('konten')
    <div class="card">
        <div class="card-body">
            <div class="stepper stepper-links d-flex flex-column pt-15" id="kt_create_account_stepper">
                <div class="stepper-nav mb-5">
                    <div class="stepper-item completed" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Proses 1</h3>
                    </div>
                    <div class="stepper-item current" data-kt-stepper-element="nav">
                        <h3 class="stepper-title text-warning">Proses 2</h3>
                    </div>
                    <div class="stepper-item pending" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Hasil</h3>
                    </div>
                </div>
                @if (Auth::user()->id_priv == 1)
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Tabel</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Data Berdasarkan Tanggal</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table id="kt_datatable_dom_positioning"
                                class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 min-w-40px rounded-start">No.</th>
                                        <th class="min-w-100px">Tanggal</th>
                                        <th class="min-w-100px">Waktu</th>
                                        <th class="min-w-100px">Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($sortedFilteredGrupdByTgl as $tanggal => $times)
                                        @foreach ($times as $time => $transactions)
                                            @foreach ($transactions as $transaction)
                                                <tr>
                                                    <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        {{ $i }}
                                                    </td>
                                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        {{ $tanggal }}
                                                    </td>
                                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        {{ $time }}
                                                    </td>
                                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        @foreach ($transaction['items'] as $item => $count)
                                                            {{ $item }}@if (!$loop->last), @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Catatan : Data sesuai tanggal awal dan tanggal akhir yang di input.</span>
                        </div>
                    </div>

                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Tabel</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Frekuensi Kemunculan Tiap Item</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table id="kt_datatable_dom_positioning_2"
                                class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 min-w-40px rounded-start">No.</th>
                                        <th class="min-w-100px">Item</th>
                                        <th class="min-w-100px">Frekuensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $totalFrekuensi = 0;
                                    @endphp
                                    @foreach ($frekuensi as $item => $jumlah)
                                        <tr>
                                            <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                {{ $i }}
                                            </td>
                                            <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                {{ $item }}
                                            </td>
                                            <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                {{ $jumlah }}
                                            </td>
                                        </tr>
                                        @php
                                            $i++;
                                            $totalFrekuensi += $jumlah;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Total Frekuensi: <strong>{{ $totalFrekuensi }}</strong></span>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Catatan : Frekuensi kemunculan item berdasarkan tanggal yang di
                                input.</span>
                        </div>
                    </div>

                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Tabel</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Penerapan Item Berdasarkan Frekuensi Kemunculan
                                (Dengan Min Support = {{ $minSupportCount }}% maka {{ $totalTransaksi }}*{{ $minSupportCount }}% = {{ $minSupportTran }} transaksi)</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table id="kt_datatable_dom_positioning_filtered"
                                class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 min-w-40px rounded-start">No.</th>
                                        <th class="min-w-100px">Tanggal</th>
                                        <th class="min-w-100px">Waktu</th>
                                        <th class="min-w-100px">Item</th>
                                        <th class="min-w-100px">Kode Inisial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rowNumber = 1;
                                    @endphp
                                    @foreach ($sortedFilteredGrupdByTgl as $date => $timeGroups)
                                        @foreach ($timeGroups as $time => $transactions)
                                            @foreach ($transactions as $transaction)
                                                @php
                                                    $filteredItems = array_filter($transaction['items'], function($item) use ($frekuensi, $minSupportTran) {
                                                        return $frekuensi[$item] >= $minSupportTran;
                                                    }, ARRAY_FILTER_USE_KEY);
                                                @endphp
                                                @if (!empty($filteredItems))
                                                    <tr>
                                                        <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                            {{ $rowNumber++ }}
                                                        </td>
                                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                            {{ $date }}
                                                        </td>
                                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                            {{ $time }}
                                                        </td>
                                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                            {{ implode(', ', array_keys($filteredItems)) }}
                                                        </td>
                                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                            {{ implode(', ', array_map(function($item) use ($itemInitials) {
                                                                return $itemInitials[$item] ?? '';
                                                            }, array_keys($filteredItems))) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Catatan : Data sesuai tanggal awal dan tanggal akhir yang di input dan
                                difilter berdasarkan minimal support. Diurutkan berdasarkan tanggal, waktu, dan item sesuai dengan urutan frekuensi kemunculan. Baris dengan item kosong setelah filtering telah dihapus.</span>
                        </div>
                    </div>

                    <!-- New Section for FP-tree Visualization Steps -->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Langkah-langkah Pembentukan FP-Tree</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Berdasarkan Tabel Penerapan Item Berdasarkan Frekuensi Kemunculan</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        @foreach ($fpTreeSteps as $index => $step)
                            <div class="mb-5">
                                <h4>Langkah {{ $index + 1 }}</h4>
                                <p>{{ $step['description'] }}</p>
                                <div id="fp-tree-step-{{ $index }}"></div>
                            </div>
                        @endforeach
                    </div>

                    <!-- New Section for Conditional Pattern Base -->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Conditional Pattern Base</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 min-w-100px rounded-start">Item</th>
                                        <th class="min-w-200px">Conditional Pattern Base</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conditionalPatternBase as $item => $patterns)
                                        <tr>
                                            <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                {{ $item }} ({{ $itemInitials[$item] ?? $item }})
                                            </td>
                                            <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                @foreach ($patterns as $pattern => $count)
                                                    {
                                                    @foreach (explode(', ', $pattern) as $index => $initial)
                                                        @php
                                                            $fullName = array_search($initial, $itemInitials) ?: $initial;
                                                        @endphp
                                                        {{ $fullName }} ({{ $initial }})
                                                        @if ($index < count(explode(', ', $pattern)) - 1)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                    : {{ $count }}
                                                    }
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Conditional FP-Tree dan Frequent Pattern Generated</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light">
                                        <th class="ps-4 min-w-100px rounded-start">Item</th>
                                        <th class="min-w-200px">Conditional FP-Tree</th>
                                        <th class="min-w-200px">Frequent Pattern Generated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conditionalFPTreeAndPatterns as $item => $data)
                                        <tr>
                                            <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                {{ $item }} ({{ $itemInitials[$item] ?? $item }})
                                            </td>
                                            <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                @foreach ($data['conditionalFPTree'] as $tree)
                                                    @php
                                                        $fullNameTree = preg_replace_callback('/([A-Z]+)/', function($matches) use ($itemInitials) {
                                                            $initial = $matches[1];
                                                            $fullName = array_search($initial, $itemInitials) ?: $initial;
                                                            return "$fullName ($initial)";
                                                        }, $tree);
                                                    @endphp
                                                    {!! $fullNameTree !!}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                @foreach ($data['frequentPatterns'] as $pattern)
                                                    @php
                                                        $fullNamePattern = preg_replace_callback('/([A-Z]+)/', function($matches) use ($itemInitials) {
                                                            $initial = $matches[1];
                                                            $fullName = array_search($initial, $itemInitials) ?: $initial;
                                                            return "$fullName ($initial)";
                                                        }, $pattern);
                                                    @endphp
                                                    {!! htmlspecialchars($fullNamePattern) !!}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ url('fpg/hasil') }}" class="btn btn-warning">
                            <span class="indicator-label">Lanjut ke Hasil</span>
                            <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('my_components.toastr')
    @include('my_components.datatables')
    <script src="https://d3js.org/d3.v6.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const steps = @json($fpTreeSteps);
            const itemInitials = @json($itemInitials);

            // Fungsi untuk mendapatkan nama item lengkap
            function getFullItemName(initial) {
                for (let item in itemInitials) {
                    if (itemInitials[item] === initial) {
                        return `${item} (${initial})`;
                    }
                }
                return initial;
            }

            steps.forEach((step, index) => {
                const data = step.tree;
                const width = 600;
                const height = 400;

                const svg = d3.select(`#fp-tree-step-${index}`).append("svg")
                    .attr("width", width)
                    .attr("height", height)
                    .append("g")
                    .attr("transform", "translate(40,0)");

                const tree = d3.tree().size([height, width - 160]);

                const root = d3.hierarchy(data, d => {
                    const children = Object.keys(d.children || {}).map(key => ({
                        name: getFullItemName(key),
                        count: d.children[key].count,
                        children: d.children[key].children
                    }));
                    return children.length ? children : null;
                });

                tree(root);

                const link = svg.selectAll(".link")
                    .data(root.descendants().slice(1))
                    .enter().append("path")
                    .attr("class", "link")
                    .attr("d", d => `
                        M${d.y},${d.x}
                        C${(d.y + d.parent.y) / 2},${d.x}
                         ${(d.y + d.parent.y) / 2},${d.parent.x}
                         ${d.parent.y},${d.parent.x}
                    `);

                const node = svg.selectAll(".node")
                    .data(root.descendants())
                    .enter().append("g")
                    .attr("class", d => "node" + (d.children ? " node--internal" : " node--leaf"))
                    .attr("transform", d => `translate(${d.y},${d.x})`);

                node.append("circle")
                    .attr("r", 10);

                node.append("text")
                    .attr("dy", 3)
                    .attr("x", d => d.children ? -12 : 12)
                    .style("text-anchor", d => d.children ? "end" : "start")
                    .text(d => `${d.data.name} : ${d.data.count}`);
            });
        });
    </script>
@endsection
