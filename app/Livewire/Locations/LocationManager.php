<?php

namespace App\Livewire\Locations;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class LocationManager extends Component
{
    use WithPagination;

    // Properties untuk form
    public $locationId = null;
    public $parent_id = null;
    public $name = '';
    public $code = '';
    public $type = 'warehouse';
    public $description = '';

    // Properties untuk UI
    public $isEditing = false;
    public $showForm = false;
    public $showDeleteModal = false;
    public $locationToDelete = null;

    // Properties untuk filter dan search
    public $search = '';
    public $filterType = '';
    public $filterParent = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Tree view properties
    public $expandedNodes = [];
    public $selectedLocation = null;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'parent_id' => 'nullable|exists:locations,id',
            'name' => 'required|string|min:3|max:100',
            'code' => 'nullable|string|max:50|unique:locations,code,' . $this->locationId,
            'type' => 'required|in:warehouse,rack,bin',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages()
    {
        return [
            'name.required' => 'Nama lokasi wajib diisi',
            'name.min' => 'Nama lokasi minimal 3 karakter',
            'type.required' => 'Tipe lokasi wajib dipilih',
            'code.unique' => 'Kode lokasi sudah digunakan',
        ];
    }

    /**
     * Reset form
     */
    public function resetForm()
    {
        $this->locationId = null;
        $this->parent_id = null;
        $this->name = '';
        $this->code = '';
        $this->type = 'warehouse';
        $this->description = '';
        $this->isEditing = false;
        $this->showForm = false;
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    /**
     * Edit location
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);

        $this->locationId = $location->id;
        $this->parent_id = $location->parent_id;
        $this->name = $location->name;
        $this->code = $location->code;
        $this->type = $location->type;
        $this->description = $location->description;

        $this->isEditing = true;
        $this->showForm = true;
    }

    /**
     * Save location (create or update)
     */
    public function save()
    {
        $this->validate();

        try {
            $data = [
                'parent_id' => $this->parent_id ?: null,
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
            ];

            // Auto-generate code if empty
            if (!empty($this->code)) {
                $data['code'] = $this->code;
            }

            if ($this->isEditing) {
                $location = Location::findOrFail($this->locationId);
                $location->update($data);
                $message = 'Lokasi berhasil diperbarui';
            } else {
                Location::create($data);
                $message = 'Lokasi berhasil ditambahkan';
            }

            $this->resetForm();
            session()->flash('success', $message);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            Log::error('Location save error: ' . $e->getMessage());
        }
    }

    /**
     * Confirm delete
     */
    public function confirmDelete($id)
    {
        $this->locationToDelete = Location::findOrFail($id);
        $this->showDeleteModal = true;
    }

    /**
     * Delete location
     */
    public function delete()
    {
        try {
            $location = $this->locationToDelete;

            // Cek apakah ada children
            if ($location->hasChildren()) {
                session()->flash('error', 'Tidak dapat menghapus lokasi yang memiliki sub-lokasi');
                $this->showDeleteModal = false;
                return;
            }

            // Cek apakah ada stock movement (nanti akan ditambahkan)
            // if ($location->stockMovements()->exists()) {
            //     session()->flash('error', 'Tidak dapat menghapus lokasi yang memiliki riwayat stok');
            //     $this->showDeleteModal = false;
            //     return;
            // }

            $location->delete();
            session()->flash('success', 'Lokasi berhasil dihapus');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            Log::error('Location delete error: ' . $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->locationToDelete = null;
    }

    /**
     * Toggle expand/collapse tree node
     */
    public function toggleNode($id)
    {
        if (in_array($id, $this->expandedNodes)) {
            $this->expandedNodes = array_diff($this->expandedNodes, [$id]);
        } else {
            $this->expandedNodes[] = $id;
        }
    }

    /**
     * Select location for details
     */
    public function selectLocation($id)
    {
        $this->selectedLocation = Location::with(['parent', 'children'])->find($id);
    }

    /**
     * Generate tree data
     */
    public function getTreeData()
    {
        return Location::with('children')
            ->whereNull('parent_id')
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->get();
    }

    /**
     * Sort by field
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Reset filters
     */
    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = '';
        $this->filterParent = '';
        $this->resetPage();
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        return [
            'total' => Location::count(),
            'warehouses' => Location::where('type', 'warehouse')->count(),
            'racks' => Location::where('type', 'rack')->count(),
            'bins' => Location::where('type', 'bin')->count(),
        ];
    }

    /**
     * Render component
     */
    public function render()
    {
        $query = Location::query()
            ->with(['parent', 'children'])
            ->when($this->search, function ($q) {
                $q->where(function ($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterParent, fn($q) => $q->where('parent_id', $this->filterParent))
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.locations.location-manager', [
            'locations' => $query->paginate($this->perPage),
            'parentOptions' => Location::getParentOptions($this->locationId),
            'typeOptions' => Location::getTypeOptions(),
            'treeData' => $this->getTreeData(),
            'statistics' => $this->getStatistics(),
        ]);
    }
}