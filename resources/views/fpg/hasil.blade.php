@extends('master')
@section('judul', 'Hasil FP-Growth')
@section('breadcrumb')
    <h1 class="page-heading d-flex title-custom fw-bolder fs-2hx flex-column justify-content-center my-0">
        Hasil FP-Growth</h1>
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-gray-700">
            <a href="{{ url('dashboard') }}" class="text-gray-700 text-hover-primary"><i class="ki-duotone ki-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <span class="">
                <i class="ki-duotone ki-right fs-6 text-gray-700"></i>
            </span>
        </li>
        <li class="breadcrumb-item text-gray-700">Hasil FP-Growth</li>
    </ul>
@endsection

@section('css')
    <style>
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
                    <div class="stepper-item completed" data-kt-stepper-element="nav">
                        <h3 class="stepper-title">Proses 2</h3>
                    </div>
                    <div class="stepper-item current" data-kt-stepper-element="nav">
                        <h3 class="stepper-title text-warning">Hasil</h3>
                    </div>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <h2>Konfigurasi</h2>
                        <table class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                            <tbody>
                                <tr>
                                    <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        Jumlah Data
                                    </td>
                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        {{ $jumlahData }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        Min Support
                                    </td>
                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        {{ $minSupport }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        Min Confidence
                                    </td>
                                    <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                        {{ $minConfidence }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <h2>Perhitungan dalam Menemukan Nilai Support</h2>
                        <table class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="ps-4 min-w-40px rounded-start">No.</th>
                                    <th class="min-w-200px">Rule</th>
                                    <th class="min-w-200px">Frequent Pattern</th>
                                    <th class="min-w-100px">Count</th>
                                    <th class="min-w-100px">Support</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formattedFrequentPatterns as $index => $pattern)
                                    <tr>
                                        <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $index + 1 }}</td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $pattern['item'] }}</td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $pattern['pattern'] }}</td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $pattern['count'] }}</td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                            {{ $pattern['support'] }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <h2>Perhitungan dalam Menemukan Nilai Confidence</h2>
                        <table class="table table-row-bordered border rounded align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="ps-4 min-w-40px rounded-start">No.</th>
                                    <th class="min-w-200px">Rule</th>
                                    <th class="min-w-100px">Support</th>
                                    <th class="min-w-100px">Confidence</th>
                                    <th class="min-w-200px">Perhitungan Confidence</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formattedFrequentPatterns as $index => $pattern)
                                    @php
                                        $rule = $pattern['item'];
                                        $support = $pattern['support'];
                                        $count = $pattern['count'];
                                        
                                        // Extract antecedent and consequent from the rule
                                        preg_match('/Jika (.+) maka (.+)/', $rule, $matches);
                                        $antecedent = $matches[1] ?? '';
                                        $consequent = $matches[2] ?? '';
                                        
                                        // Extract the item name from antecedent (only the part inside parentheses)
                                        preg_match('/\(([^)]+)\)/', $antecedent, $antecedentMatches);
                                        $antecedentItem = $antecedentMatches[1] ?? '';
                                        
                                        // Find the frequency of the antecedent item from the frekuensi array
                                        $antecedentFrequency = $frekuensi[$antecedentItem] ?? 0;
                                        
                                        // Calculate confidence
                                        $confidence = ($antecedentFrequency > 0) ? ($count / $antecedentFrequency) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $index + 1 }}</td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $rule }}</td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $support }}%</td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ number_format($confidence, 2) }}%</td>
                                        <td class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                            ({{ $count }} / {{ $antecedentFrequency }}) * 100 = {{ number_format($confidence, 2) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h2>Kesimpulan</h2>
                        @php
                            $validRules = [];
                        @endphp
                        <ul>
                            @foreach ($formattedFrequentPatterns as $pattern)
                                @php
                                    $rule = $pattern['item'];
                                    $support = $pattern['support'];
                                    $count = $pattern['count'];
                                    
                                    // Extract antecedent and consequent from the rule
                                    preg_match('/Jika (.+) maka (.+)/', $rule, $matches);
                                    $antecedent = $matches[1] ?? '';
                                    $consequent = $matches[2] ?? '';
                                    
                                    // Extract the item name from antecedent (only the part inside parentheses)
                                    preg_match('/\(([^)]+)\)/', $antecedent, $antecedentMatches);
                                    $antecedentItem = $antecedentMatches[1] ?? '';
                                    
                                    // Find the frequency of the antecedent item from the frekuensi array
                                    $antecedentFrequency = $frekuensi[$antecedentItem] ?? 0;
                                    
                                    // Calculate confidence
                                    $confidence = ($antecedentFrequency > 0) ? ($count / $antecedentFrequency) * 100 : 0;
                                    
                                    // Only show rules that meet or exceed the minimum confidence
                                    if ($confidence >= $minConfidence) {
                                        $validRules[] = [
                                            'rule' => $rule,
                                            'confidence' => $confidence,
                                            'antecedent' => $antecedent,
                                            'consequent' => $consequent
                                        ];
                                @endphp
                                    <li>
                                        <strong>{{ $rule }} (Confidence: {{ number_format($confidence, 2) }}%)</strong>
                                        <p>Jika pelanggan membeli {{ $antecedent }}, maka kemungkinan besar akan membeli {{ $consequent }}.</p>
                                    </li>
                                @php
                                    }
                                @endphp
                            @endforeach
                        </ul>
                        @if (empty($validRules))
                            <p>Tidak ada aturan asosiasi yang memenuhi nilai minimum confidence.</p>
                        @endif
                    </div>

                    <div class="mt-8">
                        <h2>Roti yang Sering Dibeli Bersamaan</h2>
                        <p>Berdasarkan analisis di atas, roti-roti berikut sering dibeli bersamaan:</p>
                        <ul>
                            @php
                                $breadInConclusion = [];
                                foreach ($validRules as $rule) {
                                    // Extract names inside parentheses
                                    preg_match_all('/\(([^)]+)\)/', $rule['antecedent'] . ', ' . $rule['consequent'], $matches);
                                    $breadInConclusion = array_merge($breadInConclusion, $matches[1]);
                                }
                                $breadInConclusion = array_unique($breadInConclusion);
                            @endphp
                            @foreach ($breadInConclusion as $breadName)
                                <li>
                                    <strong>{{ $breadName }}</strong>
                                    @php
                                        $bread = App\Models\Roti::where('nama', 'like', '%' . $breadName . '%')->first();
                                    @endphp
                                    @if ($bread)
                                        <br>Bahan: {{ $bread->bahan }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @if (empty($breadInConclusion))
                            <p>Tidak ada roti yang memenuhi kriteria untuk sering dibeli bersamaan berdasarkan aturan asosiasi yang memenuhi nilai minimum confidence.</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
