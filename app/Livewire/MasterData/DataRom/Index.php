<?php

namespace App\Livewire\MasterData\DataRom;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MasterData\DataRom;

class Index extends Component
{
    use WithPagination;

    public $nik = '', $name = '', $alias = '', $phone = '', $email = '', $status = true;
    public $dataRomId = null;
    public $showModal = false;
    public $isEdit = false;
    public $search = '';

    protected $listeners = ['deleteConfirmed'];

    protected function rules()
    {
        $id = $this->dataRomId ?? 'NULL';
        return [
            'nik' => 'nullable|unique:data_r_o_m_s,nik,' . $id,
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:data_r_o_m_s,email,' . $id,
            'status' => 'required|boolean',
        ];
    }

    public function render()
    {
        $records = DataRom::query()
            ->when($this->search, fn($q) =>
            $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(10);

        return view('livewire.master-data.data-rom.index', compact('records'));
    }

    public function resetFields()
    {
        $this->nik = $this->name = $this->alias = $this->phone = $this->email = '';
        $this->status = true;
        $this->dataRomId = null;
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

        DataRom::create([
            'nik' => $this->nik,
            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
        ]);

        $this->showModal = false;
        $this->resetFields();
        $this->dispatch('showSuccess', 'Data berhasil ditambah!');
    }

    public function edit($id)
    {
        $data = DataRom::findOrFail($id);
        $this->dataRomId = $data->id;
        $this->nik = $data->nik ?? '';
        $this->name = $data->name;
        $this->alias = $data->alias ?? '';
        $this->phone = $data->phone ?? '';
        $this->email = $data->email;
        $this->status = $data->status;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        $data = DataRom::findOrFail($this->dataRomId);
        $data->update([
            'nik' => $this->nik,
            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
        ]);

        $this->showModal = false;
        $this->resetFields();
        $this->dispatch('showSuccess', 'Data berhasil diupdate!');
    }

    public function deleteConfirmed($id)
    {
        DataRom::findOrFail($id)->delete();
        $this->dispatch('showSuccess', 'Data berhasil dihapus!');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
}
