<?php
// app/Livewire/Components/Modal.php

namespace App\Livewire\Components;

use Livewire\Component;

/**
 * Generic Modal Component
 *
 * This is a reusable modal component that can be used throughout the application
 * for Add/Edit forms.
 *
 * Features:
 * - Dynamic title and size
 * - Form submission handling
 * - Validation error display
 * - Loading states
 * - Close on success
 *
 * Usage:
 * 1. Emit 'openModal' event with data
 * 2. Emit 'closeModal' event to close
 */
class Modal extends Component
{
    // Modal properties
    public $modalId = 'genericModal';
    public $modalTitle = 'Modal Title';
    public $modalSize = 'modal-lg'; // modal-sm, modal-lg, modal-xl, modal-full
    public $isOpen = false;

    // Form properties
    public $formId = null;
    public $formData = [];

    // Loading state
    public $isLoading = false;

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'openModal' => 'open',
        'closeModal' => 'close',
    ];

    /**
     * Mount the component
     */
    public function mount($modalId = 'genericModal', $title = 'Modal', $size = 'modal-lg')
    {
        $this->modalId = $modalId;
        $this->modalTitle = $title;
        $this->modalSize = $size;
    }

    /**
     * Open modal
     *
     * @param array $data Optional data to populate form
     */
    public function open($data = [])
    {
        $this->formId = $data['id'] ?? null;
        $this->formData = $data;
        $this->isOpen = true;
        $this->resetValidation();

        // Dispatch browser event to show modal
        $this->dispatch('show-modal', modalId: $this->modalId);
    }

    /**
     * Close modal
     */
    public function close()
    {
        $this->isOpen = false;
        $this->formId = null;
        $this->formData = [];
        $this->isLoading = false;
        $this->resetValidation();

        // Dispatch browser event to hide modal
        $this->dispatch('hide-modal', modalId: $this->modalId);
    }

    /**
     * Reset form
     */
    public function resetForm()
    {
        $this->formId = null;
        $this->formData = [];
        $this->resetValidation();
    }

    /**
     * Set loading state
     */
    public function setLoading($state = true)
    {
        $this->isLoading = $state;
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.components.modal');
    }
}
