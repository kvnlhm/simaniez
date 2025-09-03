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
        .stepper.stepper-links .stepper-nav .stepper-item.current:after {
            background-color: var(--bs-warning);
        }

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
                        <h3 class="stepper-title">Proses 3</h3>
                    </div>
                    <div class="stepper-item pending" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Proses 4</h3>
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
                                        {{-- <th class="min-w-100px">Frekuensi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($grupdByTgl as $tanggal => $times)
                                        @php
                                            $firstDateRow = true;
                                        @endphp
                                        @foreach ($times as $time => $items)
                                            @php
                                                $firstTimeRow = true;
                                                $itemCounts = [];
                                                foreach ($items as $itemGroup) {
                                                    foreach ($itemGroup as $item) {
                                                        if (isset($itemCounts[$item])) {
                                                            $itemCounts[$item]++;
                                                        } else {
                                                            $itemCounts[$item] = 1;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @foreach ($itemCounts as $item => $count)
                                                <tr>
                                                    <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        {{ $i }}
                                                    </td>
                                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        @if ($firstDateRow)
                                                            {{ $tanggal }}
                                                            @php
                                                                $firstDateRow = false;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        @if ($firstTimeRow)
                                                            {{ $time }}
                                                            @php
                                                                $firstTimeRow = false;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        {{ $item }}
                                                    </td>
                                                    {{-- <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                        {{ $count }}
                                                    </td> --}}
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
                            <span class="text-muted">Catatan : Data sesuai tanggal awal dan tanggal akhir yang di
                                input.</span>
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
                            <table id="kt_datatable_dom_positioning"
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
                                    <tr class="fw-bold text-muted bg-light">
                                        <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6" colspan="2">Total
                                        </td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $totalFrekuensi }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                                        {{-- <th class="min-w-100px">Frekuensi</th> --}}
                                        <th class="min-w-100px">Kode Inisial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($filteredGrupdByTgl as $tanggal => $times)
                                        @php
                                            $firstDateRow = true;
                                        @endphp
                                        @foreach ($times as $time => $items)
                                            @php
                                                $firstTimeRow = true;
                                                $itemString = implode(', ', array_keys($items));
                                                $initialsString = implode(
                                                    ', ',
                                                    array_map(function ($item) use ($itemInitials) {
                                                        return $itemInitials[$item] ?? '';
                                                    }, array_keys($items)),
                                                );
                                            @endphp
                                            <tr>
                                                <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                    {{ $i }}
                                                </td>
                                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                    @if ($firstDateRow)
                                                        {{ $tanggal }}
                                                        @php
                                                            $firstDateRow = false;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                    @if ($firstTimeRow)
                                                        {{ $time }}
                                                        @php
                                                            $firstTimeRow = false;
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                    {{ $itemString }}
                                                </td>
                                                {{-- <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                    {{ $totalCount }}
                                                </td> --}}
                                                <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                    {{ $initialsString }}
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Catatan : Data sesuai tanggal awal dan tanggal akhir yang di input dan
                                difilter berdasarkan minimal support.</span>
                        </div>
                    </div>

                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Langkah-langkah FP-Tree</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Visualisasi setiap langkah FP-Tree</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        @foreach ($steps as $index => $step)
                            <div id="fptree-step-{{ $index }}"></div>
                            <div class="mt-3">
                                <span class="text-muted">Langkah {{ $index + 1 }}: 
                                    @if ($index == 0)
                                        Inisialisasi FP-Tree dengan item pertama.
                                    @elseif ($index == 1)
                                        Menambahkan item kedua ke dalam FP-Tree.
                                    @elseif ($index == 2)
                                        Menambahkan item ketiga ke dalam FP-Tree.
                                    @else
                                        Menambahkan item ke-{{ $index + 1 }} ke dalam FP-Tree.
                                    @endif
                                </span>
                                <p class="text-muted">
                                    Nama item: {{ $step['item'] }}<br>
                                    Asal root: {{ $step['root'] }}<br>
                                    Kode Inisial: {{ $step['initialsString'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">FP-Tree</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Visualisasi FP-Tree</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div id="fptree"></div>
                        <div class="mt-3">
                            <span class="text-muted">Catatan : Visualisasi FP-Tree berdasarkan data yang difilter.</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://d3js.org/d3.v6.min.js"></script>
    <script>
        const treeData = {!! $fpTreeData !!};

        const margin = {top: 20, right: 90, bottom: 30, left: 90},
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        const svg = d3.select("#fptree").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        const root = d3.hierarchy({name: "root", children: treeData}, d => d.children);

        const treeLayout = d3.tree().size([height, width]);

        treeLayout(root);

        svg.selectAll('.link')
            .data(root.links())
            .enter()
            .append('path')
            .attr('class', 'link')
            .attr('d', d3.linkHorizontal()
                .x(d => d.y)
                .y(d => d.x));

        const node = svg.selectAll('.node')
            .data(root.descendants())
            .enter()
            .append('g')
            .attr('class', 'node')
            .attr('transform', d => `translate(${d.y},${d.x})`);

        node.append('circle')
            .attr('r', 5);

        node.append('text')
            .attr('dy', '.35em')
            .attr('x', d => d.children ? -10 : 10)
            .style('text-anchor', d => d.children ? 'end' : 'start')
            .text(d => d.data.name);

        // Render each step
        @foreach ($steps as $index => $step)
            const stepData{{ $index }} = {!! $step['data'] !!};

            const svgStep{{ $index }} = d3.select("#fptree-step-{{ $index }}").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

            const rootStep{{ $index }} = d3.hierarchy({
                name: "root",
                children: stepData{{ $index }}
            }, d => d.children);

            treeLayout(rootStep{{ $index }});

            svgStep{{ $index }}.selectAll('.link')
                .data(rootStep{{ $index }}.links())
                .enter()
                .append('path')
                .attr('class', 'link')
                .attr('d', d3.linkHorizontal()
                    .x(d => d.y)
                    .y(d => d.x));

            const nodeStep{{ $index }} = svgStep{{ $index }}.selectAll('.node')
                .data(rootStep{{ $index }}.descendants())
                .enter()
                .append('g')
                .attr('class', 'node')
                .attr('transform', d => `translate(${d.y},${d.x})`);

            nodeStep{{ $index }}.append('circle')
                .attr('r', 5);

            nodeStep{{ $index }}.append('text')
                .attr('dy', '.35em')
                .attr('x', d => d.children ? -10 : 10)
                .style('text-anchor', d => d.children ? 'end' : 'start')
                .text(d => d.data.name);
        @endforeach
    </script>

    @include('my_components.toastr')
    @include('my_components.datatables')
@endsection
