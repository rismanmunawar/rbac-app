<?php

namespace App\Livewire\MasterData\IT;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\MasterData\DataIT;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $nik = '', $name = '', $alias = '', $designation = '', $image = null, $phone = '', $email = '', $status = true;
    public $dataItId = null;
    public $showModal = false;
    public $isEdit = false;
    public $oldImage = null;
    public $search = '';

    public $detailData = null;
    public $showDetailModal = false;

    protected function rules()
    {
        $id = $this->dataItId ?? 'NULL';
        return [
            'nik' => 'nullable|unique:data_i_t_s,nik,' . $id,
            'name' => 'required|string',
            'alias' => 'nullable|string',
            'designation' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:data_i_t_s,email,' . $id,
            'status' => 'required|boolean',
        ];
    }

    public function render()
    {
        $records = DataIT::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(10);

        return view('livewire.master-data.i-t.index', compact('records'));
    }

    public function resetFields()
    {
        $this->nik = $this->name = $this->alias = $this->designation = $this->phone = $this->email = '';
        $this->image = null;
        $this->status = true;
        $this->dataItId = null;
        $this->oldImage = null;
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        $payload = [
            'nik' => $this->nik,
            'name' => $this->name,
            'alias' => $this->alias,
            'designation' => $this->designation,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
        ];

        if ($this->image) {
            $folder = 'images/data-it';
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }
            $payload['image'] = $this->image->store($folder, 'public');
        }

        DataIT::create($payload);

        $this->showModal = false;
        $this->resetFields();
        $this->dispatch('showSuccess', 'Data berhasil ditambah!');
    }

    public function edit($id)
    {
        $data = DataIT::findOrFail($id);
        $this->dataItId = $data->id;
        $this->nik = $data->nik ?? '';
        $this->name = $data->name ?? '';
        $this->alias = $data->alias ?? '';
        $this->designation = $data->designation ?? '';
        $this->phone = $data->phone ?? '';
        $this->email = $data->email ?? '';
        $this->status = $data->status;
        $this->oldImage = $data->image ?? null;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $data = DataIT::findOrFail($this->dataItId);

        $payload = [
            'nik' => $this->nik,
            'name' => $this->name,
            'alias' => $this->alias,
            'designation' => $this->designation,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
        ];

        if ($this->image) {
            $folder = 'images/data-it';
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }

            $payload['image'] = $this->image->store($folder, 'public');
        }

        $data->update($payload);

        $this->showModal = false;
        $this->resetFields();
        $this->dispatch('showSuccess', 'Data berhasil diupdate!');
    }

    protected $listeners = ['deleteConfirmed'];

    public function deleteConfirmed($id)
    {
        $row = DataIT::findOrFail($id);
        if (!empty($row->image) && Storage::disk('public')->exists($row->image)) {
            Storage::disk('public')->delete($row->image);
        }
        $row->delete();
        $this->dispatch('showSuccess', 'Data berhasil dihapus!');
        $this->resetFields();
    }



    public function closeModal()
    {
        $this->showModal = false;
    }

    public function view($id)
    {
        $this->detailData = DataIT::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailData = null;
    }
}
