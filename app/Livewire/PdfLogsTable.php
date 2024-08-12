<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PdfImportLog;

class PdfLogsTable extends Component
{
    use WithPagination;

    public $sortField = 'imported_at'; // Campo di default per l'ordinamento
    public $sortDirection = 'desc'; // Direzione di default per l'ordinamento

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $logs = PdfImportLog::orderBy($this->sortField, $this->sortDirection)
                            ->paginate(10);

        return view('livewire.pdf-logs-table', [
            'logs' => $logs,
        ]);
    }
}
