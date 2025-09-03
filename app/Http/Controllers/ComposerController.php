<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ComposerController extends Controller
{
    public function installGraphviz()
    {
        $process = new Process(['composer', 'require', 'alxdm/graphviz']);
        $process->setWorkingDirectory(base_path());
        $process->run();

        // Executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return response()->json([
            'message' => 'Graphviz package installed successfully!',
            'output' => $process->getOutput(),
        ]);
    }
}