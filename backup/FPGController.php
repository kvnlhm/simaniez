<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\FPG;
use App\Models\Data;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Graphp\GraphViz\GraphViz;
use Fhaculty\Graph\Graph;

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

            $minSupportCount = $request->minimal_support;
            $totalTransaksi = array_sum(array_map('count', $grupdByTgl));
            $minSupportTran = $totalTransaksi / 100 * $minSupportCount;

            $filteredGrupdByTgl = [];
            foreach ($grupdByTgl as $tanggal => $times) {
                foreach ($times as $time => $items) {
                    $itemCounts = [];
                    foreach ($items as $itemGroup) {
                        foreach ($itemGroup as $item) {
                            if ($frekuensi[$item] >= $minSupportTran) {
                                if (isset($itemCounts[$item])) {
                                    $itemCounts[$item]++;
                                } else {
                                    $itemCounts[$item] = 1;
                                }
                            }
                        }
                    }
                    if (!empty($itemCounts)) {
                        // Urutkan item berdasarkan frekuensi dari yang paling besar
                        arsort($itemCounts);
                        $filteredGrupdByTgl[$tanggal][$time] = $itemCounts;
                    }
                }
            }
            
            $steps = [];
            $fpTree = $this->buildFPTreeFromFilteredData($filteredGrupdByTgl, $frekuensi, $steps, $itemInitials);
            $conditionalPatternBase = $this->buildConditionalPatternBase($fpTree);
            // $fpTreeImagePath = $this->generateFPTreeImage($fpTree);
            $fpTreeData = $this->generateFPTreeData($fpTree);

            // $log = new Log;
            // $log->id_user = Auth::user()->id;
            // $log->aktivitas = 'Melakukan proses pertama di halaman PerhitunganFP-Growth';
            // $log->save();

            return view('fpg.index2', compact('data', 'frekuensi', 'grupdByTgl', 'minSupportCount', 'totalTransaksi', 'minSupportTran', 'filteredGrupdByTgl', 'semuaItem', 'itemInitials', 'fpTreeData', 'conditionalPatternBase', 'steps'))->with('success', 'FPG berhasil melalui proses pertama.');
        } else {
            return redirect()->back()->with('error', 'Maaf, anda tidak memiliki akses.');
        }
    }

    private function buildFPTreeFromFilteredData($filteredGrupdByTgl, $frekuensi, &$steps, $itemInitials)
    {
        $fpTree = [];
        $stepCounter = 0;
        foreach ($filteredGrupdByTgl as $tanggal => $times) {
            foreach ($times as $time => $items) {
                // Sort items by frequency
                uksort($items, function ($a, $b) use ($frekuensi) {
                    return $frekuensi[$b] <=> $frekuensi[$a];
                });
                $sortedItems = array_keys($items);
                $this->insertTransaction($fpTree, $sortedItems, $steps, $itemInitials, $stepCounter);
            }
        }
        return $fpTree;
    }
    
    private function insertTransaction(&$tree, $transaction, &$steps, $itemInitials, &$stepCounter, $path = [])
    {
        if (empty($transaction)) {
            return;
        }
    
        $item = array_shift($transaction);
        if (!isset($tree[$item])) {
            $tree[$item] = ['count' => 0, 'children' => []];
        }
        $tree[$item]['count']++;
        $currentPath = array_merge($path, [$item]);
        $steps[] = [
            'data' => $this->generateFPTreeData($tree), // Capture the state of the tree at this step
            'item' => $item,
            'root' => implode(' -> ', $currentPath),
            'initialsString' => implode(', ', array_map(function ($item) use ($itemInitials) {
                return $itemInitials[$item] ?? '';
            }, $currentPath))
        ];
        $stepCounter++;
        $this->insertTransaction($tree[$item]['children'], $transaction, $steps, $itemInitials, $stepCounter, $currentPath);
    }

    private function buildConditionalPatternBase($fpTree)
    {
        $conditionalPatternBase = [];
        foreach ($fpTree as $item => $node) {
            $this->extractConditionalPatternBase($node, [], $conditionalPatternBase);
        }
        return $conditionalPatternBase;
    }

    private function extractConditionalPatternBase($node, $prefix, &$conditionalPatternBase)
    {
        foreach ($node['children'] as $childItem => $childNode) {
            $newPrefix = array_merge($prefix, [$childItem]);
            if (!isset($conditionalPatternBase[$childItem])) {
                $conditionalPatternBase[$childItem] = [];
            }
            $conditionalPatternBase[$childItem][] = $newPrefix;
            $this->extractConditionalPatternBase($childNode, $newPrefix, $conditionalPatternBase);
        }
    }

    private function generateFPTreeImage($fpTree)
    {
        $graph = new Graph();
        $graphviz = new GraphViz();
    
        $this->addNodesToGraph($graph, $fpTree);
    
        $imagePath = 'fp_tree_image.png';
        try {
            $graphviz->display($graph, 'png', $imagePath);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to generate FP-Tree image. Please ensure that Graphviz is installed and the "dot" command is available.',
                'details' => $e->getMessage(),
            ]);
        }
    
        return $imagePath;
    }
    
    private function addNodesToGraph($graph, $tree, $parent = null, &$nodeCounter = 0)
    {
        foreach ($tree as $item => $node) {
            $nodeName = $item . ' (' . $node['count'] . ')';
            $uniqueNodeId = $nodeName . '_' . $nodeCounter++;
            $nodeObj = $graph->createVertex($uniqueNodeId)->setAttribute('label', $nodeName);
    
            if ($parent) {
                $parent->createEdgeTo($nodeObj);
            }
    
            $this->addNodesToGraph($graph, $node['children'], $nodeObj, $nodeCounter);
        }
    }

    private function generateFPTreeData($fpTree)
    {
        return json_encode($this->convertTreeToD3Format($fpTree));
    }

    private function convertTreeToD3Format($tree)
    {
        $result = [];
        foreach ($tree as $item => $node) {
            $children = $this->convertTreeToD3Format($node['children']);
            $result[] = [
                'name' => $item . ' (' . $node['count'] . ')',
                'children' => $children
            ];
        }
        return $result;
    }
}