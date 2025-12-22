<?php

namespace App\Livewire\Admin;

use App\Models\Package;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PackageManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $modalOpen = false;
    public $modalMode = 'create'; // create or edit
    public $packageId;

    // Form fields
    public $name;
    public $max_capacity;
    public $min_capacity;
    public $week_type = 'weekdays';
    public $price;
    public $price_type = 'pack';
    public $benefits = [];
    public $benefitInput = '';
    public $total_stays;
    public $is_published = false;
    public $photo;
    public $existingPhoto;

    // Search & Filter
    public $search = '';
    public $filterWeekType = '';
    public $filterPublished = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'max_capacity' => 'nullable|integer|min:1',
        'min_capacity' => 'nullable|integer|min:1',
        'week_type' => 'required|in:weekdays,weekends',
        'price' => 'required|integer|min:0',
        'price_type' => 'required|in:pack,night',
        'total_stays' => 'nullable|string',
        'photo' => 'nullable|image|max:2048',
    ];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->modalOpen = true;
    }

    public function openEditModal($id)
    {
        $package = Package::findOrFail($id);

        $this->packageId = $package->id;
        $this->name = $package->name;
        $this->max_capacity = $package->max_capacity;
        $this->min_capacity = $package->min_capacity;
        $this->week_type = $package->week_type;
        $this->price = $package->price;
        $this->price_type = $package->price_type;
        $this->benefits = $package->benefits ?? [];
        $this->total_stays = $package->total_stays;
        $this->is_published = $package->is_published;
        $this->existingPhoto = $package->photo;

        $this->modalMode = 'edit';
        $this->modalOpen = true;
    }

    public function addBenefit()
    {
        if (!empty($this->benefitInput)) {
            $this->benefits[] = $this->benefitInput;
            $this->benefitInput = '';
        }
    }

    public function removeBenefit($index)
    {
        unset($this->benefits[$index]);
        $this->benefits = array_values($this->benefits);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'max_capacity' => $this->max_capacity,
            'min_capacity' => $this->min_capacity,
            'week_type' => $this->week_type,
            'price' => $this->price,
            'price_type' => $this->price_type,
            'benefits' => $this->benefits,
            'total_stays' => $this->total_stays,
            'is_published' => $this->is_published,
        ];

        if ($this->photo) {
            $photoPath = $this->photo->store('packages', 'public');
            $data['photo'] = '/storage/' . $photoPath;
        }

        if ($this->modalMode === 'create') {
            Package::create($data);
            session()->flash('message', 'Paket berhasil ditambahkan.');
        } else {
            $package = Package::findOrFail($this->packageId);
            if (!$this->photo && $this->existingPhoto) {
                $data['photo'] = $this->existingPhoto;
            }
            $package->update($data);
            session()->flash('message', 'Paket berhasil diperbarui.');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function togglePublish($id)
    {
        $package = Package::findOrFail($id);
        $package->update(['is_published' => !$package->is_published]);
        session()->flash('message', 'Status publikasi diperbarui.');
    }

    public function delete($id)
    {
        Package::findOrFail($id)->delete();
        session()->flash('message', 'Paket berhasil dihapus.');
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'packageId',
            'name',
            'max_capacity',
            'min_capacity',
            'week_type',
            'price',
            'price_type',
            'benefits',
            'benefitInput',
            'total_stays',
            'is_published',
            'photo',
            'existingPhoto'
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        $packages = Package::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->filterWeekType, fn($q) => $q->where('week_type', $this->filterWeekType))
            ->when($this->filterPublished !== '', fn($q) => $q->where('is_published', $this->filterPublished))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.package-management', compact('packages'));
    }
}
