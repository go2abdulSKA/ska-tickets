<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Index extends Component
{
    public $users;

    // form fields used inside modal slot
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected $listeners = [
        'openModal' => 'handleOpenModal', // listen for modal open so we can reset for create
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ];

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::orderBy('id', 'desc')->get();
    }

    /**
     * When modal opens, reset the form if the modal is the create-user modal.
     * $data is the payload we emitted when opening the modal.
     */
    public function handleOpenModal($data = [])
    {
        // ensure $data is array (Livewire may pass objects)
        if (is_object($data)) {
            $data = (array) $data;
        }

        if (($data['modalId'] ?? '') === 'createUserModal') {
            // reset the form fields and validation
            $this->reset(['name', 'email', 'password', 'password_confirmation']);
            $this->resetValidation();
        }
    }

    public function saveUser()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // reload users list
        $this->loadUsers();

        // close modal by dispatching the hide event expected by your modal component
        // We will dispatch to the modal's modalId so it hides itself
        $this->dispatch('hide-modal', modalId: 'createUserModal');

        session()->flash('message', 'User created successfully.');

        // optional: emit a global event if other components care
        $this->emit('userCreated', $user->id);
    }

    public function render()
    {
        return view('livewire.users.index')->extends('admin.layout');
    }
}
