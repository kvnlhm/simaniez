<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\FPG;
use App\Models\Data;
use App\Models\Roti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FPGController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $fpg = FPG::all();
            
        $log = new Log;
        $log->id_user = Auth::user()->id;
        $log->aktivitas = 'Mengakses halaman FPG';
        $log->save();

        return view('fpg.index', compact('fpg'));
    }

    public function proses1(Request $request)
    {
        if (Auth::user()->id_priv == 1) {
            $fpg = new FPG;
            $fpg->tanggal_awal = $request->tanggal_awal;
            $fpg->tanggal_akhir = $request->tanggal_akhir;
            $fpg->minimal_support = $request->minimal_support;
            $fpg->minimal_confidence = $request->minimal_confidence;
            // $fpg->save();

            $data = Data::whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir])->get();
            // dd($data, $request->tanggal_awal, $request->tanggal_akhir);

            $frekuensi = [];
            $grupdByTgl = [];
            $semuaItem = [];
            $itemInitials = [];
            foreach ($data as $dt) {
                $items = explode(',', $dt->item);
                foreach ($items as $item) {
                    if (isset($frekuensi[$item])) {
                        $frekuensi[$item]++;
                    } else {
                        $frekuensi[$item] = 1;
                    }
                    $itemInitials[$item] = $dt->inisial;
                }
                $grupdByTgl[$dt->tanggal][$dt->waktu][] = $items;
                $semuaItem = array_merge($semuaItem, $items);
            }

            // Urutkan item berdasarkan frekuensi dari yang paling besar
            arsort($frekuensi);
            $itemOrder = array_keys($frekuensi);

            $minSupportCount = $request->minimal_support;

            // Create $sortedFilteredGrupdByTgl
            $sortedFilteredGrupdByTgl = [];
            foreach ($grupdByTgl as $tanggal => $times) {
                foreach ($times as $time => $items) {
                    $itemCounts = [];
                    $maxFreq = 0;
                    foreach ($items as $itemGroup) {
                        foreach ($itemGroup as $item) {
                            if (isset($frekuensi[$item])) {
                                $itemCounts[$item] = $frekuensi[$item];
                                $maxFreq = max($maxFreq, $frekuensi[$item]);
                            }
                        }
                    }
                    if (!empty($itemCounts)) {
                        // Urutkan item berdasarkan frekuensi dari yang paling besar
                        arsort($itemCounts);
                        $filteredGrupdByTgl[] = [
                            'tanggal' => $tanggal,
                            'waktu' => $time,
                            'items' => $itemCounts,
                            'max_freq' => $maxFreq
                        ];
                    }
                }
            }

            foreach ($filteredGrupdByTgl as $transaction) {
                $date = $transaction['tanggal'];
                $time = $transaction['waktu'];
                if (!isset($sortedFilteredGrupdByTgl[$date])) {
                    $sortedFilteredGrupdByTgl[$date] = [];
                }
                if (!isset($sortedFilteredGrupdByTgl[$date][$time])) {
                    $sortedFilteredGrupdByTgl[$date][$time] = [];
                }
                $sortedFilteredGrupdByTgl[$date][$time][] = $transaction;
            }

            // Calculate totalTransaksi using $sortedFilteredGrupdByTgl
            $totalTransaksi = array_sum(array_map(function($dateGroup) {
                return array_sum(array_map('count', $dateGroup));
            }, $sortedFilteredGrupdByTgl));

            // Calculate minSupportTran
            $minSupportTran = $totalTransaksi / 100 * $minSupportCount;

            // Now use $minSupportTran in the filtering process
            $filteredGrupdByTgl = [];
            foreach ($grupdByTgl as $tanggal => $times) {
                foreach ($times as $time => $items) {
                    $itemCounts = [];
                    $maxFreq = 0;
                    foreach ($items as $itemGroup) {
                        foreach ($itemGroup as $item) {
                            if ($frekuensi[$item] >= $minSupportTran) {
                                $itemCounts[$item] = $frekuensi[$item];
                                $maxFreq = max($maxFreq, $frekuensi[$item]);
                            }
                        }
                    }
                    if (!empty($itemCounts)) {
                        // Urutkan item berdasarkan frekuensi dari yang paling besar
                        arsort($itemCounts);
                        $filteredGrupdByTgl[] = [
                            'tanggal' => $tanggal,
                            'waktu' => $time,
                            'items' => $itemCounts,
                            'max_freq' => $maxFreq
                        ];
                    }
                }
            }

            // Urutkan transaksi berdasarkan frekuensi tertinggi
            usort($filteredGrupdByTgl, function($a, $b) {
                return $b['max_freq'] - $a['max_freq'];
            });

            // Urutkan hanya item dalam setiap transaksi, bukan seluruh $filteredGrupdByTgl
            foreach ($filteredGrupdByTgl as &$transaction) {
                $sortedItems = [];
                foreach ($itemOrder as $item) {
                    if (isset($transaction['items'][$item])) {
                        $sortedItems[$item] = $transaction['items'][$item];
                    }
                }
                $transaction['items'] = $sortedItems;
            }

            // Generate FP-tree data step-by-step
            $fpTreeSteps = $this->generateFPTreeSteps($filteredGrupdByTgl, $itemInitials);

            // Generate Conditional Pattern Base
            $conditionalPatternBase = $this->generateConditionalPatternBase($filteredGrupdByTgl, $frekuensi, $itemInitials);

            // Generate Conditional FP-Tree and Frequent Pattern Generated
            $conditionalFPTreeAndPatterns = $this->generateConditionalFPTreeAndPatterns($conditionalPatternBase, $minSupportTran, $itemInitials);

            // Simpan data yang diperlukan ke dalam session
            $request->session()->put('jumlahData', count($data));
            $request->session()->put('minSupport', $minSupportCount);
            $request->session()->put('minConfidence', $request->minimal_confidence);
            $request->session()->put('totalTransaksi', $totalTransaksi);
            $request->session()->put('frequentPatterns', array_keys($conditionalFPTreeAndPatterns));
            $request->session()->put('conditionalFPTreeAndPatterns', $conditionalFPTreeAndPatterns);
            $request->session()->put('filteredGrupdByTgl', $filteredGrupdByTgl);
            $request->session()->put('itemInitials', $itemInitials);
            $request->session()->put('frekuensi', $frekuensi);

            // Urutkan berdasarkan tanggal dan waktu
            ksort($sortedFilteredGrupdByTgl);
            foreach ($sortedFilteredGrupdByTgl as &$dateGroup) {
                ksort($dateGroup);
            }

            // Urutkan item dalam setiap transaksi
            foreach ($sortedFilteredGrupdByTgl as &$dateGroup) {
                foreach ($dateGroup as &$timeGroup) {
                    foreach ($timeGroup as &$transaction) {
                        arsort($transaction['items']);
                    }
                }
            }

            // Count total transactions exactly as shown in the table
            $totalRows = 0;
            foreach ($sortedFilteredGrupdByTgl as $tanggal => $times) {
                foreach ($times as $time => $transactions) {
                    $totalRows += count($transactions); // Count each transaction in the time group
                }
            }
            
            // Store the correct count in session
            $request->session()->put('jumlahData', $totalRows);
            
            // For debugging
            // dd($totalRows, $sortedFilteredGrupdByTgl);
            
            return view('fpg.index2', compact('data', 'frekuensi', 'grupdByTgl', 'minSupportCount', 'totalTransaksi', 'minSupportTran', 'sortedFilteredGrupdByTgl', 'semuaItem', 'itemInitials', 'fpTreeSteps', 'conditionalPatternBase', 'conditionalFPTreeAndPatterns', 'itemOrder'))->with('success', 'FPG berhasil melalui proses pertama.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    private function generateFPTreeSteps($filteredGrupdByTgl, $itemInitials)
    {
        $fpTreeSteps = [];
        $fpTree = ['name' => 'Null', 'count' => 0, 'children' => []];

        foreach ($filteredGrupdByTgl as $transaction) {
            $initialsString = implode(', ', array_map(function ($item) use ($itemInitials) {
                return $itemInitials[$item] ?? '';
            }, array_keys($transaction['items'])));
            $this->insertTree($fpTree, array_keys($transaction['items']), $itemInitials);
            $fpTreeSteps[] = [
                'tree' => $this->cloneTree($fpTree),
                'description' => "Menambahkan item: $initialsString"
            ];
        }

        return $fpTreeSteps;
    }

    private function insertTree(&$tree, $items, $itemInitials)
    {
        if (empty($items)) {
            return;
        }

        $item = array_shift($items);
        $initial = $itemInitials[$item] ?? $item;
        if (!isset($tree['children'][$initial])) {
            $tree['children'][$initial] = ['name' => $initial, 'count' => 0, 'children' => []];
        }
        $tree['children'][$initial]['count']++;
        $this->insertTree($tree['children'][$initial], $items, $itemInitials);
    }

    private function cloneTree($tree)
    {
        return json_decode(json_encode($tree), true);
    }

    private function generateConditionalPatternBase($filteredGrupdByTgl, $frekuensi, $itemInitials)
    {
        $conditionalPatternBase = [];
        
        // Urutkan item berdasarkan frekuensi dari yang terkecil ke terbesar
        asort($frekuensi);
        
        foreach ($frekuensi as $item => $count) {
            $patterns = [];
            foreach ($filteredGrupdByTgl as $transaction) {
                $items = array_keys($transaction['items']);
                if (($pos = array_search($item, $items)) !== false) {
                    $path = array_slice($items, 0, $pos);
                    if (!empty($path)) {
                        $pathKey = implode(', ', array_map(function($i) use ($itemInitials) {
                            return $itemInitials[$i] ?? $i;
                        }, $path));
                        if (!isset($patterns[$pathKey])) {
                            $patterns[$pathKey] = 1;
                        } else {
                            $patterns[$pathKey]++;
                        }
                    }
                }
            }
            if (!empty($patterns)) {
                $conditionalPatternBase[$item] = $patterns;
            }
        }
        
        // Urutkan items berdasarkan frekuensi dari yang terkecil ke terbesar untuk output
        asort($frekuensi);
        $orderedConditionalPatternBase = [];
        foreach ($frekuensi as $item => $count) {
            if (isset($conditionalPatternBase[$item])) {
                $orderedConditionalPatternBase[$item] = $conditionalPatternBase[$item];
            }
        }
        
        return $orderedConditionalPatternBase;
    }

    private function generateConditionalFPTreeAndPatterns($conditionalPatternBase, $minSupportTran, $itemInitials)
    {
        $conditionalFPTreeAndPatterns = [];

        foreach ($conditionalPatternBase as $item => $patterns) {
            $conditionalFPTree = [];
            $frequentPatterns = [];

            // Hitung frekuensi untuk setiap item dalam pola
            $itemCounts = [];
            foreach ($patterns as $pattern => $count) {
                $items = explode(', ', $pattern);
                foreach ($items as $i) {
                    if (!isset($itemCounts[$i])) {
                        $itemCounts[$i] = 0;
                    }
                    $itemCounts[$i] += $count;
                }
            }

            // Filter item yang memenuhi minimal transaksi
            foreach ($itemCounts as $i => $count) {
                if ($count >= $minSupportTran) {
                    $conditionalFPTree[] = "{" . ($itemInitials[$i] ?? $i) . " : " . $count . "}";
                    $frequentPatterns[] = "<" . ($itemInitials[$i] ?? $i) . ", " . ($itemInitials[$item] ?? $item) . " : " . $count . ">";
                }
            }

            // Tambahkan pola yang memenuhi minimal transaksi
            foreach ($patterns as $pattern => $count) {
                if ($count >= $minSupportTran) {
                    $frequentPatterns[] = "<" . implode(', ', array_map(function($i) use ($itemInitials) {
                        return $itemInitials[$i] ?? $i;
                    }, explode(', ', $pattern))) . ", " . ($itemInitials[$item] ?? $item) . " : " . $count . ">";
                }
            }

            // Hilangkan duplikasi pada frequent patterns
            $frequentPatterns = array_unique($frequentPatterns);

            $conditionalFPTreeAndPatterns[$item] = [
                'conditionalFPTree' => $conditionalFPTree,
                'frequentPatterns' => $frequentPatterns
            ];
        }

        return $conditionalFPTreeAndPatterns;
    }

    public function hasil(Request $request)
    {
        // Get the correct count from session
        $jumlahData = $request->session()->get('jumlahData');
        
        // Ambil data dari proses sebelumnya
        $filteredGrupdByTgl = $request->session()->get('filteredGrupdByTgl');
        $minSupport = $request->session()->get('minSupport');
        $minConfidence = $request->session()->get('minConfidence');
        $totalTransaksi = $request->session()->get('totalTransaksi');
        $conditionalFPTreeAndPatterns = $request->session()->get('conditionalFPTreeAndPatterns');
        $itemInitials = $request->session()->get('itemInitials');

        // Calculate support percentages and confidence for each frequent pattern
        $formattedFrequentPatterns = [];
        $confidenceResults = [];
        $conclusionResults = [];
        foreach ($conditionalFPTreeAndPatterns as $item => $data) {
            foreach ($data['frequentPatterns'] as $pattern) {
                preg_match('/<(.+) : (\d+)>/', $pattern, $matches);
                if (count($matches) === 3) {
                    $patternItems = explode(', ', $matches[1]);
                    $count = intval($matches[2]);
                    $supportPercentage = ($count / $jumlahData) * 100;
                    
                    // Membuat format "Jika X maka Y" dengan nama lengkap dan inisial
                    $consequent = array_pop($patternItems);
                    $antecedent = implode(', ', array_map(function($i) use ($itemInitials) {
                        $initial = array_search($i, $itemInitials);
                        return $i . ' (' . ($initial !== false ? $initial : $i) . ')';
                    }, $patternItems));
                    $consequentInitial = array_search($consequent, $itemInitials);
                    $formattedRule = "Jika $antecedent maka $consequent (" . ($consequentInitial !== false ? $consequentInitial : $consequent) . ")";

                    $formattedFrequentPatterns[] = [
                        'item' => $formattedRule,
                        'pattern' => implode(', ', array_map(function($i) use ($itemInitials) {
                            $initial = array_search($i, $itemInitials);
                            return $i . ' (' . ($initial !== false ? $initial : $i) . ')';
                        }, explode(', ', $matches[1]))),
                        'count' => $count,
                        'support' => number_format($supportPercentage, 2)
                    ];

                    // Calculate confidence for patterns with more than 2 items
                    if (count($patternItems) > 1) {
                        foreach ($patternItems as $antecedentItem) {
                            $antecedentPattern = array_diff($patternItems, [$antecedentItem]);
                            $antecedentPattern[] = $consequent;
                            $formattedAntecedentPattern = array_map(function($i) use ($itemInitials) {
                                $initial = array_search($i, $itemInitials);
                                return $i . ' (' . ($initial !== false ? $initial : $i) . ')';
                            }, $antecedentPattern);
                            $antecedentItemInitial = array_search($antecedentItem, $itemInitials);
                            $formattedAntecedentItem = $antecedentItem . ' (' . ($antecedentItemInitial !== false ? $antecedentItemInitial : $antecedentItem) . ')';

                            $antecedentCount = 0;
                            foreach ($conditionalFPTreeAndPatterns as $innerItem => $innerData) {
                                foreach ($innerData['frequentPatterns'] as $innerPattern) {
                                    if (strpos($innerPattern, "<" . implode(', ', $antecedentPattern) . " :") !== false) {
                                        preg_match('/<(.+) : (\d+)>/', $innerPattern, $innerMatches);
                                        if (count($innerMatches) === 3) {
                                            $antecedentCount += intval($innerMatches[2]);
                                        }
                                    }
                                }
                            }
                            $confidence = ($count / $antecedentCount) * 100;
                            $confidenceResults[] = [
                                'rule' => "Jika " . implode(', ', $formattedAntecedentPattern) . " maka $formattedAntecedentItem",
                                'support' => number_format($supportPercentage, 2),
                                'confidence' => number_format($confidence, 2)
                            ];

                            // Add to conclusions only if confidence meets the minimum threshold
                            if ($confidence >= $minConfidence) {
                                $conclusionResults[] = [
                                    'rule' => "Jika " . implode(', ', $formattedAntecedentPattern) . " maka $formattedAntecedentItem",
                                    'confidence' => number_format($confidence, 2),
                                    'description' => "Jika pelanggan membeli " . implode(', ', $formattedAntecedentPattern) . ", maka kemungkinan besar akan membeli $formattedAntecedentItem."
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Fetch all bread items
        $allBreadItems = Roti::pluck('nama', 'id_roti')->toArray();

        // Modify the conclusionResults to include bread information
        $conclusionResults = array_map(function ($conclusion) use ($allBreadItems) {
            $breadInfo = $this->getBreadInfo($conclusion['rule'], $allBreadItems);
            return array_merge($conclusion, $breadInfo);
        }, $conclusionResults);

        // Pastikan $frekuensi sudah didefinisikan sebelumnya
        $frekuensi = $request->session()->get('frekuensi');

        return view('fpg.hasil', compact('jumlahData', 'minSupport', 'minConfidence', 'formattedFrequentPatterns', 'confidenceResults', 'conclusionResults', 'frekuensi'));
    }

    private function getBreadInfo($rule, $allBreadItems)
    {
        $breadNames = [];
        $breadIngredients = [];

        foreach ($allBreadItems as $id => $name) {
            if (stripos($rule, $name) !== false) {
                $bread = Roti::find($id);
                $breadNames[] = $name;
                $breadIngredients[] = $bread->bahan;
            }
        }

        return [
            'breadNames' => $breadNames,
            'breadIngredients' => $breadIngredients,
        ];
    }
}
