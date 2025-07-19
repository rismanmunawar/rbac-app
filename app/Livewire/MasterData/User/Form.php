<?php

namespace App\Livewire\MasterData\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Form extends Component
{
    public $userId;
    public $name, $email, $password;

    protected $rules = [
        'name' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
        }
    }

    public function save()
    {
        $this->validate($this->userId ? [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => 'nullable|min:6',
        ] : $this->rules);

        $user = $this->userId ? User::find($this->userId) : new User();
        $user->name = $this->name;
        $user->email = $this->email;
        if ($this->password) {
            $user->password = Hash::make($this->password);
        }
        $user->save();

        session()->flash('success', $this->userId ? 'User berhasil diperbarui.' : 'User berhasil ditambahkan.');
        return redirect()->route('users.index'); // Atur ke route daftar user
    }

    public function render()
    {
        return view('livewire.master-data.user.form');
    }
}
