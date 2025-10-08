<?php
// app/Livewire/Components/Offcanvas.php

namespace App\Livewire\Components;

use Livewire\Component;

/**
 * Generic Offcanvas Component
 *
 * This is a reusable offcanvas component for displaying detailed views
 * and related information.
 *
 * Features:
 * - Slide from right/left/top/bottom
 * - Dynamic title and width
 * - Loading states
 * - Close on action
 *
 * Usage:
 * 1. Emit 'openOffcanvas' event with data
 * 2. Emit 'closeOffcanvas' event to close
 */
class Offcanvas extends Component
{
    // Offcanvas properties
    public $offcanvasId = 'genericOffcanvas';
    public $offcanvasTitle = 'Details';
    public $placement = 'end'; // start, end, top, bottom
    public $isOpen = false;

    // Data to display
    public $data = [];

    // Loading state
    public $isLoading = false;

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'openOffcanvas' => 'open',
        'closeOffcanvas' => 'close',
    ];

    /**
     * Mount the component
     */
    public function mount($offcanvasId = 'genericOffcanvas', $title = 'Details', $placement = 'end')
    {
        $this->offcanvasId = $offcanvasId;
        $this->offcanvasTitle = $title;
        $this->placement = $placement;
    }

    /**
     * Open offcanvas
     *
     * @param array $data Data to display
     */
    public function open($data = [])
    {
        $this->data = $data;
        $this->isOpen = true;
        $this->isLoading = false;

        // Dispatch browser event to show offcanvas
        $this->dispatch('show-offcanvas', offcanvasId: $this->offcanvasId);
    }

    /**
     * Close offcanvas
     */
    public function close()
    {
        $this->isOpen = false;
        $this->data = [];
        $this->isLoading = false;

        // Dispatch browser event to hide offcanvas
        $this->dispatch('hide-offcanvas', offcanvasId: $this->offcanvasId);
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
        return view('livewire.components.offcanvas');
    }
}
